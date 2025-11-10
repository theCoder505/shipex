<div id="stopconversation" class="modal-overlay">
    <div class="modal-conten create_modal">
        <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeAskConversation()">
        <img src="/assets/images/log-out.png" alt="User" class="w-24 h-24 rounded-lg block mx-auto">
        <div class="popup_text text-xl lg:text-[40px] my-6 text-center">
            Are you sure you want to stop the conversation?
        </div>

        <p class="pb-8 text-[#46484D] text-center">
            The manufacturer won’t be able to reach you out but you can still access your past conversations inside your profile and continue later.
        </p>

        <div class="links grid lg:flex justify-center items-center gap-4 lg:gap-8">
            <button class="text_primary text-center px-4 py-2 font-semibold" onclick="closeAskConversation()">Cancel</button>
            <button class="text-gray-200 hover:text-gray-50 rounded-lg px-4 py-3 bg_primary cursor-pointer" onclick="closeChatPopupModal()">Stop conversation</button>
        </div>
    </div>
</div>
