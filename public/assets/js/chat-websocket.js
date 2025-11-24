// WebSocket connection for chat page
let ws = null;
let reconnectInterval = null;
let typingTimeout = null;
let isTyping = false;

// Initialize WebSocket connection
function initWebSocket() {
    const userType = $('meta[name="user-type"]').attr('content');
    const wsUrl = `wss://shipex.co.kr/ws?userId=${user_uid}&userType=${userType}`;

    // Use global WebSocket if available, otherwise create new one
    if (window.globalWebSocket && window.globalWebSocket.ws) {
        ws = window.globalWebSocket.ws;
        // console.log('✅ Using existing global WebSocket connection');
        setupWebSocketHandlers();
        return;
    }

    ws = new WebSocket(wsUrl);

    ws.onopen = function () {
        // console.log('💬 Chat WebSocket connected');
        clearInterval(reconnectInterval);

        // Notify server about currently open chat if any
        if (sending_to) {
            notifyChatOpened(sending_to);
            requestOnlineStatus(sending_to);
        }

        showConnectionStatus('connected');
    };

    ws.onmessage = function (event) {
        try {
            const data = JSON.parse(event.data);
            handleWebSocketMessage(data);
        } catch (error) {
            console.error('Error parsing WebSocket message:', error);
        }
    };

    ws.onerror = function (error) {
        console.error('💬 Chat WebSocket error:', error);
        showConnectionStatus('error');
    };

    ws.onclose = function () {
        // console.log('💬 Chat WebSocket disconnected');
        showConnectionStatus('disconnected');

        reconnectInterval = setInterval(function () {
            // console.log('Attempting to reconnect...');
            initWebSocket();
        }, 5000);
    };
}

function setupWebSocketHandlers() {
    if (!ws) return;

    // Override the global message handler for chat-specific messages
    const originalOnMessage = ws.onmessage;
    ws.onmessage = function (event) {
        try {
            const data = JSON.parse(event.data);

            // Handle chat-specific messages
            if (data.type === 'user_typing' ||
                data.type === 'messages_seen' ||
                data.type === 'online_status' ||
                data.type === 'user_status_changed' ||
                data.type === 'update_chat_list' ||
                data.type === 'message_seen' ||
                data.type === 'all_messages_seen' ||
                data.type === 'user_chat_opened') {
                handleWebSocketMessage(data);
            }

            // Also call original handler if it exists
            if (originalOnMessage) {
                originalOnMessage.call(this, event);
            }
        } catch (error) {
            console.error('Error parsing WebSocket message:', error);
        }
    };
}

// Handle incoming WebSocket messages
function handleWebSocketMessage(data) {
    // console.log('💬 Chat WebSocket message:', data.type);

    switch (data.type) {
        case 'connected':
            // console.log('Connected to chat server:', data.message);
            break;

        case 'new_message':
            handleNewMessage(data);
            break;

        case 'user_typing':
            handleUserTyping(data);
            break;

        case 'messages_seen':
            handleMessagesSeen(data);
            break;

        case 'online_status':
            handleOnlineStatus(data);
            break;

        case 'user_status_changed':
            handleUserStatusChanged(data);
            break;

        case 'update_chat_list':
            handleChatListUpdate(data);
            break;

        case 'message_seen':
            handleSingleMessageSeen(data);
            break;

        case 'all_messages_seen':
            handleAllMessagesSeen(data);
            break;

        case 'user_chat_opened':
            handleUserChatOpened(data);
            break;

        case 'pong':
            // Heartbeat response
            break;

        default:
            // console.log('Unknown message type:', data.type);
    }
}

// Handle new incoming message (sent from server after DB save)
function handleNewMessage(data) {
    // console.log('New message received:', data);

    // Check if the message is from the currently active chat
    const isFromActiveChat = data.senderId === sending_to;
    // console.log('Is from active chat:', isFromActiveChat, 'senderId:', data.senderId, 'sending_to:', sending_to);

    // Remove "No chat history yet" message if it exists
    $('.no_chats').remove();

    if (isFromActiveChat) {
        // Message is from the currently active chat - display it
        // console.log('Message from active chat');
        const isSeen = data.seen || false;

        if (data.messageType === 'text') {
            displayTextMessage(data.message, false, data.messageUid, isSeen);
        } else if (data.messageType === 'file') {
            displayFileMessage(data.fileData, false, data.messageText, data.messageUid, isSeen);
        }

        scrollToBottom();

        // CRITICAL: Since we have the chat open with this sender, mark as seen immediately
        if (!isSeen) {
            // console.log('🔵 Marking incoming message as seen (chat is open):', data.messageUid);
            markMessagesAsSeenInBackend([data.messageUid], data.senderId);
            
            // Update UI immediately
            const messageElement = $(`[data-message-uid="${data.messageUid}"]`);
            if (messageElement.length > 0) {
                const seenIndicator = messageElement.find('.time i.fa-check-double');
                if (seenIndicator.length > 0) {
                    seenIndicator.removeClass('text-gray-400').addClass('text-blue-400');
                    seenIndicator.attr('title', 'Seen');
                }
            }
        }

        // Update chat list WITHOUT incrementing unread badge for active chat
        updateChatListItem(data.senderId, data, false);
    } else {
        // Message is from another chat - update unread badge or create new chat tab
        // console.log('Message from different chat, updating unread badge');
        
        // Check if chat tab exists for this user
        const chatTab = $(`.chat_tab[data-userid="${data.senderId}"]`);
        
        if (chatTab.length > 0) {
            // Update existing chat tab WITH unread badge increment
            updateChatListItem(data.senderId, data, true);
        } else {
            // Create new chat tab for this user with unread badge
            createNewChatTabFromMessage(data.senderId, data);
        }

        // Play notification sound for messages from other chats
        playNotificationSound();
    }
}

// Handle single message seen notification
function handleSingleMessageSeen(data) {
    // console.log('Single message seen:', data);
    const { messageUid, seenBy } = data;
    
    // Update the UI for this specific message
    const messageElement = $(`[data-message-uid="${messageUid}"]`);
    if (messageElement.length > 0) {
        const seenIndicator = messageElement.find('.time i.fa-check-double');
        if (seenIndicator.length > 0) {
            seenIndicator.removeClass('text-gray-400').addClass('text-blue-400');
            seenIndicator.attr('title', 'Seen');
        }
    }
}

// Handle all messages seen notification
function handleAllMessagesSeen(data) {
    // console.log('All messages seen by:', data.seenBy);
    
    // Update all sent messages to show as seen
    $('.sent_message').each(function() {
        const checkIcon = $(this).find('.time i.fa-check-double');
        checkIcon.addClass("text-blue-400").removeClass("text-gray-400");
        checkIcon.attr('title', 'Seen');
    });
}

// Handle user chat opened notification
function handleUserChatOpened(data) {
    // console.log('User opened chat with us:', data.userId);
    // You can use this to show that the other user is viewing the chat
    // Optional: Show a "User is viewing the chat" indicator
}

// Create new chat tab when message arrives from new user
function createNewChatTabFromMessage(userId, messageData) {
    const sendingToType = $('meta[name="user-type"]').attr('content') === 'wholesaler' ? 'manufacturer' : 'wholesaler';
    
    // Check if this is from the currently active chat
    const isFromActiveChat = userId === sending_to;
    
    // console.log('createNewChatTabFromMessage - userId:', userId, 'sending_to:', sending_to, 'isFromActiveChat:', isFromActiveChat);

    // Remove "No chat history yet" message if it exists
    $('.no_chats').remove();

    $.ajax({
        url: '/get-chat-list-item',
        type: 'POST',
        data: {
            'user_id': userId,
            'sending_to_type': sendingToType,
            'current_user_id': user_uid,
            '_token': csrf_token
        },
        success: function (response) {
            if (response.status === 'success') {
                const user = response.user;
                
                const isSent = messageData.senderId === user_uid;
                let lastMessageText = '';
                let messageIcon = '';

                if (messageData.messageType === 'text') {
                    lastMessageText = messageData.message.length > 40 ?
                        messageData.message.substring(0, 40) + '...' :
                        messageData.message;
                } else if (messageData.messageType === 'file') {
                    messageIcon = '<i class="fas fa-file-alt"></i>';
                    lastMessageText = isSent ? 'File sent' : 'File received';
                }

                // Only show unread badge if NOT from active chat AND message is from other user
                const unreadCount = (!isFromActiveChat && !isSent) ? 1 : 0;

                // console.log('Creating new chat tab from message with unreadCount:', unreadCount);

                const chatTabHtml = `
                    <div class="chat_tab inactive_chat_tab" data-userid="${user.id}" onclick="activateChat(this)">
                        <img src="${user.profile_picture}" alt="${user.name}"
                            class="w-12 h-12 rounded-full chat_user_img object-cover"
                            onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=3b82f6&color=fff'">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h3 class="font-semibold text-gray-900 truncate chat_user_name">
                                    ${user.name}
                                </h3>
                                <span class="text-xs text-gray-500">Just now</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">
                                ${messageIcon} ${lastMessageText}
                            </p>
                        </div>
                        <span class="unread_badge ${unreadCount > 0 ? '' : 'hidden'}">${unreadCount}</span>
                    </div>
                `;

                $('.chat_list').prepend(chatTabHtml);
                
                // If this is the first chat, remove the "No chat history yet" message
                if ($('.chat_list .chat_tab').length === 1) {
                    $('.chat_list .p-4.text-center').remove();
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching chat list item:', error);
        }
    });
}

// Handle messages seen - update ALL messages from this sender
function handleMessagesSeen(data) {
    // console.log('Messages seen notification received:', data);
    if (data.senderId === sending_to) {
        // Update all seen indicators to blue
        $('.sent_message').each(function() {
            const checkIcon = $(this).find('.time i.fa-check-double');
            checkIcon.addClass("text-blue-400").removeClass("text-gray-400");
            checkIcon.attr('title', 'Seen');
        });
    }
}

// Mark messages as seen in backend
function markMessagesAsSeenInBackend(messageUids, senderId) {
    // console.log('🔵 Marking messages as seen in backend:', messageUids);
    
    // Notify WebSocket server
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'mark_messages_seen',
            messageUids: messageUids,
            senderId: senderId
        }));
    }
    
    // Also call Laravel endpoint directly for redundancy
    $.ajax({
        url: '/mark-messages-seen',
        type: 'POST',
        data: {
            'message_uids': messageUids,
            'sender_id': senderId,
            '_token': csrf_token
        },
        success: function (response) {
            // console.log('✅ Messages marked as seen in database');
        },
        error: function (xhr, status, error) {
            console.error('❌ Error marking messages as seen:', error);
        }
    });
}

// Notify chat opened - CRITICAL: This tells server which chat we have open
window.notifyChatOpened = function (withUserId) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        // console.log('🔓 Notifying server: Chat opened with', withUserId);
        ws.send(JSON.stringify({
            type: 'chat_opened',
            withUserId: withUserId
        }));
    }
}

// Notify chat closed - CRITICAL: This tells server we closed the chat
window.notifyChatClosed = function (withUserId) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        // console.log('🔒 Notifying server: Chat closed with', withUserId);
        ws.send(JSON.stringify({
            type: 'chat_closed',
            withUserId: withUserId
        }));
    }
}

// Update existing chat tab with new message
function updateExistingChatTab(chatTab, messageData, shouldIncrementUnread = true) {
    const isSent = messageData.senderId === user_uid;
    const isFromActiveChat = chatTab.attr('data-userid') === sending_to;

    // console.log('updateExistingChatTab - shouldIncrementUnread:', shouldIncrementUnread, 'isFromActiveChat:', isFromActiveChat, 'isSent:', isSent);

    // Remove "No chat history yet" message if it exists
    $('.no_chats').remove();

    // Update last message
    let lastMessageText = '';
    let messageIcon = '';

    if (messageData.messageType === 'text') {
        lastMessageText = messageData.message.length > 40 ?
            messageData.message.substring(0, 40) + '...' :
            messageData.message;
    } else if (messageData.messageType === 'file') {
        messageIcon = '<i class="fas fa-file-alt"></i>';
        lastMessageText = isSent ? 'File sent' : 'File received';
    }

    const lastMessageHtml = messageIcon ?
        `${messageIcon} ${lastMessageText}` :
        lastMessageText;

    chatTab.find('.text-sm').html(lastMessageHtml);
    chatTab.find('.text-xs.text-gray-500').text('Just now');

    // CRITICAL: Only increment unread if NOT the active chat and NOT sent by current user
    if (shouldIncrementUnread && !isSent && !isFromActiveChat) {
        const badge = chatTab.find('.unread_badge');
        const currentCount = parseInt(badge.text()) || 0;
        const newCount = currentCount + 1;
        
        // console.log('Incrementing unread badge:', currentCount, '->', newCount);
        badge.removeClass('hidden').text(newCount);
    } else {
        // console.log('Skipping unread badge increment - Active chat or sent message');
    }

    // Move to top ONLY if new message (not when just opening chat)
    chatTab.prependTo('.chat_list');
}

// Update or add chat list item
function updateChatListItem(userId, messageData, shouldUpdateUnread = true) {
    const sendingToType = $('meta[name="user-type"]').attr('content') === 'wholesaler' ? 'manufacturer' : 'wholesaler';

    // Check if this is from the currently active chat
    const isFromActiveChat = userId === sending_to;
    // console.log('updateChatListItem - userId:', userId, 'sending_to:', sending_to, 'isFromActiveChat:', isFromActiveChat, 'shouldUpdateUnread:', shouldUpdateUnread);

    // Check if chat tab already exists
    let chatTab = $(`.chat_tab[data-userid="${userId}"]`);

    if (chatTab.length > 0) {
        // CRITICAL: Don't increment unread if it's the active chat
        const shouldIncrementUnread = shouldUpdateUnread && !isFromActiveChat;
        updateExistingChatTab(chatTab, messageData, shouldIncrementUnread);
    } else {
        // Create new chat tab - only add unread badge if not active chat
        const shouldShowUnread = shouldUpdateUnread && !isFromActiveChat;
        createNewChatTab(userId, sendingToType, messageData, shouldShowUnread);
    }
}



// Create new chat tab
function createNewChatTab(userId, sendingToType, messageData, shouldShowUnread = true) {
    const isFromActiveChat = userId === sending_to;
    
    // console.log('createNewChatTab - userId:', userId, 'sending_to:', sending_to, 'isFromActiveChat:', isFromActiveChat, 'shouldShowUnread:', shouldShowUnread);

    // Remove "No chat history yet" message if it exists
    $('.no_chats').remove();

    $.ajax({
        url: '/get-chat-list-item',
        type: 'POST',
        data: {
            'user_id': userId,
            'sending_to_type': sendingToType,
            'current_user_id': user_uid,
            '_token': csrf_token
        },
        success: function (response) {
            if (response.status === 'success') {
                const user = response.user;
                
                const isSent = messageData.senderId === user_uid;
                let lastMessageText = '';
                let messageIcon = '';

                if (messageData.messageType === 'text') {
                    lastMessageText = messageData.message.length > 40 ?
                        messageData.message.substring(0, 40) + '...' :
                        messageData.message;
                } else if (messageData.messageType === 'file') {
                    messageIcon = '<i class="fas fa-file-alt"></i>';
                    lastMessageText = isSent ? 'File sent' : 'File received';
                }

                // Only show unread badge if specified AND message is from other user AND not from active chat
                const unreadCount = (shouldShowUnread && !isSent && !isFromActiveChat) ? 1 : 0;

                // console.log('Creating new chat tab with unreadCount:', unreadCount);

                const chatTabHtml = `
                    <div class="chat_tab inactive_chat_tab" data-userid="${user.id}" onclick="activateChat(this)">
                        <img src="${user.profile_picture}" alt="${user.name}"
                            class="w-12 h-12 rounded-full chat_user_img object-cover"
                            onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=3b82f6&color=fff'">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h3 class="font-semibold text-gray-900 truncate chat_user_name">
                                    ${user.name}
                                </h3>
                                <span class="text-xs text-gray-500">Just now</span>
                            </div>
                            <p class="text-sm text-gray-600 truncate">
                                ${messageIcon} ${lastMessageText}
                            </p>
                        </div>
                        <span class="unread_badge ${unreadCount > 0 ? '' : 'hidden'}">${unreadCount}</span>
                    </div>
                `;

                $('.chat_list').prepend(chatTabHtml);
                
                if ($('.chat_list .chat_tab').length === 1) {
                    $('.chat_list .p-4.text-center').remove();
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching chat list item:', error);
        }
    });
}




// Handle typing indicator
function handleUserTyping(data) {
    if (data.senderId === sending_to) {
        if (data.isTyping) {
            showTypingIndicator();
        } else {
            hideTypingIndicator();
        }
    }
}

// Handle online status response
function handleOnlineStatus(data) {
    if (data.userId === sending_to) {
        updateUserOnlineStatus(data.isOnline);
    }
}

// Handle user status change
function handleUserStatusChanged(data) {
    updateChatListOnlineStatus(data.userId, data.isOnline);

    if (data.userId === sending_to) {
        updateUserOnlineStatus(data.isOnline);
    }
}

// Handle chat list update
function handleChatListUpdate(data) {
    refreshUnreadCount(data.fromUserId);
    
    // Move to top ONLY if it's not the currently active chat
    if (data.fromUserId !== sending_to) {
        moveToTopOfChatList(data.fromUserId);
    }
}

// Send message with chat update notification
window.sendMessageWithChatUpdate = function (messageData) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'chat_message_sent',
            receiverId: sending_to,
            messageData: messageData,
            timestamp: new Date().toISOString()
        }));
    }
}

// Send typing indicator
window.sendTypingIndicator = function () {
    if (ws && ws.readyState === WebSocket.OPEN && sending_to) {
        ws.send(JSON.stringify({
            type: 'typing',
            receiverId: sending_to
        }));
    }
}

// Send stop typing indicator
window.sendStopTypingIndicator = function () {
    if (ws && ws.readyState === WebSocket.OPEN && sending_to) {
        ws.send(JSON.stringify({
            type: 'stop_typing',
            receiverId: sending_to
        }));
    }
}

// Request online status
window.requestOnlineStatus = function (userId) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'get_online_status',
            checkUserId: userId
        }));
    }
}

// Send new text message via WebSocket
window.sendTextMessageViaWebSocket = function (receiverId, message, messageUid, timestamp) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'new_text_message',
            receiverId: receiverId,
            message: message,
            messageUid: messageUid,
            timestamp: timestamp
        }));
    }
}

// Send new file message via WebSocket
window.sendFileMessageViaWebSocket = function (receiverId, fileData, messageText, messageUid, timestamp) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'new_file_message',
            receiverId: receiverId,
            fileData: fileData,
            messageText: messageText,
            messageUid: messageUid,
            timestamp: timestamp
        }));
    }
}

// UI Helper Functions
function showTypingIndicator() {
    const typingHtml = `
        <div class="typing-indicator flex items-start gap-3">
            <div class="bg-gray-200 rounded-2xl rounded-tl-none px-4 py-2">
                <div class="flex gap-1">
                    <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0s"></span>
                    <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>
                </div>
            </div>
        </div>
    `;

    $('.typing-indicator').remove();
    $('#messagesContainer').append(typingHtml);
    scrollToBottom();
}

function hideTypingIndicator() {
    $('.typing-indicator').remove();
}

function updateUserOnlineStatus(isOnline) {
    const statusText = isOnline ? 'Online now' : 'Offline';
    $('.activity').html(statusText);

    if (isOnline) {
        $('.activity').removeClass('text-gray-500').addClass('text-green-600');
    } else {
        $('.activity').removeClass('text-green-600').addClass('text-gray-500');
    }
}

function updateChatListOnlineStatus(userId, isOnline) {
    const chatTab = $(`.chat_tab[data-userid="${userId}"]`);
    if (chatTab.length > 0) {
        if (isOnline) {
            chatTab.find('.chat_user_img').addClass('ring-2 ring-green-500');
        } else {
            chatTab.find('.chat_user_img').removeClass('ring-2 ring-green-500');
        }
    }
}

function refreshUnreadCount(userId) {
    // Skip if this is the currently active chat
    if (userId === sending_to) {
        // console.log('Skipping unread count refresh for active chat');
        return;
    }
    
    $.ajax({
        url: '/get-unread-count',
        type: 'POST',
        data: {
            'user_id': userId,
            '_token': csrf_token
        },
        success: function (response) {
            if (response.count > 0) {
                const badge = $(`.chat_tab[data-userid="${userId}"] .unread_badge`);
                badge.removeClass('hidden').text(response.count);
            }
        }
    });
}

function moveToTopOfChatList(userId) {
    const chatTab = $(`.chat_tab[data-userid="${userId}"]`);
    if (chatTab.length > 0) {
        chatTab.prependTo('.chat_list');
    }
}

function showConnectionStatus(status) {
    let color = 'gray';
    let text = 'Connecting...';

    if (status === 'connected') {
        color = 'green';
        text = 'Connected';
    } else if (status === 'error' || status === 'disconnected') {
        color = 'red';
        text = 'Disconnected';
    }
}

function playNotificationSound() {
    // Optional: Play notification sound
    // const audio = new Audio('/sounds/notification.mp3');
    // audio.play();
}

// Handle typing in message box
$('.message_box').on('input', function () {
    this.style.height = 'auto';
    const newHeight = Math.min(this.scrollHeight, 120);
    this.style.height = newHeight + 'px';

    if (!isTyping && sending_to) {
        isTyping = true;
        window.sendTypingIndicator();
    }

    clearTimeout(typingTimeout);

    typingTimeout = setTimeout(function () {
        isTyping = false;
        window.sendStopTypingIndicator();
    }, 1000);
});

// Update last active periodically
setInterval(function () {
    if (user_uid) {
        $.ajax({
            url: '/update-last-active',
            type: 'POST',
            data: {
                '_token': csrf_token
            },
            success: function (response) {
                // Last active updated
            }
        });
    }
}, 60000);

// Initialize WebSocket on page load
$(document).ready(function () {
    initWebSocket();
});

// Export functions for global access
window.markMessagesAsSeenInBackend = markMessagesAsSeenInBackend;
window.notifyChatOpened = notifyChatOpened;
window.notifyChatClosed = notifyChatClosed;
window.sendTypingIndicator = sendTypingIndicator;
window.sendStopTypingIndicator = sendStopTypingIndicator;
window.requestOnlineStatus = requestOnlineStatus;
window.sendTextMessageViaWebSocket = sendTextMessageViaWebSocket;
window.sendFileMessageViaWebSocket = sendFileMessageViaWebSocket;