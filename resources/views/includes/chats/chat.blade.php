<div id="chatPopupModal"
    class="fixed inset-0 bg-[#00000075] items-center justify-center lg:items-end lg:justify-end lg:pr-10 lg:pb-4 z-50 hidden">
    <div class="messaging_with w-full lg:w-[500px]">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl h-[600px] flex flex-col m-4">
            <div class="flex items-center justify-between px-6 py-4 bg-[#F6F6F6]">
                <div class="flex items-center gap-3">
                    <button onclick="backToAllchats()" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <span class="text-gray-600 font-medium">ALL CHATS</span>
                </div>
                <div class="flex items-center gap-2 relative">
                    <button
                        class="text-gray-600 hover:text-gray-800 h-6 w-6 rounded-full hover:bg-gray-200 items-center flex justify-center"
                        onclick="showDropDown()">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <button onclick="closeChatPopupModal()"
                        class="text-gray-600 hover:text-gray-800 h-6 w-6 rounded-full hover:bg-gray-200 items-center flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>

                    <div class="profile_dropdown">
                        <a href="/manufacturers/{{ $manufacturer_name }}/MenuFacturer_777418" class="drop_text">See
                            Profile</a>
                        <hr>
                        <div class="drop_text" onclick="stopConversation()">Stop conversation</div>
                    </div>
                </div>
            </div>

            <!-- Chat Title -->
            <div class="px-6 pb-4 bg-[#F6F6F6]">
                <h2 class="text-2xl text-gray-900">Manufacturer 1</h2>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 px-6 py-4 overflow-y-auto msg_arena">
                <div id="messagesContainer" class="flex flex-col gap-4">
                    <!-- Initial message -->
                    <div class="rounded-lg p-4 bg-[#f6f6f6] mx-auto text-gray-500 w-full no_msg">
                        Type something to get started
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="px-6 py-4 border-t border-gray-200 bg-white">
                <div class="flex items-center gap-3">
                    <input type="text" placeholder="Type your message..."
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent msg_box" />
                    <button class="text-gray-400 hover:text-gray-600 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg" onclick="addMessage()">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="all_chats w-full lg:w-[500px] hidden">
        <div class="bg-white rounded-lg shadow-xl w-full flex flex-col m-4">
            <div class="flex items-center justify-between px-6 py-4 bg-[#F6F6F6]">
                <div class="">
                    <span class="text-2xl text-gray-600 font-medium">Chat</span>
                    <div
                        class="bg-gradient-to-r from-[#003FB4] to-[#85014C] bg-clip-text text-transparent text-sm py-2">
                        Automatic Translation Powered by AI
                    </div>
                </div>
                <div class="flex items-center gap-2 relative">
                    <button
                        class="text-gray-600 hover:text-gray-800 h-6 w-6 rounded-full hover:bg-gray-200 items-center flex justify-center"
                        onclick="showChatDropDown()">
                        <i class="fa fa-ellipsis-h"></i>
                    </button>
                    <button onclick="closeChatPopupModal()"
                        class="text-gray-600 hover:text-gray-800 h-6 w-6 rounded-full hover:bg-gray-200 items-center flex justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </button>

                    <div class="lang_dropdown">
                        <a class="drop_text" href="/wholesaler/settings">Edit Language</a>
                    </div>
                </div>
            </div>

            <div class="flex-1 p-6">
                <div class="col-span-3 p-8 rounded-lg bg-[#F6F6F6] mx-4 lg:mx-auto empty_results text-center">
                    <img src="/assets/images/empty_box.png" alt="" class="w-32 rounded-lg mx-auto">
                    <h3 class="text-[40px] my-4">No chat yet</h3>
                    <p class="text-[16px] text-gray-500 mb-2">
                        Go to manufacturers’ profiles to see their products and chat with them
                    </p>
                    <a href="/" class="text-[16px] text-[#003FB4] hover:underline">
                        Check out manufacturers
                    </a>
                </div>
            </div>



        </div>
    </div>
</div>



@include('includes.chats.sure_to_stop')
