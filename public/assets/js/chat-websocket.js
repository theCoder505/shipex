// WebSocket connection for chat page
let ws = null;
let reconnectInterval = null;
let typingTimeout = null;
let isTyping = false;

// Initialize WebSocket connection
function initWebSocket() {
    const userType = $('meta[name="user-type"]').attr('content');
    const wsUrl = `ws://localhost:3000?userId=${user_uid}&userType=${userType}`;

    // Use global WebSocket if available, otherwise create new one
    if (window.globalWebSocket && window.globalWebSocket.ws) {
        ws = window.globalWebSocket.ws;
        console.log('✅ Using existing global WebSocket connection');
        setupWebSocketHandlers();
        return;
    }

    ws = new WebSocket(wsUrl);

    ws.onopen = function () {
        console.log('💬 Chat WebSocket connected');
        clearInterval(reconnectInterval);

        if (sending_to) {
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
        console.log('💬 Chat WebSocket disconnected');
        showConnectionStatus('disconnected');

        reconnectInterval = setInterval(function () {
            console.log('Attempting to reconnect...');
            initWebSocket();
        }, 5000);
    };
}

function setupWebSocketHandlers() {
    if (!ws) return;

    // Override the global message handler for chat-specific messages
    const originalOnMessage = ws.onmessage;
    ws.onmessage = function(event) {
        try {
            const data = JSON.parse(event.data);
            
            // Handle chat-specific messages
            if (data.type === 'user_typing' || 
                data.type === 'messages_seen' || 
                data.type === 'online_status' ||
                data.type === 'user_status_changed' ||
                data.type === 'update_chat_list') {
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
    console.log('💬 Chat WebSocket message:', data.type);

    switch (data.type) {
        case 'connected':
            console.log('Connected to chat server:', data.message);
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

        case 'pong':
            // Heartbeat response
            break;

        default:
            console.log('Unknown message type:', data.type);
    }
}


// Handle new incoming message (sent from server after DB save)
function handleNewMessage(data) {
    // console.log('New message received:', data);
    
    if (data.senderId === sending_to) {
        // Message is from the currently active chat
        const isSeen = data.seen || false;
        
        if (data.messageType === 'text') {
            displayTextMessage(data.message, false, data.messageUid, isSeen);
        } else if (data.messageType === 'file') {
            displayFileMessage(data.fileData, false, data.messageText, data.messageUid, isSeen);
        }
        
        scrollToBottom();
        
        // If message is not already marked as seen and chat is open, mark it as seen
        if (!isSeen && data.chatIsOpen) {
            // console.log('Auto-marking incoming message as seen:', data.messageUid);
            markMessagesAsSeenInBackend([data.messageUid], data.senderId);
        }
    } else {
        // Message is from another chat - update unread badge
        updateUnreadBadge(data.senderId, 1);
    }
    
    updateChatListItem(data.senderId, data);
    playNotificationSound();
}

// Handle messages seen - update UI indicators
function handleMessagesSeen(data) {
    // console.log('Messages seen notification received:', data);
    
    // Update seen indicators for these messages
    if (data.messageUids && Array.isArray(data.messageUids)) {
        data.messageUids.forEach(uid => {
            const messageElement = $(`[data-message-uid="${uid}"]`);
            if (messageElement.length > 0) {
                const seenIndicator = messageElement.find('.seen-indicator');
                if (seenIndicator.length > 0) {
                    // Update to blue checkmark for seen
                    seenIndicator.html('<i class="fas fa-check-double text-blue-400 text-xs" title="Seen"></i>');
                    // console.log(`Updated seen indicator for message: ${uid}`);
                } else {
                    // console.log(`No seen indicator found for message: ${uid}`);
                }
            } else {
                // console.log(`Message element not found for UID: ${uid}`);
            }
        });
    }
}

// Mark messages as seen in backend
function markMessagesAsSeenInBackend(messageUids, senderId) {
    $.ajax({
        url: '/mark-messages-seen',
        type: 'POST',
        data: {
            'message_uids': messageUids,
            'sender_id': senderId,
            '_token': csrf_token
        },
        success: function(response) {
            // console.log('Messages marked as seen in database');
        },
        error: function(xhr, status, error) {
            console.error('Error marking messages as seen:', error);
        }
    });
}

// Notify chat opened
window.notifyChatOpened = function(withUserId) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'chat_opened',
            withUserId: withUserId
        }));
    }
}

// Notify chat closed
window.notifyChatClosed = function(withUserId) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'chat_closed',
            withUserId: withUserId
        }));
    }
}


// Chat-specific WebSocket handlers
function handleChatWebSocketMessage(data) {
    console.log('Chat WebSocket message:', data.type);

    switch (data.type) {
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

        // new_message is now handled globally in app.blade.php
    }
}

// Handle messages seen - update UI indicators
function handleMessagesSeen(data) {
    // console.log('Messages seen notification received:', data);
    if (data.messageUids && Array.isArray(data.messageUids)) {
        data.messageUids.forEach(uid => {
            const messageElement = $(`[data-message-uid="${uid}"]`);
            if (messageElement.length > 0) {
                const seenIndicator = messageElement.find('.time-container i.fa-check-double');
                if (seenIndicator.length > 0) {
                    seenIndicator.removeClass('text-gray-400').addClass('text-blue-400');
                    seenIndicator.attr('title', 'Seen');
                    // console.log(`Updated seen indicator for message: ${uid}`);
                } else {
                    // console.log(`No seen indicator found for message: ${uid}`);
                }
            } else {
                // console.log(`Message element not found for UID: ${uid}`);
            }
        });
    }
}
// Update or add chat list item
function updateChatListItem(userId, messageData) {
    const sendingToType = $('meta[name="user-type"]').attr('content') === 'wholesaler' ? 'manufacturer' : 'wholesaler';

    // Check if chat tab already exists
    let chatTab = $(`.chat_tab[data-userid="${userId}"]`);

    if (chatTab.length > 0) {
        // Update existing chat tab
        updateExistingChatTab(chatTab, messageData);

        // Move to top
        chatTab.prependTo('.chat_list');
    } else {
        // Create new chat tab
        createNewChatTab(userId, sendingToType, messageData);
    }
}

// Update existing chat tab
function updateExistingChatTab(chatTab, messageData) {
    const isSent = messageData.senderId === user_uid;

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

    // Update time
    chatTab.find('.text-xs.text-gray-500').text('Just now');

    // If message is from other user (not sent by current user), increment unread
    if (!isSent && messageData.senderId !== user_uid) {
        const badge = chatTab.find('.unread_badge');
        const currentCount = parseInt(badge.text()) || 0;
        badge.removeClass('hidden').text(currentCount + 1);
    }
}

// Create new chat tab
function createNewChatTab(userId, sendingToType, messageData) {
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
                                ${user.message_icon} ${user.last_message}
                            </p>
                        </div>
                        <span class="unread_badge ${user.unseen_count > 0 ? '' : 'hidden'}">${user.unseen_count}</span>
                    </div>
                `;

                $('.chat_list').prepend(chatTabHtml);
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
    moveToTopOfChatList(data.fromUserId);
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

// Send message seen notification
function sendMessageSeen(senderId, messageUids) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'message_seen',
            receiverId: senderId,
            messageUids: messageUids
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

// Notify chat opened
window.notifyChatOpened = function (withUserId) {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'chat_opened',
            withUserId: withUserId
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

function updateUnreadBadge(userId, increment) {
    const badge = $(`.chat_tab[data-userid="${userId}"] .unread_badge`);
    if (badge.length > 0) {
        const currentCount = parseInt(badge.text()) || 0;
        const newCount = currentCount + increment;

        if (newCount > 0) {
            badge.removeClass('hidden').text(newCount);
        }
    }
}

function refreshUnreadCount(userId) {
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

    // console.log(`Connection status: ${text}`);
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
}, 60000); // Update every minute

// Initialize WebSocket on page load
$(document).ready(function () {
    initWebSocket();
});