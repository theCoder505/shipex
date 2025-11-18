import express from 'express';
import { WebSocketServer } from 'ws';
import http from 'http';
import cors from 'cors';

const app = express();
const port = 3000;

app.use(cors());
app.use(express.json());

const server = http.createServer(app);

const wss = new WebSocketServer({
    server,
    clientTracking: true,
    perMessageDeflate: false
});

// Store clients by user ID with additional metadata
const clients = new Map();

// Track last active timestamps
const lastActive = new Map();

// Track typing status
const typingStatus = new Map();

// Track which users have which chats open
const activeChats = new Map(); // userId => Set of chat partner IDs

// Track connection attempts to prevent spam
const connectionAttempts = new Map();
const MAX_CONNECTION_ATTEMPTS = 5;
const CONNECTION_TIMEOUT = 30000; // 30 seconds

wss.on('connection', (ws, req) => {
    const url = new URL(req.url, `ws://${req.headers.host}`);
    const userId = url.searchParams.get('userId');
    const userType = url.searchParams.get('userType');

    if (!userId || !userType) {
        ws.close(1008, 'User ID and Type required');
        return;
    }

    // Check for connection spam
    const now = Date.now();
    const attempts = connectionAttempts.get(userId) || [];
    const recentAttempts = attempts.filter(time => now - time < CONNECTION_TIMEOUT);

    if (recentAttempts.length >= MAX_CONNECTION_ATTEMPTS) {
        console.log(`Blocking connection spam from user: ${userId}`);
        ws.close(1008, 'Too many connection attempts');
        return;
    }

    // Record this connection attempt
    recentAttempts.push(now);
    connectionAttempts.set(userId, recentAttempts);

    console.log(`New client connected: ${userId} (${userType})`);

    // Close any existing connection for this user
    if (clients.has(userId)) {
        const oldClient = clients.get(userId);
        if (oldClient.ws.readyState === 1) {
            oldClient.ws.close(1000, 'New connection established');
        }
        clients.delete(userId);
    }

    clients.set(userId, {
        ws,
        userType,
        connectedAt: new Date(),
        ip: req.socket.remoteAddress
    });

    lastActive.set(userId, new Date());

    // Initialize active chats set for this user
    if (!activeChats.has(userId)) {
        activeChats.set(userId, new Set());
    }

    // Set up heartbeat
    const heartbeatInterval = setInterval(() => {
        if (ws.readyState === 1) {
            ws.ping();
        }
    }, 30000);

    ws.on('pong', () => {
        lastActive.set(userId, new Date());
    });

    try {
        ws.send(JSON.stringify({
            type: 'connected',
            message: 'Connected to chat server',
            userId: userId,
            timestamp: new Date().toISOString()
        }));
    } catch (error) {
        console.error(`Error sending connection confirmation to ${userId}:`, error);
    }

    broadcastOnlineStatus(userId);

    ws.on('message', (message) => {
        try {
            const data = JSON.parse(message);
            console.log(`📨 Received from ${userId}:`, data.type);

            lastActive.set(userId, new Date());

            switch (data.type) {
                case 'typing':
                    handleTyping(userId, data);
                    break;

                case 'stop_typing':
                    handleStopTyping(userId, data);
                    break;

                case 'get_online_status':
                    sendOnlineStatus(userId, data.checkUserId);
                    break;

                case 'ping':
                    ws.send(JSON.stringify({
                        type: 'pong',
                        timestamp: new Date().toISOString()
                    }));
                    break;

                case 'chat_opened':
                    handleChatOpened(userId, data);
                    break;

                case 'chat_closed':
                    handleChatClosed(userId, data);
                    break;

                case 'chat_message_sent':
                    handleChatMessageSent(userId, data);
                    break;

                case 'mark_messages_seen':
                    handleMarkMessagesSeen(userId, data);
                    break;

                case 'new_text_message':
                    handleNewTextMessage(userId, data);
                    break;

                case 'new_file_message':
                    handleNewFileMessage(userId, data);
                    break;

                case 'get_total_unread_count':
                    handleGetTotalUnreadCount(userId);
                    break;

                default:
                    console.warn('Unknown message type:', data.type);
            }
        } catch (error) {
            console.error('Error processing message:', error);
        }
    });

    ws.on('close', (code, reason) => {
        console.log(`Client disconnected: ${userId} (Code: ${code}, Reason: ${reason})`);
        clearInterval(heartbeatInterval);
        clients.delete(userId);
        lastActive.delete(userId);
        typingStatus.delete(userId);
        activeChats.delete(userId);
        broadcastOnlineStatus(userId, true);
    });

    ws.on('error', (error) => {
        console.error(`WebSocket error for user ${userId}:`, error);
        clearInterval(heartbeatInterval);
    });

    // Handle connection timeout
    ws.on('timeout', () => {
        console.log(`Connection timeout for user ${userId}`);
        ws.close(1001, 'Connection timeout');
    });
});

// HTTP endpoint to receive notifications from Laravel backend
app.post('/api/notify', (req, res) => {
    try {
        const data = req.body;
        console.log('📨 Received notification from Laravel backend:', data.type);

        switch (data.type) {
            case 'new_text_message':
                handleNewTextMessageFromLaravel(data);
                break;

            case 'new_file_message':
                handleNewFileMessageFromLaravel(data);
                break;

            case 'messages_marked_seen':
                handleMessagesSeenFromLaravel(data);
                break;

            case 'user_activity':
                updateUserActivity(data);
                break;

            default:
                console.warn('Unknown notification type:', data.type);
        }

        res.json({ status: 'success', message: 'Notification processed' });
    } catch (error) {
        console.error('Error processing notification:', error);
        res.status(500).json({ status: 'error', message: error.message });
    }
});

// Handle get total unread count request
function handleGetTotalUnreadCount(userId) {
    console.log(`📊 Get total unread count request from: ${userId}`);

    // Notify client to fetch from Laravel backend
    // The actual count is stored in database, so we tell client to fetch via AJAX
    if (clients.has(userId)) {
        const client = clients.get(userId);
        if (client.ws.readyState === 1) {
            try {
                client.ws.send(JSON.stringify({
                    type: 'fetch_unread_count_from_server',
                    timestamp: new Date().toISOString()
                }));
                console.log(`✅ Requested ${userId} to fetch unread count from server`);
            } catch (error) {
                console.error(`Failed to request unread count for ${userId}:`, error);
            }
        }
    }
}

// Handle new text message from Laravel
function handleNewTextMessageFromLaravel(data) {
    const { senderId, receiverId, message, messageUid, timestamp, seen, bothUsersChatting } = data;

    if (!receiverId || !message) {
        console.warn('Invalid message data from Laravel');
        return;
    }

    console.log(`📨 Laravel text message from ${senderId} to ${receiverId}`);

    // Check if receiver has chat open with sender
    const chatIsOpen = isChatActive(receiverId, senderId);
    console.log(`Chat open status: ${receiverId} with ${senderId} = ${chatIsOpen}`);

    // Send to receiver if they have an active WebSocket connection
    if (clients.has(receiverId)) {
        const receiver = clients.get(receiverId);
        if (receiver.ws.readyState === 1) {
            const messageData = {
                type: 'new_message',
                messageType: 'text',
                senderId: senderId,
                receiverId: receiverId,
                message: message,
                messageUid: messageUid,
                timestamp: timestamp,
                seen: seen || chatIsOpen,
                chatIsOpen: chatIsOpen
            };

            try {
                receiver.ws.send(JSON.stringify(messageData));
                console.log(`✅ Text message sent to ${receiverId}`);
            } catch (error) {
                console.error(`Failed to send text message to ${receiverId}:`, error);
            }

            // Update chat list for receiver
            updateChatList(receiverId, senderId, {
                message: message,
                messageType: 'text',
                timestamp: timestamp
            });

            // If chat is open, automatically mark as seen
            if (chatIsOpen && !seen) {
                console.log(`👁️ Auto-marking text message ${messageUid} as seen (chat is open)`);
                // Notify sender that message was seen
                if (clients.has(senderId)) {
                    const sender = clients.get(senderId);
                    if (sender.ws.readyState === 1) {
                        try {
                            sender.ws.send(JSON.stringify({
                                type: 'message_seen',
                                messageUid: messageUid,
                                seenBy: receiverId,
                                timestamp: new Date().toISOString()
                            }));
                            console.log(`✅ Notified sender ${senderId} about seen message`);
                        } catch (error) {
                            console.error(`Failed to notify sender ${senderId}:`, error);
                        }
                    }
                }
            }
        }
    }

    // Update sender's chat list regardless of receiver's online status
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: receiverId,
                    timestamp: new Date().toISOString(),
                    isSentMessage: true,
                    messageData: {
                        message: message,
                        messageType: 'text',
                        timestamp: timestamp
                    }
                }));
                console.log(`✅ Chat list updated for sender ${senderId}`);
            } catch (error) {
                console.error(`Failed to update chat list for sender ${senderId}:`, error);
            }
        }
    }
}

// Handle new file message from Laravel
function handleNewFileMessageFromLaravel(data) {
    const { senderId, receiverId, fileData, messageText, messageUid, timestamp, seen, bothUsersChatting } = data;

    if (!receiverId || !fileData) {
        console.warn('Invalid file message data from Laravel');
        return;
    }

    console.log(`📎 Laravel file message from ${senderId} to ${receiverId}`);

    // Check if receiver has chat open with sender
    const chatIsOpen = isChatActive(receiverId, senderId);

    // Send to receiver if they have an active WebSocket connection
    if (clients.has(receiverId)) {
        const receiver = clients.get(receiverId);
        if (receiver.ws.readyState === 1) {
            const messageData = {
                type: 'new_message',
                messageType: 'file',
                senderId: senderId,
                receiverId: receiverId,
                fileData: fileData,
                messageText: messageText || '',
                messageUid: messageUid,
                timestamp: timestamp,
                seen: seen || chatIsOpen,
                chatIsOpen: chatIsOpen
            };

            try {
                receiver.ws.send(JSON.stringify(messageData));
                console.log(`✅ File message sent to ${receiverId}`);
            } catch (error) {
                console.error(`Failed to send file message to ${receiverId}:`, error);
            }

            // Update chat list for receiver
            updateChatList(receiverId, senderId, {
                message: messageText || 'File',
                messageType: 'file',
                timestamp: timestamp
            });

            // If chat is open, automatically mark as seen
            if (chatIsOpen && !seen) {
                console.log(`👁️ Auto-marking file message ${messageUid} as seen (chat is open)`);
                // Notify sender that message was seen
                if (clients.has(senderId)) {
                    const sender = clients.get(senderId);
                    if (sender.ws.readyState === 1) {
                        try {
                            sender.ws.send(JSON.stringify({
                                type: 'message_seen',
                                messageUid: messageUid,
                                seenBy: receiverId,
                                timestamp: new Date().toISOString()
                            }));
                            console.log(`✅ Notified sender ${senderId} about seen file`);
                        } catch (error) {
                            console.error(`Failed to notify sender ${senderId}:`, error);
                        }
                    }
                }
            }
        }
    }

    // Update sender's chat list regardless of receiver's online status
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: receiverId,
                    timestamp: new Date().toISOString(),
                    isSentMessage: true,
                    messageData: {
                        message: messageText || 'File',
                        messageType: 'file',
                        timestamp: timestamp
                    }
                }));
                console.log(`✅ Chat list updated for sender ${senderId}`);
            } catch (error) {
                console.error(`Failed to update chat list for sender ${senderId}:`, error);
            }
        }
    }
}

// Handle messages seen from Laravel
function handleMessagesSeenFromLaravel(data) {
    const { senderId, receiverId, messageUids } = data;

    if (!receiverId || !messageUids) {
        console.warn('Invalid messages seen data from Laravel');
        return;
    }

    console.log(`👀 Laravel: Messages seen by ${senderId} from ${receiverId}`);

    // Notify the original sender that their messages were seen
    if (clients.has(receiverId)) {
        const receiver = clients.get(receiverId);
        if (receiver.ws.readyState === 1) {
            try {
                receiver.ws.send(JSON.stringify({
                    type: 'messages_seen',
                    senderId: senderId,
                    messageUids: messageUids,
                    timestamp: new Date().toISOString()
                }));
                console.log(`✅ Messages seen notification sent to ${receiverId}`);
            } catch (error) {
                console.error(`Failed to send messages seen to ${receiverId}:`, error);
            }
        }
    }
}

// Enhanced active chat tracking
function isChatActive(userId, withUserId) {
    if (!activeChats.has(userId)) {
        return false;
    }

    const isActive = activeChats.get(userId).has(withUserId);
    console.log(`Chat active check: ${userId} with ${withUserId} = ${isActive}`);

    return isActive;
}

// Handle chat opened - track active chat
function handleChatOpened(userId, data) {
    const { withUserId } = data;

    if (!withUserId) return;

    // Add to active chats
    if (!activeChats.has(userId)) {
        activeChats.set(userId, new Set());
    }
    activeChats.get(userId).add(withUserId);

    console.log(`🔓 User ${userId} opened chat with ${withUserId}`);

    // When a chat is opened, notify the other user and mark messages as seen
    if (clients.has(withUserId)) {
        const otherUser = clients.get(withUserId);
        if (otherUser.ws.readyState === 1) {
            try {
                otherUser.ws.send(JSON.stringify({
                    type: 'user_chat_opened',
                    userId: userId,
                    timestamp: new Date().toISOString()
                }));
                console.log(`✅ Notified ${withUserId} that ${userId} opened chat`);
            } catch (error) {
                console.error(`Failed to notify ${withUserId}:`, error);
            }
        }
    }

    // Mark all unseen messages as seen
    markAllUnseenMessagesAsSeen(userId, withUserId);
}

// Handle chat closed - remove from active chats
function handleChatClosed(userId, data) {
    const { withUserId } = data;

    if (!withUserId) return;

    // Remove from active chats
    if (activeChats.has(userId)) {
        activeChats.get(userId).delete(withUserId);
    }

    console.log(`🔒 User ${userId} closed chat with ${withUserId}`);
}

function handleChatMessageSent(senderId, data) {
    const { receiverId, messageData } = data;

    if (!receiverId) return;

    console.log(`💬 User ${senderId} sent message to ${receiverId}`);

    // Update sender's own chat list across all their devices
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: receiverId,
                    timestamp: new Date().toISOString(),
                    isSentMessage: true
                }));
                console.log(`✅ Chat list updated for sender ${senderId}`);
            } catch (error) {
                console.error(`Failed to update chat list for sender ${senderId}:`, error);
            }
        }
    }
}

// Handle new text message from client (direct WebSocket)
function handleNewTextMessage(senderId, data) {
    const { receiverId, message, messageUid, timestamp } = data;

    if (!receiverId || !message) {
        console.warn('Invalid message data from client');
        return;
    }

    console.log(`📨 Client text message from ${senderId} to ${receiverId}`);

    // Check if receiver has chat open with sender
    const chatIsOpen = isChatActive(receiverId, senderId);

    // Send to receiver if they have an active WebSocket connection
    if (clients.has(receiverId)) {
        const receiver = clients.get(receiverId);
        if (receiver.ws.readyState === 1) {
            const messageData = {
                type: 'new_message',
                messageType: 'text',
                senderId: senderId,
                receiverId: receiverId,
                message: message,
                messageUid: messageUid,
                timestamp: timestamp,
                seen: chatIsOpen,
                chatIsOpen: chatIsOpen
            };

            try {
                receiver.ws.send(JSON.stringify(messageData));
                console.log(`✅ Text message sent to ${receiverId}`);
            } catch (error) {
                console.error(`Failed to send text message to ${receiverId}:`, error);
            }

            // Update chat list for receiver
            updateChatList(receiverId, senderId, {
                message: message,
                messageType: 'text',
                timestamp: timestamp
            });

            // If chat is open, automatically mark as seen
            if (chatIsOpen) {
                console.log(`👁️ Auto-marking text message ${messageUid} as seen (chat is open)`);
                // Notify sender that message was seen
                if (clients.has(senderId)) {
                    const sender = clients.get(senderId);
                    if (sender.ws.readyState === 1) {
                        try {
                            sender.ws.send(JSON.stringify({
                                type: 'message_seen',
                                messageUid: messageUid,
                                seenBy: receiverId,
                                timestamp: new Date().toISOString()
                            }));
                            console.log(`✅ Notified sender ${senderId} about seen message`);
                        } catch (error) {
                            console.error(`Failed to notify sender ${senderId}:`, error);
                        }
                    }
                }
            }
        }
    }

    // Update sender's chat list regardless of receiver's online status
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: receiverId,
                    timestamp: new Date().toISOString(),
                    isSentMessage: true,
                    messageData: {
                        message: message,
                        messageType: 'text',
                        timestamp: timestamp
                    }
                }));
                console.log(`✅ Chat list updated for sender ${senderId}`);
            } catch (error) {
                console.error(`Failed to update chat list for sender ${senderId}:`, error);
            }
        }
    }
}

// Handle new file message from client (direct WebSocket)
function handleNewFileMessage(senderId, data) {
    const { receiverId, fileData, messageText, messageUid, timestamp } = data;

    if (!receiverId || !fileData) {
        console.warn('Invalid file message data from client');
        return;
    }

    console.log(`📎 Client file message from ${senderId} to ${receiverId}`);

    // Check if receiver has chat open with sender
    const chatIsOpen = isChatActive(receiverId, senderId);

    // Send to receiver if they have an active WebSocket connection
    if (clients.has(receiverId)) {
        const receiver = clients.get(receiverId);
        if (receiver.ws.readyState === 1) {
            const messageData = {
                type: 'new_message',
                messageType: 'file',
                senderId: senderId,
                receiverId: receiverId,
                fileData: fileData,
                messageText: messageText || '',
                messageUid: messageUid,
                timestamp: timestamp,
                seen: chatIsOpen,
                chatIsOpen: chatIsOpen
            };

            try {
                receiver.ws.send(JSON.stringify(messageData));
                console.log(`✅ File message sent to ${receiverId}`);
            } catch (error) {
                console.error(`Failed to send file message to ${receiverId}:`, error);
            }

            // Update chat list for receiver
            updateChatList(receiverId, senderId, {
                message: messageText || 'File',
                messageType: 'file',
                timestamp: timestamp
            });

            // If chat is open, automatically mark as seen
            if (chatIsOpen) {
                console.log(`👁️ Auto-marking file message ${messageUid} as seen (chat is open)`);
                // Notify sender that message was seen
                if (clients.has(senderId)) {
                    const sender = clients.get(senderId);
                    if (sender.ws.readyState === 1) {
                        try {
                            sender.ws.send(JSON.stringify({
                                type: 'message_seen',
                                messageUid: messageUid,
                                seenBy: receiverId,
                                timestamp: new Date().toISOString()
                            }));
                            console.log(`✅ Notified sender ${senderId} about seen file`);
                        } catch (error) {
                            console.error(`Failed to notify sender ${senderId}:`, error);
                        }
                    }
                }
            }
        }
    }

    // Update sender's chat list regardless of receiver's online status
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: receiverId,
                    timestamp: new Date().toISOString(),
                    isSentMessage: true,
                    messageData: {
                        message: messageText || 'File',
                        messageType: 'file',
                        timestamp: timestamp
                    }
                }));
                console.log(`✅ Chat list updated for sender ${senderId}`);
            } catch (error) {
                console.error(`Failed to update chat list for sender ${senderId}:`, error);
            }
        }
    }
}

// Handle marking messages as seen (sent from client)
function handleMarkMessagesSeen(userId, data) {
    const { messageUids, senderId } = data;

    if (!messageUids || !senderId) {
        console.warn('Invalid mark messages seen data');
        return;
    }

    console.log(`👀 User ${userId} marking messages as seen from ${senderId}:`, messageUids);

    // Notify the sender that their messages were seen
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'messages_seen',
                    senderId: userId,
                    messageUids: messageUids,
                    timestamp: new Date().toISOString()
                }));
                console.log(`✅ Notified sender ${senderId} about seen messages`);
            } catch (error) {
                console.error(`Failed to notify sender ${senderId}:`, error);
            }
        }
    }
}

// Mark all unseen messages as seen when chat is opened
function markAllUnseenMessagesAsSeen(userId, withUserId) {
    console.log(`🔵 Marking all unseen messages from ${withUserId} as seen for ${userId}`);

    // Notify the other user that all their messages were seen
    if (clients.has(withUserId)) {
        const sender = clients.get(withUserId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'all_messages_seen',
                    seenBy: userId,
                    timestamp: new Date().toISOString()
                }));
                console.log(`✅ Notified ${withUserId} that all messages were seen by ${userId}`);
            } catch (error) {
                console.error(`Failed to notify ${withUserId}:`, error);
            }
        }
    }
}

// Handle typing indicator
function handleTyping(senderId, data) {
    const { receiverId } = data;

    if (!receiverId) return;

    typingStatus.set(`${senderId}_${receiverId}`, true);

    if (clients.has(receiverId)) {
        const receiver = clients.get(receiverId);
        if (receiver.ws.readyState === 1) {
            try {
                receiver.ws.send(JSON.stringify({
                    type: 'user_typing',
                    senderId: senderId,
                    isTyping: true
                }));
            } catch (error) {
                console.error(`Failed to send typing indicator to ${receiverId}:`, error);
            }
        }
    }
}

// Handle stop typing
function handleStopTyping(senderId, data) {
    const { receiverId } = data;

    if (!receiverId) return;

    typingStatus.delete(`${senderId}_${receiverId}`);

    if (clients.has(receiverId)) {
        const receiver = clients.get(receiverId);
        if (receiver.ws.readyState === 1) {
            try {
                receiver.ws.send(JSON.stringify({
                    type: 'user_typing',
                    senderId: senderId,
                    isTyping: false
                }));
            } catch (error) {
                console.error(`Failed to send stop typing to ${receiverId}:`, error);
            }
        }
    }
}

// Enhanced update chat list function
function updateChatList(userId, newMessageFrom, messageData = null) {
    if (clients.has(userId)) {
        const user = clients.get(userId);
        if (user.ws.readyState === 1) {
            try {
                const payload = {
                    type: 'update_chat_list',
                    fromUserId: newMessageFrom,
                    timestamp: new Date().toISOString()
                };

                // Add message data if available for creating new chat tabs
                if (messageData) {
                    payload.messageData = messageData;
                    payload.senderId = newMessageFrom;
                }

                user.ws.send(JSON.stringify(payload));
                console.log(`✅ Chat list updated for ${userId} from ${newMessageFrom}`);
            } catch (error) {
                console.error(`Failed to update chat list for ${userId}:`, error);
            }
        }
    }
}

// Send online status for specific user
function sendOnlineStatus(requesterId, checkUserId) {
    if (!clients.has(requesterId)) return;

    const requester = clients.get(requesterId);
    const isOnline = clients.has(checkUserId);

    let lastActiveTime = null;
    if (!isOnline && lastActive.has(checkUserId)) {
        lastActiveTime = lastActive.get(checkUserId).toISOString();
    }

    if (requester.ws.readyState === 1) {
        try {
            requester.ws.send(JSON.stringify({
                type: 'online_status',
                userId: checkUserId,
                isOnline: isOnline,
                lastActive: lastActiveTime,
                timestamp: new Date().toISOString()
            }));
        } catch (error) {
            console.error(`Failed to send online status to ${requesterId}:`, error);
        }
    }
}

// Broadcast online status change
function broadcastOnlineStatus(userId, isDisconnect = false) {
    const payload = {
        type: 'user_status_changed',
        userId: userId,
        isOnline: !isDisconnect,
        timestamp: new Date().toISOString()
    };

    clients.forEach((client, clientId) => {
        if (client.ws.readyState === 1 && clientId !== userId) {
            try {
                client.ws.send(JSON.stringify(payload));
            } catch (error) {
                console.error(`Failed to broadcast status to ${clientId}:`, error);
            }
        }
    });
}

// Update the lastActive tracking to be more robust
function updateUserActivity(data) {
    const { userId, timestamp } = data;
    lastActive.set(userId, new Date());
    console.log(`🔄 User activity updated: ${userId}`);

    // Broadcast online status if user was previously offline
    if (!clients.has(userId)) {
        broadcastOnlineStatus(userId, false);
    }
}

// Enhanced isUserOnline function
function isUserOnline(userId) {
    if (clients.has(userId)) {
        const client = clients.get(userId);
        if (client.ws.readyState === 1) {
            return true;
        }
    }

    // Check last activity - user is online if active within 15 minutes
    const lastActivity = lastActive.get(userId);
    if (lastActivity) {
        const minutesSinceLastActivity = (new Date() - lastActivity) / (1000 * 60);
        return minutesSinceLastActivity <= 15; // 15-minute window
    }

    return false;
}

// Clean up old connection attempts periodically
setInterval(() => {
    const now = Date.now();
    connectionAttempts.forEach((attempts, userId) => {
        const recentAttempts = attempts.filter(time => now - time < CONNECTION_TIMEOUT);
        if (recentAttempts.length === 0) {
            connectionAttempts.delete(userId);
        } else {
            connectionAttempts.set(userId, recentAttempts);
        }
    });
}, 60000); // Clean every minute

// Heartbeat to detect inactive connections
setInterval(() => {
    const now = new Date();
    const inactiveThreshold = 1000 * 60 * 15; // 15 minutes

    clients.forEach((client, userId) => {
        const lastActiveTime = lastActive.get(userId);
        if (lastActiveTime && (now - lastActiveTime) > inactiveThreshold) {
            console.log(`💤 Closing inactive connection for ${userId}`);
            try {
                client.ws.close(1000, 'Inactive connection');
            } catch (error) {
                console.error(`Error closing inactive connection for ${userId}:`, error);
            }
        }
    });
}, 1000 * 60); // Check every minute

// Health check endpoint
app.get('/health', (req, res) => {
    res.json({
        status: 'ok',
        connectedClients: clients.size,
        activeChats: activeChats.size,
        timestamp: new Date().toISOString(),
        memoryUsage: process.memoryUsage(),
        uptime: process.uptime()
    });
});

// Server status endpoint
app.get('/status', (req, res) => {
    res.json({
        server: 'WebSocket Chat Server',
        status: 'running',
        port: port,
        connectedClients: clients.size,
        uptime: process.uptime(),
        timestamp: new Date().toISOString()
    });
});

// Debug endpoint to check active chats
app.get('/debug/active-chats', (req, res) => {
    const activeChatsData = {};
    activeChats.forEach((chats, userId) => {
        activeChatsData[userId] = Array.from(chats);
    });

    res.json({
        activeChats: activeChatsData,
        connectedClients: Array.from(clients.keys()),
        timestamp: new Date().toISOString()
    });
});

// Debug endpoint to check specific user status
app.get('/debug/user/:userId', (req, res) => {
    const userId = req.params.userId;
    const isConnected = clients.has(userId);
    const lastActiveTime = lastActive.get(userId);
    const userActiveChats = activeChats.get(userId);

    res.json({
        userId: userId,
        isConnected: isConnected,
        isOnline: isUserOnline(userId),
        lastActive: lastActiveTime ? lastActiveTime.toISOString() : null,
        activeChats: userActiveChats ? Array.from(userActiveChats) : [],
        timestamp: new Date().toISOString()
    });
});

// Start server
server.listen(port, '0.0.0.0', () => {
    console.log(`WebSocket server running on ws://localhost:${port}`);
    console.log(`HTTP API available at http://localhost:${port}`);
    console.log(`Health check available at http://localhost:${port}/health`);
    console.log(`Server status at http://localhost:${port}/status`);
    console.log(`Debug active chats at http://localhost:${port}/debug/active-chats`);
});

// Handle uncaught exceptions
process.on('uncaughtException', (error) => {
    console.error('Uncaught Exception:', error);
});

process.on('unhandledRejection', (reason, promise) => {
    console.error('Unhandled Rejection at:', promise, 'reason:', reason);
});

// Graceful shutdown
process.on('SIGTERM', () => {
    console.log('Shutting down WebSocket server...');

    clients.forEach((client) => {
        try {
            client.ws.close(1001, 'Server shutting down');
        } catch (error) {
            console.error('Error closing client connection:', error);
        }
    });

    wss.close(() => {
        server.close(() => {
            console.log('✅ WebSocket server closed gracefully');
            process.exit(0);
        });
    });
});

process.on('SIGINT', () => {
    console.log('Received SIGINT, shutting down gracefully...');

    clients.forEach((client) => {
        try {
            client.ws.close(1001, 'Server shutting down');
        } catch (error) {
            console.error('Error closing client connection:', error);
        }
    });

    wss.close(() => {
        server.close(() => {
            console.log('✅ WebSocket server closed gracefully');
            process.exit(0);
        });
    });
});