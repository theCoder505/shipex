@extends('layouts.chats.app')

@section('title', 'Your Chat Records')

@section('styles')
    <style>
        .file-message {
            max-width: 300px;
        }

        .file-preview {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .file-preview:hover {
            transform: scale(1.05);
        }

        .file-icon {
            width: 48px;
            height: 48px;
        }

        .back_arrow {
            display: none;
        }

        @media screen and (max-width: 800px) {
            .back_arrow {
                display: block !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="h-screen flex flex-col">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900">
                    <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
                    <span class="text-gray-500">/</span>
                    <span>Chats</span>
                </h1>
            </div>
            <p class="text-sm text-gray-600">Your chat history with {{ $sending_to_type }}s</p>
        </div>

        <!-- Chat Container -->
        <div class="grid lg:flex-1 lg:flex">
            <!-- Left Sidebar - Chat List -->
            <div class="w-full lg:w-80 bg-white border-r border-gray-200 flex flex-col">
                <!-- Search Bar -->
                <div class="p-4 border-b border-gray-200 lg:block search_input_box">
                    <input type="text" placeholder="Search chats..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onkeyup="searchChat(this)">
                </div>

                <!-- Chat List -->
                <div class="lg:flex-1 lg:block overflow-y-auto chat_list">
                    @php
                        // Get all unique user IDs that have chat history with the current user
                        $chatPartners = \App\Models\Chat::where(function ($query) use ($user_uid) {
                            $query->where('sent_by', $user_uid)->orWhere('sent_to', $user_uid);
                        })
                            ->get()
                            ->map(function ($chat) use ($user_uid) {
                                // Return the other user's ID (the chat partner)
                                return $chat->sent_by === $user_uid ? $chat->sent_to : $chat->sent_by;
                            })
                            ->unique()
                            ->values();

                        // Filter users to only those with chat history
                        $usersWithChats = $chat_with->filter(function ($user) use ($chatPartners, $user_uid_type) {
                            return $chatPartners->contains($user->{$user_uid_type});
                        });
                    @endphp

                    @forelse ($usersWithChats as $user)
                        @php
                            $userId = $user->{$user_uid_type};

                            // Get unseen message count (messages sent TO current user that are unseen)
                            $unseenCount = \App\Models\Chat::where('sent_to', $user_uid)
                                ->where('sent_by', $userId)
                                ->where('seen', 0)
                                ->count();

                            // Get last message
                            $lastMessage = \App\Models\Chat::where(function ($query) use ($user_uid, $userId) {
                                $query
                                    ->where(function ($q) use ($user_uid, $userId) {
                                        $q->where('sent_by', $user_uid)->where('sent_to', $userId);
                                    })
                                    ->orWhere(function ($q) use ($user_uid, $userId) {
                                        $q->where('sent_by', $userId)->where('sent_to', $user_uid);
                                    });
                            })
                                ->latest()
                                ->first();

                            // Format last message time
                            $lastMessageTime = $lastMessage
                                ? $lastMessage->created_at->diffForHumans(null, true, true)
                                : '';

                            // Format last message text based on message type
                            if ($lastMessage) {
                                if ($lastMessage->message_type === 'text') {
                                    $lastMessageText = \Illuminate\Support\Str::limit($lastMessage->main_message, 40);
                                    $messageIcon = '';
                                } else {
                                    // Determine if it was sent or received
                                    $isSent = $lastMessage->sent_by === $user_uid;

                                    // Get appropriate icon and text based on message type
                                    switch ($lastMessage->message_type) {
                                        case 'image':
                                            $messageIcon = '<i class="fas fa-image"></i>';
                                            $lastMessageText = $isSent ? 'Photo sent' : 'Photo received';
                                            break;
                                        case 'file':
                                        case 'document':
                                            $messageIcon = '<i class="fas fa-file-alt"></i>';
                                            $lastMessageText = $isSent ? 'File sent' : 'File received';
                                            break;
                                        case 'video':
                                            $messageIcon = '<i class="fas fa-video"></i>';
                                            $lastMessageText = $isSent ? 'Video sent' : 'Video received';
                                            break;
                                        case 'audio':
                                            $messageIcon = '<i class="fas fa-microphone"></i>';
                                            $lastMessageText = $isSent ? 'Audio sent' : 'Audio received';
                                            break;
                                        default:
                                            $messageIcon = '<i class="fas fa-paperclip"></i>';
                                            $lastMessageText = $isSent ? 'File sent' : 'File received';
                                    }
                                }
                            } else {
                                $lastMessageText = 'No messages yet';
                                $messageIcon = '';
                            }

                            // Determine profile picture
                            if ($sending_to_type === 'manufacturer') {
                                // Current user is wholesaler, showing manufacturers
                                $profilePicture = $user->company_logo
                                    ? asset($user->company_logo)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode($user->name ?? ($user->company_name_en ?? 'User')) .
                                        '&background=3b82f6&color=fff';
                                $userName = $user->company_name_en;
                            } else {
                                // Current user is manufacturer, showing wholesalers
                                $profilePicture = $user->profile_picture
                                    ? asset($user->profile_picture)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode($user->company_name ?? 'User') .
                                        '&background=3b82f6&color=fff';
                                $userName = $user->company_name ?? 'User';
                            }
                        @endphp

                        <div class="chat_tab inactive_chat_tab" data-userid="{{ $userId }}"
                            onclick="activateChat(this)">
                            <img src="{{ $profilePicture }}" alt="{{ $userName }}"
                                class="w-12 h-12 rounded-full chat_user_img object-cover"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=3b82f6&color=fff'">
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline">
                                    <h3 class="font-semibold text-gray-900 truncate chat_user_name">
                                        {{ $userName }}
                                    </h3>
                                    @if ($lastMessageTime)
                                        <span class="text-xs text-gray-500">{{ $lastMessageTime }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 truncate">
                                    @if ($messageIcon)
                                        {!! $messageIcon !!}
                                    @endif
                                    {{ $lastMessageText }}
                                </p>
                            </div>
                            @if ($unseenCount > 0)
                                <span class="unread_badge">{{ $unseenCount }}</span>
                            @else
                                <span class="unread_badge hidden">0</span>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            <p>No chat history yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side - Chat Area -->
            <div class="hidden lg:flex-1 lg:flex lg:flex-col bg-gray-50 relative overflow-y-auto chat_area">
                <!-- Chat Header -->
                @if ($spec_manufacturer == '')
                    <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-4">
                        <svg class="w-12 h-12 text-gray-600 cursor-pointer absolute left-[-0.5rem] pr-2 back_arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" onclick="showChatList(this)">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                        <img src="https://ui-avatars.com/api/?name=Select+User&background=3b82f6&color=fff"
                            alt="Select User" class="w-10 h-10 rounded-full activated_user_img">
                        <div class="flex-1">
                            <h2 class="font-semibold text-gray-900 activated_user_name">Select a chat</h2>
                            <p class="text-sm text-gray-500 activity">Select a user to start chatting</p>
                        </div>
                    </div>
                @else
                    <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center gap-4">
                        <svg class="w-12 h-12 text-gray-600 cursor-pointer absolute left-[-0.5rem] pr-2 back_arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" onclick="showChatList(this)">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                        <img src="{{ $spec_manufacturer->company_logo ? asset($spec_manufacturer->company_logo) : 'https://ui-avatars.com/api/?name=' . urlencode($spec_manufacturer->company_name_en) . '&background=3b82f6&color=fff' }}"
                            alt="Select User" class="w-10 h-10 rounded-full activated_user_img">
                        <div class="flex-1">
                            <h2 class="font-semibold text-gray-900 activated_user_name">
                                {{ $spec_manufacturer->company_name_en }}</h2>
                            <p class="text-sm text-gray-500 activity">Active Now</p>
                        </div>
                    </div>
                @endif

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto px-6 py-4 space-y-2" id="messagesContainer">
                    <div class="empty_chat_area text-center py-8">
                        <svg class="w-32 h-32 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-lg font-medium">No chat selected</p>
                        <p class="text-sm">Select a user from the left to view messages</p>
                    </div>
                </div>

                <!-- Message Input Area -->
                <div class="bg-white border-t border-gray-200 px-6 py-4">
                    <div class="flex items-end gap-3">
                        <input type="hidden" name="_token" class="csrf_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="sending_to" class="sending_to" value="">

                        <!-- Attachment Options -->
                        <div class="relative">
                            <button
                                class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100 attachment-btn">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </button>
                            <input type="file" id="fileInput" class="hidden"
                                accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx">
                        </div>

                        <!-- Message Input -->
                        <div class="flex-1 bg-gray-100 rounded-2xl px-4 py-2 flex items-center">
                            <textarea placeholder="Type a message..."
                                class="flex-1 bg-transparent border-none focus:outline-none text-gray-900 placeholder-gray-500 message_box"
                                rows="1"></textarea>
                            <button class="text-gray-500 hover:text-gray-700" onclick="insertEmoji(this)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Send Button -->
                        <button class="bg-blue-400 hover:bg-blue-500 text-white p-3 rounded-full transition-colors send-btn"
                            onclick="sendMessage(this)">
                            <svg class="w-5 h-5 transform rotate-90" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18l9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" class="mt-3 hidden">
                        <div class="flex items-center gap-3 bg-blue-50 p-3 rounded-lg">
                            <div id="filePreviewContent"></div>
                            <button onclick="cancelFileUpload()" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Modal -->
    <div id="fileModal" class="fixed inset-0 bg-[#00000050] flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg max-w-4xl max-h-4/5 overflow-auto">
            <div class="flex justify-between items-center relative">
                <button onclick="closeFileModal()"
                    class="text-gray-500 hover:text-gray-700 absolute top-4 right-4 p-2 bg-white rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="" id="modalContent"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var user_uid = "{{ $user_uid }}";
        var spec_manufacturer_uid = "{{ $spec_manufacturer ? $spec_manufacturer->manufacturer_uid : '' }}";

        function searchChat(input) {
            let searchTerm = $(input).val().toLowerCase().trim();

            if (searchTerm === '' || searchTerm === null) {
                $('.chat_tab').removeClass('hidden');
            } else {
                $('.chat_tab').addClass('hidden');
                $('.chat_tab').each(function() {
                    let userName = $(this).find('.chat_user_name').text().toLowerCase();
                    if (userName.includes(searchTerm)) {
                        $(this).removeClass('hidden');
                    }
                });
            }
        }

        $(document).ready(function() {
            if (spec_manufacturer_uid) {
                let chatTab = $(`.chat_tab[data-userid="${spec_manufacturer_uid}"]`);
                if (chatTab.length > 0) {
                    activateChat(chatTab[0]);
                } else {
                    sending_to = spec_manufacturer_uid;
                    $(".sending_to").val(spec_manufacturer_uid);
                    @if ($spec_manufacturer)
                        $(".activated_user_img").attr('src',
                            "{{ $spec_manufacturer->company_logo ? asset($spec_manufacturer->company_logo) : 'https://ui-avatars.com/api/?name=' . urlencode($spec_manufacturer->company_name_en) . '&background=3b82f6&color=fff' }}"
                            );
                        $(".activated_user_name").html("{{ $spec_manufacturer->company_name_en }}");
                        $(".activity").html('Active now');
                    @endif
                    $("#messagesContainer").html(emptyChat);
                }
            }
        });
    </script>
@endsection
