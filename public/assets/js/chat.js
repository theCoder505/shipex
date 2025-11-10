
function showChatPopup() {
    document.getElementById('chatPopupModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    $(".profile_dropdown").removeClass("show_drop");
}

function closeChatPopupModal() {
    document.getElementById('chatPopupModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    closeAskConversation();
    $(".messaging_with").removeClass("hidden");
    $(".all_chats").addClass("hidden");
    let emptyMsg = `<div class="rounded-lg p-4 bg-[#f6f6f6] mx-auto text-gray-500 w-full no_msg">
                        Type something to get started
                    </div>`;
    $("#messagesContainer").html(emptyMsg);
}





function addMessage() {
    const messageBox = document.querySelector('.msg_box');
    const messageText = messageBox.value.trim();
    
    if (messageText === '') return;
    
    const messagesContainer = document.getElementById('messagesContainer');
    const initialMessage = messagesContainer.querySelector('.rounded-lg.p-4.bg-\\[\\#f6f6f6\\].max-w-xs');
    
    if (initialMessage) {
        initialMessage.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'right_msg';
    messageDiv.textContent = messageText;
    messagesContainer.appendChild(messageDiv);
    messageBox.value = '';
    
    const scrollableContainer = document.querySelector('.msg_arena');
    if (scrollableContainer) {
        scrollableContainer.scrollTop = scrollableContainer.scrollHeight;
    }

    $(".no_msg").addClass('hidden');
}

function openChatPopupModal() {
    document.getElementById('chatPopupModal').classList.remove('hidden');
}



function showDropDown(){
    $(".profile_dropdown").toggleClass("show_drop");
}


function showChatDropDown(){
    $(".lang_dropdown").toggleClass("show_drop");
}




function stopConversation() {
    document.getElementById('stopconversation').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAskConversation() {
    document.getElementById('stopconversation').style.display = 'none';
    document.body.style.overflow = 'auto';
}



function backToAllchats(){
    $(".messaging_with").addClass("hidden");
    $(".all_chats").removeClass("hidden");
}

