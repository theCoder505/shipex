var csrf_token = $(".csrf_token").val();
var sending_to = $(".sending_to").val();
var currentFile = null;
var previousChatUserId = null;

$(document).ready(function () {
    $('#fileInput').on('change', function (e) {
        handleFileSelect(e);
    });

    $('.attachment-btn').on('click', function () {
        $('#fileInput').click();
    });

    function handleEnterKey(e) {
        if (e.which === 13) {
            if (window.innerWidth > 768) {
                if (e.shiftKey) {
                    return;
                } else {
                    e.preventDefault();
                    sendMessage($('.send-btn'));
                }
            }
        }
    }

    $('.message_box').on('keypress', handleEnterKey);
    $('.message_box').on('keydown', handleEnterKey);

    $('.message_box').on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf',
        'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];

    if (!allowedTypes.includes(file.type)) {
        toastr.error('Only images, PDF, DOC, and PPT files are allowed.');
        return;
    }

    if (file.size > 20 * 1024 * 1024) {
        toastr.error('File size must be less than 20MB.');
        return;
    }

    currentFile = file;
    showFilePreview(file);
}

function showFilePreview(file) {
    const preview = $('#filePreview');
    const previewContent = $('#filePreviewContent');

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewContent.html(`
                <img src="${e.target.result}" class="w-16 h-16 object-cover rounded" alt="Preview">
                <span class="text-sm">${file.name}</span>
            `);
        };
        reader.readAsDataURL(file);
    } else {
        const icon = getFileIcon(file.type);
        previewContent.html(`
            <div class="flex items-center gap-3">
                ${icon}
                <span class="text-sm">${file.name}</span>
            </div>
        `);
    }

    preview.removeClass('hidden');
}

function cancelFileUpload() {
    currentFile = null;
    $('#fileInput').val('');
    $('#filePreview').addClass('hidden');
    $('#filePreviewContent').html('');
}

function uploadFile(messageText = '') {
    if (!currentFile || !sending_to) return;

    const formData = new FormData();
    formData.append('file', currentFile);
    formData.append('sending_to', sending_to);
    formData.append('message_text', messageText);
    formData.append('_token', csrf_token);

    const tempMessageId = 'temp_' + Date.now();
    displayFileMessageWithLoader(currentFile, true, messageText, tempMessageId);

    updateChatListOnSend({
        messageType: 'file',
        message: messageText || 'File sent',
        timestamp: new Date().toISOString()
    });

    $.ajax({
        url: '/send-file-message',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 'success') {
                $(`#${tempMessageId}`).remove();
                displayFileMessage(response.file_data, true, response.message_text, response.message_uid, false);

                if (window.ws && window.ws.readyState === WebSocket.OPEN) {
                    window.ws.send(JSON.stringify({
                        type: 'update_chat_list',
                        fromUserId: sending_to,
                        timestamp: new Date().toISOString()
                    }));
                }

                cancelFileUpload();
                scrollToBottom();
            }
        },
        error: function (xhr, status, error) {
            console.error('Error uploading file:', error);
            $(`#${tempMessageId}`).remove();
            toastr.error('Error uploading file. Please try again.');
        }
    });
}

function displayFileMessageWithLoader(file, isSent, messageText, tempId) {
    const messageClass = isSent ? 'sent_message' : 'received_message';
    const alignClass = isSent ? 'items-end' : 'items-start';
    const bgClass = isSent ? 'bg-blue-400' : 'bg-purple-400';

    const loaderHtml = `
        <div class="${messageClass}" id="${tempId}">
            <div class="flex flex-col gap-0 ${alignClass}">
                <div class="file-message min-w-[50px] max-w-[200px] lg:min-w-[120px] lg:max-w-[300px]">
                    <div class="flex items-center gap-3 p-3 ${bgClass} text-white rounded-2xl">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-white"></div>
                        <span class="text-sm">Uploading...</span>
                    </div>
                </div>
            </div>
        </div>`;

    $("#messagesContainer").append(loaderHtml);
    $(".empty_chat_area").remove();
    scrollToBottom();
}

function updateChatListOnSend(messageData) {
    const sendingToType = $('meta[name="user-type"]').attr('content') === 'wholesaler' ? 'manufacturer' : 'wholesaler';
    let chatTab = $(`.chat_tab[data-userid="${sending_to}"]`);
    
    $('.no_chats').remove();
    
    if (chatTab.length > 0) {
        updateSentChatTab(chatTab, messageData);
        chatTab.prependTo('.chat_list');
    } else {
        createNewChatTabOnSend(sending_to, sendingToType, messageData);
    }
}

function updateSentChatTab(chatTab, messageData) {
    let lastMessageText = '';
    let messageIcon = '';
    if (messageData.messageType === 'text') {
        lastMessageText = messageData.message.length > 40 ?
            messageData.message.substring(0, 40) + '...' :
            messageData.message;
    } else if (messageData.messageType === 'file') {
        messageIcon = '<i class="fas fa-file-alt"></i>';
        lastMessageText = 'File sent';
    }
    const lastMessageHtml = messageIcon ?
        `${messageIcon} ${lastMessageText}` :
        lastMessageText;
    chatTab.find('.text-sm').html(lastMessageHtml);
    chatTab.find('.text-xs.text-gray-500').text('Just now');

    chatTab.find('.unread_badge').addClass('hidden').text('0');
}

function createNewChatTabOnSend(userId, sendingToType, messageData) {
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
                let lastMessageText = '';
                let messageIcon = '';

                if (messageData.messageType === 'text') {
                    lastMessageText = messageData.message.length > 40 ?
                        messageData.message.substring(0, 40) + '...' :
                        messageData.message;
                } else if (messageData.messageType === 'file') {
                    messageIcon = '<i class="fas fa-file-alt"></i>';
                    lastMessageText = 'File sent';
                }

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
                        <span class="unread_badge hidden">0</span>
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

function sendMessage(passedThis) {
    let message_box = ($(".message_box").val()).trim();

    if (currentFile) {
        uploadFile(message_box);
        $(".message_box").val('');
        resetTextareaHeight();
        return;
    }

    if (message_box !== '' && message_box !== null && sending_to) {
        const tempMessageUid = 'temp_' + Date.now();
        displayTextMessage(message_box, true, tempMessageUid, false);
        $(".message_box").val('');
        resetTextareaHeight();
        scrollToBottom();
        updateChatListOnSend({
            messageType: 'text',
            message: message_box,
            timestamp: new Date().toISOString()
        });

        if (window.sendStopTypingIndicator) {
            window.sendStopTypingIndicator();
        }

        $.ajax({
            url: '/send-text-message',
            type: 'POST',
            data: {
                'sending_to': sending_to,
                'message_box': message_box,
                '_token': csrf_token
            },
            success: function (response) {
                $(`[data-message-uid="${tempMessageUid}"]`).attr('data-message-uid', response.message_uid);
            },
            error: function (xhr, status, error) {
                console.error('Error sending message:', error);
                toastr.error('Failed to send message. Please try again.');
            }
        });
    }
}

function resetTextareaHeight() {
    const messageBox = $('.message_box');
    messageBox.css('height', 'auto');
    messageBox.css('height', '40px');
}

function scrollToBottom() {
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        requestAnimationFrame(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            setTimeout(() => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 100);
        });
    }
}

function displayTextMessage(message, isSent, messageUid = null, seenStatus = false) {
    const d = new Date();
    const dateTimeString = `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear().toString().slice(-2)}, ${d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}`;

    const messageClass = isSent ? 'sent_message' : 'received_message';
    const alignClass = isSent ? 'items-end' : 'items-start';
    const bgClass = isSent ? 'bg-blue-500 text-white rounded-2xl rounded-tr-none' : 'bg-[#843ffe] text-white rounded-2xl rounded-tl-none';

    const seenIndicator = isSent ? `
        <i class="fas fa-check-double ${seenStatus ? 'text-blue-400' : 'text-gray-400'} text-xs ml-1" title="${seenStatus ? 'Seen' : 'Sent'}"></i>
    ` : '';

    const new_message = `
        <div class="${messageClass}" data-message-uid="${messageUid || ''}">
            <div class="flex flex-col gap-1 ${alignClass} cursor-pointer" onclick="toggleTime(this)">
                <div class="${bgClass} px-4 py-2 max-w-md break-words">
                    <p style="white-space: pre-wrap;" class="text-xs lg:text-sm">${escapeHtml(message)}</p>
                </div>
                <span class="text-xs text-gray-500 ${isSent ? 'mr-2' : 'ml-2'} hidden time flex items-center gap-1">
                    ${dateTimeString}
                    ${seenIndicator}
                </span>
            </div>
        </div>`;

    $("#messagesContainer").append(new_message);
    $(".empty_chat_area").remove();
}

function displayFileMessage(fileData, isSent, messageText = '', messageUid = null, seenStatus = false) {
    const d = new Date();
    const dateTimeString = `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear().toString().slice(-2)}, ${d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}`;

    const messageClass = isSent ? 'sent_message' : 'received_message';
    const alignClass = isSent ? 'items-end' : 'items-start';
    const bgClass = isSent ? 'bg-blue-500 text-white' : 'bg-[#843ffe] text-white';

    const seenIndicator = isSent ? `
        <i class="fas fa-check-double ${seenStatus ? 'text-blue-400' : 'text-gray-400'} text-xs ml-1" title="${seenStatus ? 'Seen' : 'Sent'}"></i>
    ` : '';

    let fileContent = '';
    if (fileData.file_type.startsWith('image/')) {
        const imageRoundClass = messageText
            ? 'rounded-t-2xl'
            : (isSent ? 'rounded-2xl rounded-tr-none' : 'rounded-2xl rounded-tl-none');

        fileContent = `
            <img src="${fileData.file_url}" alt="Shared image" 
                 class="w-full h-auto ${imageRoundClass} cursor-pointer file-preview"
                 onclick="openFileModal('${fileData.file_url}', 'image')">
        `;
    } else {
        const fileRoundClass = messageText
            ? 'rounded-t-2xl'
            : (isSent ? 'rounded-2xl rounded-tr-none' : 'rounded-2xl rounded-tl-none');

        const icon = getFileIcon(fileData.file_type);
        fileContent = `
            <div class="flex items-center gap-0 p-3 ${bgClass} ${fileRoundClass}">
                <div class="flex-shrink-0">
                    ${icon}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium truncate">${fileData.original_name}</p>
                    <a href="${fileData.file_url}" target="_blank" 
                       class="text-xs underline hover:no-underline inline-block mt-1">
                        Open file
                    </a>
                </div>
            </div>
        `;
    }

    const textContent = messageText ? `
        <div class="${bgClass} px-4 py-2 ${isSent ? 'rounded-b-2xl rounded-tr-none' : 'rounded-b-2xl rounded-tl-none'} max-w-xs break-words">
            <p style="white-space: pre-wrap;" class="text-xs lg:text-sm">${escapeHtml(messageText)}</p>
        </div>
    ` : '';

    const new_message = `
        <div class="${messageClass}" data-message-uid="${messageUid || ''}">
            <div class="flex flex-col gap-0 ${alignClass} cursor-pointer" onclick="toggleTime(this)">
                <div class="file-message min-w-[50px] max-w-[200px] lg:min-w-[120px] lg:max-w-[300px]">
                    ${fileContent}
                    ${textContent}
                </div>
                <span class="text-xs text-gray-500 ${isSent ? 'mr-2' : 'ml-2'} hidden time flex items-center gap-1">
                    ${dateTimeString}
                    ${seenIndicator}
                </span>
            </div>
        </div>`;

    $("#messagesContainer").append(new_message);
    $(".empty_chat_area").remove();
}

function getFileIcon(fileType) {
    if (fileType.includes('pdf')) {
        return `<svg class="w-10 h-10 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18.5,9H13V3.5L18.5,9M6,20V4H12V10H18V20H6Z"/>
                </svg>`;
    }

    if (fileType.includes('word') || fileType.includes('document')) {
        return `<svg class="w-10 h-10 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M7,11H9L10,16L11,11H13L14,16L15,11H17L15,19H13L12,14L11,19H9L7,11Z"/>
                </svg>`;
    }

    if (fileType.includes('powerpoint') || fileType.includes('presentation')) {
        return `<svg class="w-10 h-10 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M10,11V13H13V11H10M10,14V16H11V15H12V16H13V14H10Z"/>
                </svg>`;
    }

    return `<svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M10,19L12,15H9V10H15V15L13,19H10Z"/>
            </svg>`;
}

function openFileModal(url, type) {
    const modal = $('#fileModal');
    const modalContent = $('#modalContent');

    if (type === 'image') {
        modalContent.html(`<img src="${url}" class="w-full h-auto" alt="Preview">`);
    }

    modal.removeClass('hidden');
}

function closeFileModal() {
    $('#fileModal').addClass('hidden');
}

function toggleTime(passedThis) {
    let timeElement = $(passedThis).find('.time');
    timeElement.toggleClass('hidden');
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function showChatList() {
    if (sending_to && window.notifyChatClosed) {
        window.notifyChatClosed(sending_to);
    }

    $(".chat_area").addClass('hidden');
    $(".chat_list").removeClass("hidden");
    $(".search_input_box").removeClass("hidden");
    $(".chat_header").removeClass("hidden");

    sending_to = "";
    $(".sending_to").val("");
}

var emptyChat = `<div class="empty_chat_area text-center py-8">
                    <svg class="w-32 h-32 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-lg font-medium">No messages yet</p>
                    <p class="text-sm">Start a conversation by sending a message</p>
                </div>`;

function activateChat(passedThis) {
    if (previousChatUserId && window.notifyChatClosed) {
        window.notifyChatClosed(previousChatUserId);
    }

    $('.chat_tab').removeClass('active_chat_tab');
    $(passedThis).addClass('active_chat_tab');

    let activated_user_img = $(passedThis).find('.chat_user_img').attr('src');
    let activated_user_name = $(passedThis).find('.chat_user_name').html();
    sending_to = $(passedThis).attr("data-userid");

    $(".chat_area").removeClass('hidden');
    $(".chat_list").addClass("hidden");
    $(".search_input_box").addClass("hidden");
    $(".chat_header").addClass("hidden");

    $(".activated_user_img").attr('src', activated_user_img);
    $(".activated_user_name").html(activated_user_name);
    $(".activity").html('Active now');
    $(".sending_to").val(sending_to);
    $("#messagesContainer").html('');

    $(passedThis).find(".unread_badge").addClass('hidden').text('0');

    if (window.notifyChatOpened) {
        window.notifyChatOpened(sending_to);
    }

    previousChatUserId = sending_to;

    if (window.requestOnlineStatus) {
        window.requestOnlineStatus(sending_to);
    }

    $.ajax({
        url: '/fetch-chats',
        type: 'POST',
        data: {
            'sending_to': sending_to,
            '_token': csrf_token
        },
        success: function (response) {
            $("#messagesContainer").html('');
            $("#messagesContainer").addClass('opacity-0');

            if (response.messages.length == 0) {
                $("#messagesContainer").html(emptyChat);
                return;
            } else {
                response.messages.forEach(message => {
                    const isSent = message.sent_by == user_uid;
                    const seenStatus = message.seen == 1;

                    if (message.message_type == 'text') {
                        displayTextMessage(message.main_message, isSent, message.message_uid, seenStatus);
                    } else if (message.message_type == 'file') {
                        const messageData = JSON.parse(message.main_message);
                        displayFileMessage(messageData.file_data, isSent, messageData.message_text || '', message.message_uid, seenStatus);
                    }
                });
            }

            setTimeout(() => {
                scrollToBottom();
                $("#messagesContainer").removeClass('opacity-0');

                const allUnseenMessages = response.messages.filter(msg =>
                    msg.sent_by === sending_to && msg.seen === 0
                );

                if (allUnseenMessages.length > 0) {
                    const allUnseenUids = allUnseenMessages.map(msg => msg.message_uid);
                    markMessagesAsSeenInBackend(allUnseenUids, sending_to);

                    allUnseenUids.forEach(uid => {
                        const messageElement = $(`[data-message-uid="${uid}"]`);
                        if (messageElement.length > 0) {
                            const seenIndicator = messageElement.find('.time i.fa-check-double');
                            if (seenIndicator.length > 0) {
                                seenIndicator.removeClass('text-gray-400').addClass('text-blue-400');
                                seenIndicator.attr('title', 'Seen');
                            }
                        }
                    });
                }

                setTimeout(() => {
                    scrollToBottom();
                }, 200);
            }, 10);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching messages:', error);
        }
    });
}

function markMessagesAsSeenInBackend(messageUids, senderId) {
    if (!messageUids || messageUids.length === 0) return;

    $.ajax({
        url: '/mark-messages-seen',
        type: 'POST',
        data: {
            'message_uids': messageUids,
            'sender_id': senderId,
            '_token': csrf_token
        },
        success: function (response) {
            // console.log('Messages marked as seen in database');
        },
        error: function (xhr, status, error) {
            console.error('Error marking messages as seen:', error);
        }
    });
}