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
    // Add connection timeout handling
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
        console.log(`🚫 Blocking connection spam from user: ${userId}`);
        ws.close(1008, 'Too many connection attempts');
        return;
    }

    // Record this connection attempt
    recentAttempts.push(now);
    connectionAttempts.set(userId, recentAttempts);

    console.log(`✅ New client connected: ${userId} (${userType})`);
    
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
        console.error(`❌ Error sending connection confirmation to ${userId}:`, error);
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

                case 'message_seen':
                    handleMessageSeen(userId, data);
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

                default:
                    console.warn('Unknown message type:', data.type);
            }
        } catch (error) {
            console.error('Error processing message:', error);
        }
    });

    ws.on('close', (code, reason) => {
        console.log(`❌ Client disconnected: ${userId} (Code: ${code}, Reason: ${reason})`);
        clearInterval(heartbeatInterval);
        clients.delete(userId);
        lastActive.delete(userId);
        typingStatus.delete(userId);
        activeChats.delete(userId);
        broadcastOnlineStatus(userId, true);
    });

    ws.on('error', (error) => {
        console.error(`💥 WebSocket error for user ${userId}:`, error);
        clearInterval(heartbeatInterval);
    });

    // Handle connection timeout
    ws.on('timeout', () => {
        console.log(`⏰ Connection timeout for user ${userId}`);
        ws.close(1001, 'Connection timeout');
    });
});

// HTTP endpoint to receive notifications from Laravel backend
app.post('/api/notify', (req, res) => {
    try {
        const data = req.body;
        console.log('📩 Received notification from backend:', data.type);

        switch (data.type) {
            case 'new_text_message':
                notifyNewTextMessage(data);
                break;

            case 'new_file_message':
                notifyNewFileMessage(data);
                break;

            case 'messages_marked_seen':
                notifyMessagesSeen(data);
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

// Enhanced active chat tracking
function isChatActive(userId, withUserId) {
    if (!activeChats.has(userId)) {
        console.log(`📱 No active chats found for user ${userId}`);
        return false;
    }
    
    const isActive = activeChats.get(userId).has(withUserId);
    console.log(`💬 Chat active check: ${userId} with ${withUserId} = ${isActive}`);
    
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





// Notify about new text message (called by Laravel after DB save)
async function notifyNewTextMessage(data) {
    const { senderId, receiverId, message, messageUid, timestamp } = data;
    
    if (!receiverId || !message) {
        console.warn('Invalid message data');
        return;
    }

    // Check if receiver is online (either connected or active within 15 minutes)
    const receiverIsOnline = isUserOnline(receiverId);
    console.log(`📨 Text message from ${senderId} to ${receiverId}, receiver online: ${receiverIsOnline}`);

    // Get sender info for notification
    const senderInfo = await getUserInfoForNotification(senderId, getSenderType(senderId));

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
                senderName: senderInfo.senderName,
                profilePicture: senderInfo.profilePicture,
                receiverId: receiverId,
                message: message,
                messageUid: messageUid,
                timestamp: timestamp,
                seen: chatIsOpen,
                chatIsOpen: chatIsOpen
            };

            try {
                receiver.ws.send(JSON.stringify(messageData));
                console.log(`✅ Text message notification sent to ${receiverId}`);
            } catch (error) {
                console.error(`❌ Failed to send text message to ${receiverId}:`, error);
                clients.delete(receiverId); // Remove broken connection
            }

            updateChatList(receiverId, senderId);
            
            // If chat is open, automatically mark as seen
            if (chatIsOpen) {
                console.log(`👁️ Auto-marking text message ${messageUid} as seen`);
                
                // Notify sender that message was seen
                setTimeout(() => {
                    if (clients.has(senderId)) {
                        const sender = clients.get(senderId);
                        if (sender.ws.readyState === 1) {
                            try {
                                sender.ws.send(JSON.stringify({
                                    type: 'messages_seen',
                                    senderId: receiverId,
                                    messageUids: [messageUid],
                                    timestamp: new Date().toISOString(),
                                    autoSeen: true
                                }));
                                console.log(`✅ Auto-seen notification sent to sender ${senderId}`);
                            } catch (error) {
                                console.error(`❌ Failed to send auto-seen to ${senderId}:`, error);
                            }
                        }
                    }
                }, 100);
            }
        }
    } else if (receiverIsOnline) {
        console.log(`📱 Receiver ${receiverId} is online (recent activity) but not connected via WebSocket`);
        // User is considered online due to recent activity but doesn't have active WebSocket
        // The notification will be shown when they next load the page or reconnect
        
        // We can still update the chat list for when they reconnect
        updateChatList(receiverId, senderId);
    } else {
        console.log(`❌ Receiver ${receiverId} is offline, message stored for later`);
    }
    
    // Update sender's chat list regardless of receiver's online status
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: receiverId,
                    timestamp: new Date().toISOString()
                }));
                console.log(`✅ Chat list updated for sender ${senderId}`);
            } catch (error) {
                console.error(`❌ Failed to update chat list for sender ${senderId}:`, error);
            }
        }
    }
}



// Notify about new file message (called by Laravel after DB save)
async function notifyNewFileMessage(data) {
    const { senderId, receiverId, fileData, messageText, messageUid, timestamp } = data;
    
    if (!receiverId || !fileData) {
        console.warn('Invalid file message data');
        return;
    }

    // Check if receiver is online (either connected or active within 15 minutes)
    const receiverIsOnline = isUserOnline(receiverId);
    console.log(`📎 File message from ${senderId} to ${receiverId}, receiver online: ${receiverIsOnline}`);

    // Get sender info for notification
    const senderInfo = await getUserInfoForNotification(senderId, getSenderType(senderId));

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
                senderName: senderInfo.senderName,
                profilePicture: senderInfo.profilePicture,
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
                console.log(`✅ File message notification sent to ${receiverId}`);
            } catch (error) {
                console.error(`❌ Failed to send file message to ${receiverId}:`, error);
                clients.delete(receiverId); // Remove broken connection
            }

            updateChatList(receiverId, senderId);
            
            // If chat is open, automatically mark as seen
            if (chatIsOpen) {
                console.log(`👁️ Auto-marking file message ${messageUid} as seen`);
                
                // Notify sender that message was seen
                setTimeout(() => {
                    if (clients.has(senderId)) {
                        const sender = clients.get(senderId);
                        if (sender.ws.readyState === 1) {
                            try {
                                sender.ws.send(JSON.stringify({
                                    type: 'messages_seen',
                                    senderId: receiverId,
                                    messageUids: [messageUid],
                                    timestamp: new Date().toISOString(),
                                    autoSeen: true
                                }));
                                console.log(`✅ Auto-seen notification sent to sender ${senderId}`);
                            } catch (error) {
                                console.error(`❌ Failed to send auto-seen to ${senderId}:`, error);
                            }
                        }
                    }
                }, 100);
            }
        }
    } else if (receiverIsOnline) {
        console.log(`📱 Receiver ${receiverId} is online (recent activity) but not connected via WebSocket`);
        // User is considered online due to recent activity but doesn't have active WebSocket
        // The notification will be shown when they next load the page or reconnect
        
        // We can still update the chat list for when they reconnect
        updateChatList(receiverId, senderId);
    } else {
        console.log(`❌ Receiver ${receiverId} is offline, file message stored for later`);
    }
    
    // Update sender's chat list regardless of receiver's online status
    if (clients.has(senderId)) {
        const sender = clients.get(senderId);
        if (sender.ws.readyState === 1) {
            try {
                sender.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: receiverId,
                    timestamp: new Date().toISOString()
                }));
                console.log(`✅ Chat list updated for sender ${senderId}`);
            } catch (error) {
                console.error(`❌ Failed to update chat list for sender ${senderId}:`, error);
            }
        }
    }
}








// Notify about messages being seen (called by Laravel)
function notifyMessagesSeen(data) {
    const { senderId, receiverId, messageUids } = data;
    
    if (!receiverId || !messageUids) {
        console.warn('Invalid messages seen data');
        return;
    }

    console.log(`👀 Notifying ${receiverId} that messages were seen by ${senderId}`);

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
                console.error(`❌ Failed to send messages seen to ${receiverId}:`, error);
                clients.delete(receiverId);
            }
        }
    }
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
                console.error(`❌ Failed to send typing indicator to ${receiverId}:`, error);
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
                console.error(`❌ Failed to send stop typing to ${receiverId}:`, error);
            }
        }
    }
}

// Handle message seen (sent from client)
function handleMessageSeen(senderId, data) {
    const { receiverId, messageUids } = data;
    
    if (!receiverId) return;

    console.log(`👀 User ${senderId} marked messages as seen for ${receiverId}`);

    // Notify the sender that their messages were seen
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
            } catch (error) {
                console.error(`❌ Failed to send message seen to ${receiverId}:`, error);
            }
        }
    }
}

// Update chat list
function updateChatList(userId, newMessageFrom) {
    if (clients.has(userId)) {
        const user = clients.get(userId);
        if (user.ws.readyState === 1) {
            try {
                user.ws.send(JSON.stringify({
                    type: 'update_chat_list',
                    fromUserId: newMessageFrom,
                    timestamp: new Date().toISOString()
                }));
            } catch (error) {
                console.error(`❌ Failed to update chat list for ${userId}:`, error);
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
            console.error(`❌ Failed to send online status to ${requesterId}:`, error);
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
                console.error(`❌ Failed to broadcast status to ${clientId}:`, error);
            }
        }
    });
}

// Helper function to determine sender type based on ID pattern
function getSenderType(userId) {
    if (userId.startsWith('WS_') || userId.includes('wholesaler')) {
        return 'wholesaler';
    } else if (userId.startsWith('MN_') || userId.includes('manufacturer')) {
        return 'manufacturer';
    }
    return 'unknown';
}

// Function to get user info for notifications
async function getUserInfoForNotification(userId, userType) {
    try {
        let senderName = 'User';
        let profilePicture = null;
        
        // Based on your user ID patterns
        if (userType === 'wholesaler') {
            senderName = 'Wholesaler';
        } else if (userType === 'manufacturer') {
            senderName = 'Manufacturer';
        }
        
        // Generate avatar
        profilePicture = `https://ui-avatars.com/api/?name=${encodeURIComponent(senderName)}&background=3b82f6&color=fff`;
        
        return {
            senderName: senderName,
            profilePicture: profilePicture
        };
        
    } catch (error) {
        console.error('Error getting user info for notification:', error);
        return {
            senderName: 'User',
            profilePicture: 'https://ui-avatars.com/api/?name=User&background=3b82f6&color=fff'
        };
    }
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
                console.error(`❌ Error closing inactive connection for ${userId}:`, error);
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

// Debug endpoint to see active chats
app.get('/debug/active-chats', (req, res) => {
    const activeChatsObj = {};
    activeChats.forEach((chatSet, userId) => {
        activeChatsObj[userId] = Array.from(chatSet);
    });
    
    res.json({
        activeChats: activeChatsObj,
        connectedClients: Array.from(clients.keys()),
        timestamp: new Date().toISOString()
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

// Start server
server.listen(port, '0.0.0.0', () => {
    console.log(`🚀 WebSocket server running on ws://localhost:${port}`);
    console.log(`🌐 HTTP API available at http://localhost:${port}`);
    console.log(`❤️ Health check available at http://localhost:${port}/health`);
    console.log(`📊 Server status at http://localhost:${port}/status`);
});

// Handle uncaught exceptions
process.on('uncaughtException', (error) => {
    console.error('💥 Uncaught Exception:', error);
});

process.on('unhandledRejection', (reason, promise) => {
    console.error('💥 Unhandled Rejection at:', promise, 'reason:', reason);
});

// Graceful shutdown
process.on('SIGTERM', () => {
    console.log('🛑 Shutting down WebSocket server...');
    
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