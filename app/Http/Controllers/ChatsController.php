<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use GuzzleHttp\Client;
use Exception;

class ChatsController extends Controller
{
    private function notifyWebSocketServer($data)
    {
        try {
            $client = new Client([
                'timeout' => 2.0,
                'connect_timeout' => 1.0
            ]);

            $client->post('http://localhost:3000/api/notify', [
                'json' => $data,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            Log::info('WebSocket notification sent', ['type' => $data['type']]);
        } catch (Exception $e) {
            Log::error('Failed to notify WebSocket server: ' . $e->getMessage());
        }
    }

    // Check if both users have chat open with each other
    private function areBothUsersChatting($user1, $user2)
    {
        // This would typically check your active_chats tracking in WebSocket server
        // For now, we'll implement a simple version that checks recent activity
        // You might want to implement a more sophisticated method
        
        try {
            $client = new Client([
                'timeout' => 2.0,
                'connect_timeout' => 1.0
            ]);

            $response = $client->get('http://localhost:3000/debug/active-chats');
            $data = json_decode($response->getBody(), true);
            
            $user1Chats = $data['activeChats'][$user1] ?? [];
            $user2Chats = $data['activeChats'][$user2] ?? [];
            
            return in_array($user2, $user1Chats) && in_array($user1, $user2Chats);
        } catch (Exception $e) {
            Log::error('Failed to check active chats: ' . $e->getMessage());
            return false;
        }
    }

    public function chatRecords()
    {
        if (Auth::guard('wholesaler')->check()) {
            $sending_to_type = 'manufacturer';
            $chat_with = Manufacturer::all();
            $user_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
            $user_uid_type = 'manufacturer_uid';
            $chat_page_route = '/wholesaler/chats';
        } else {
            $sending_to_type = 'wholesaler';
            $chat_with = Wholesaler::all();
            $user_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
            $user_uid_type = 'wholesaler_uid';
            $chat_page_route = '/manufacturer/chats';
        }

        $spec_manufacturer = '';
        $online_status = 'online';
        return view('chats.messaging', compact('chat_with', 'sending_to_type', 'user_uid', 'user_uid_type', 'spec_manufacturer', 'online_status', 'chat_page_route'));
    }

    public function chatWithSpecManufacturer($manufacturer_uid)
    {
        if (Auth::guard('wholesaler')->check()) {
            $sending_to_type = 'manufacturer';
            $chat_with = Manufacturer::all();
            $user_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
            $last_active_time = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->value('last_active_time');
            $user_uid_type = 'manufacturer_uid';
            $chat_page_route = '/wholesaler/chats';
        } else {
            $sending_to_type = 'wholesaler';
            $chat_with = Wholesaler::all();
            $user_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
            $last_active_time = Wholesaler::where('wholesaler_uid', $manufacturer_uid)->value('last_active_time');
            $user_uid_type = 'wholesaler_uid';
            $chat_page_route = '/manufacturer/chats';
        }

        $spec_manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        if (!$spec_manufacturer) {
            return redirect()->route('wholesaler.chats')->with('error', 'Manufacturer not found');
        }

        $is_online = false;
        if ($last_active_time) {
            try {
                $is_online = \Carbon\Carbon::now()->diffInMinutes(\Carbon\Carbon::parse($last_active_time)) <= 15;
            } catch (\Exception $e) {
                $is_online = false;
            }
        }

        $online_status = $is_online ? 'online' : 'offline';

        return view('chats.messaging', compact('chat_with', 'sending_to_type', 'user_uid', 'user_uid_type', 'spec_manufacturer', 'online_status', 'chat_page_route'));
    }

    public function fetchChats(Request $request)
    {
        try {
            $receiver_id = $request['sending_to'];
            if (Auth::guard('wholesaler')->check()) {
                $sender_id = Auth::guard('wholesaler')->user()->wholesaler_uid;
                $last_active_time = Wholesaler::where('wholesaler_uid', $sender_id)->value('last_active_time');
            } else {
                $sender_id = Auth::guard('manufacturer')->user()->manufacturer_uid;
                $last_active_time = Manufacturer::where('manufacturer_uid', $sender_id)->value('last_active_time');
            }

            $is_online = false;
            if ($last_active_time) {
                try {
                    $is_online = \Carbon\Carbon::now()->diffInMinutes(\Carbon\Carbon::parse($last_active_time)) <= 15;
                } catch (\Exception $e) {
                    $is_online = false;
                }
            }

            $online_status = $is_online ? 'online' : 'offline';

            $messages = Chat::where(function ($query) use ($sender_id, $receiver_id) {
                $query->where('sent_by', $sender_id)
                    ->where('sent_to', $receiver_id);
            })->orWhere(function ($query) use ($sender_id, $receiver_id) {
                $query->where('sent_by', $receiver_id)
                    ->where('sent_to', $sender_id);
            })->orderBy('created_at', 'asc')->get();

            // Check if both users are actively chatting
            $bothUsersChatting = $this->areBothUsersChatting($sender_id, $receiver_id);
            
            // Get ALL unseen message IDs from this sender
            $allUnseenMessageIds = Chat::where('sent_to', $sender_id)
                ->where('sent_by', $receiver_id)
                ->where('seen', 0)
                ->pluck('message_uid')
                ->toArray();

            // Mark ALL messages from this sender as seen in database if both are chatting
            if (!empty($allUnseenMessageIds) && $bothUsersChatting) {
                Chat::where('sent_to', $sender_id)
                    ->where('sent_by', $receiver_id)
                    ->where('seen', 0)
                    ->update(['seen' => 1]);

                // Notify WebSocket about ALL messages being seen
                $this->notifyWebSocketServer([
                    'type' => 'messages_marked_seen',
                    'senderId' => $sender_id,
                    'receiverId' => $receiver_id,
                    'messageUids' => $allUnseenMessageIds
                ]);
            }

            return response()->json([
                'messages' => $messages, 
                'online_status' => $online_status,
                'both_users_chatting' => $bothUsersChatting
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching chats: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch messages'
            ], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        $sending_to = $request['sending_to'];
        $message_box = $request['message_box'];

        if (Auth::guard('wholesaler')->check()) {
            $sent_by = Auth::guard('wholesaler')->user()->wholesaler_uid;
        } else {
            $sent_by = Auth::guard('manufacturer')->user()->manufacturer_uid;
        }

        $message_uid = uniqid('msg_');

        // Check if both users are actively chatting
        $bothUsersChatting = $this->areBothUsersChatting($sent_by, $sending_to);
        
        // Determine seen status - if both are chatting, mark as seen immediately
        $seenStatus = $bothUsersChatting ? 1 : 0;

        // Save to database
        $chat = Chat::create([
            'message_uid' => $message_uid,
            'sent_by' => $sent_by,
            'sent_to' => $sending_to,
            'seen' => $seenStatus, // Set based on chat status
            'message_type' => 'text',
            'main_message' => $message_box,
        ]);

        // CRITICAL: Notify WebSocket server after saving to database
        if ($chat) {
            $this->notifyWebSocketServer([
                'type' => 'new_text_message',
                'senderId' => $sent_by,
                'receiverId' => $sending_to,
                'message' => $message_box,
                'messageUid' => $message_uid,
                'timestamp' => $chat->created_at->toISOString(),
                'seen' => $seenStatus, // Pass the seen status
                'bothUsersChatting' => $bothUsersChatting
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message_uid' => $message_uid,
            'timestamp' => $chat->created_at->toISOString(),
            'seen' => $seenStatus
        ], 200);
    }

    public function sendFileMessage(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,ppt,pptx|max:20480',
        ]);

        $sending_to = $request['sending_to'];
        $message_text = $request['message_text'] ?? '';

        if (Auth::guard('wholesaler')->check()) {
            $sent_by = Auth::guard('wholesaler')->user()->wholesaler_uid;
            $user_folder = 'wholesaler_' . $sent_by;
        } else {
            $sent_by = Auth::guard('manufacturer')->user()->manufacturer_uid;
            $user_folder = 'manufacturer_' . $sent_by;
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $fileType = $file->getMimeType();

        $directory = "chat_documents/{$user_folder}";
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = "{$directory}/{$filename}";

        if (str_starts_with($fileType, 'image/')) {
            try {
                $image = Image::read($file->getPathname());
                $image->scaleDown(width: 1200);
                $encodedImage = $image->toJpeg(40);
                Storage::disk('public')->put($filePath, $encodedImage);
            } catch (\Exception $e) {
                Log::error('Image compression failed: ' . $e->getMessage());
                Storage::disk('public')->put($filePath, file_get_contents($file));
            }
        } else {
            Storage::disk('public')->put($filePath, file_get_contents($file));
        }

        $fileData = [
            'file_url' => asset("storage/{$filePath}"),
            'file_path' => $filePath,
            'original_name' => $originalName,
            'file_type' => $fileType,
            'file_size' => Storage::disk('public')->size($filePath),
        ];

        $messageData = [
            'file_data' => $fileData,
            'message_text' => $message_text
        ];

        $message_uid = uniqid('file_');

        // Check if both users are actively chatting
        $bothUsersChatting = $this->areBothUsersChatting($sent_by, $sending_to);
        
        // Determine seen status - if both are chatting, mark as seen immediately
        $seenStatus = $bothUsersChatting ? 1 : 0;

        // Save to database
        $chat = Chat::create([
            'message_uid' => $message_uid,
            'sent_by' => $sent_by,
            'sent_to' => $sending_to,
            'seen' => $seenStatus, // Set based on chat status
            'message_type' => 'file',
            'main_message' => json_encode($messageData),
        ]);

        // CRITICAL: Notify WebSocket server after saving to database
        if ($chat) {
            $this->notifyWebSocketServer([
                'type' => 'new_file_message',
                'senderId' => $sent_by,
                'receiverId' => $sending_to,
                'fileData' => $fileData,
                'messageText' => $message_text,
                'messageUid' => $message_uid,
                'timestamp' => $chat->created_at->toISOString(),
                'seen' => $seenStatus, // Pass the seen status
                'bothUsersChatting' => $bothUsersChatting
            ]);
        }

        return response()->json([
            'status' => 'success',
            'file_data' => $fileData,
            'message_text' => $message_text,
            'message_uid' => $message_uid,
            'timestamp' => $chat->created_at->toISOString(),
            'seen' => $seenStatus
        ], 200);
    }

    public function markMessagesAsSeen(Request $request)
    {
        $messageUids = $request->input('message_uids', []);
        $senderId = $request->input('sender_id');

        if (Auth::guard('wholesaler')->check()) {
            $currentUser = Auth::guard('wholesaler')->user()->wholesaler_uid;
        } else {
            $currentUser = Auth::guard('manufacturer')->user()->manufacturer_uid;
        }

        // If specific message UIDs are provided, mark only those as seen
        if (!empty($messageUids)) {
            Chat::whereIn('message_uid', $messageUids)
                ->where('sent_to', $currentUser)
                ->where('sent_by', $senderId)
                ->where('seen', 0)
                ->update(['seen' => 1]);
        }
        // If no specific UIDs, mark ALL unseen messages from this sender as seen
        else {
            $allUnseenMessageIds = Chat::where('sent_to', $currentUser)
                ->where('sent_by', $senderId)
                ->where('seen', 0)
                ->pluck('message_uid')
                ->toArray();

            Chat::where('sent_to', $currentUser)
                ->where('sent_by', $senderId)
                ->where('seen', 0)
                ->update(['seen' => 1]);

            $messageUids = $allUnseenMessageIds;
        }

        // CRITICAL: Notify WebSocket about messages being seen
        if (!empty($messageUids)) {
            $this->notifyWebSocketServer([
                'type' => 'messages_marked_seen',
                'senderId' => $currentUser,
                'receiverId' => $senderId,
                'messageUids' => $messageUids
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function markAllUnseenAsSeen(Request $request)
    {
        $senderId = $request->input('sender_id');

        if (Auth::guard('wholesaler')->check()) {
            $currentUser = Auth::guard('wholesaler')->user()->wholesaler_uid;
        } else {
            $currentUser = Auth::guard('manufacturer')->user()->manufacturer_uid;
        }

        // Get all unseen message IDs from this sender
        $allUnseenMessageIds = Chat::where('sent_to', $currentUser)
            ->where('sent_by', $senderId)
            ->where('seen', 0)
            ->pluck('message_uid')
            ->toArray();

        // Mark ALL messages from this sender as seen in database
        if (!empty($allUnseenMessageIds)) {
            Chat::where('sent_to', $currentUser)
                ->where('sent_by', $senderId)
                ->where('seen', 0)
                ->update(['seen' => 1]);

            // Notify WebSocket about ALL messages being seen
            $this->notifyWebSocketServer([
                'type' => 'messages_marked_seen',
                'senderId' => $currentUser,
                'receiverId' => $senderId,
                'messageUids' => $allUnseenMessageIds
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message_uids' => $allUnseenMessageIds
        ]);
    }

    public function getUserChatInfo(Request $request)
    {
        $userId = $request->input('user_id');
        $sendingToType = $request->input('sending_to_type');

        try {
            if ($sendingToType === 'manufacturer') {
                $user = Manufacturer::where('manufacturer_uid', $userId)->first();
                if ($user) {
                    return response()->json([
                        'status' => 'success',
                        'user' => [
                            'id' => $user->manufacturer_uid,
                            'name' => $user->company_name_en,
                            'profile_picture' => $user->company_logo ? asset($user->company_logo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->company_name_en) . '&background=3b82f6&color=fff'
                        ]
                    ]);
                }
            } else {
                $user = Wholesaler::where('wholesaler_uid', $userId)->first();
                if ($user) {
                    return response()->json([
                        'status' => 'success',
                        'user' => [
                            'id' => $user->wholesaler_uid,
                            'name' => $user->company_name,
                            'profile_picture' => $user->profile_picture ? asset($user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->company_name) . '&background=3b82f6&color=fff'
                        ]
                    ]);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching user info'
            ], 500);
        }
    }

    public function getUnreadCount(Request $request)
    {
        $userId = $request->input('user_id');

        if (Auth::guard('wholesaler')->check()) {
            $currentUser = Auth::guard('wholesaler')->user()->wholesaler_uid;
        } else {
            $currentUser = Auth::guard('manufacturer')->user()->manufacturer_uid;
        }

        $count = Chat::where('sent_to', $currentUser)
            ->where('sent_by', $userId)
            ->where('seen', 0)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function updateLastActive(Request $request)
    {
        try {
            if (Auth::guard('wholesaler')->check()) {
                $userId = Auth::guard('wholesaler')->user()->wholesaler_uid;
                Wholesaler::where('wholesaler_uid', $userId)
                    ->update(['last_active_time' => now()]);
            } else {
                $userId = Auth::guard('manufacturer')->user()->manufacturer_uid;
                Manufacturer::where('manufacturer_uid', $userId)
                    ->update(['last_active_time' => now()]);
            }

            // Notify WebSocket server about activity
            $this->notifyWebSocketServer([
                'type' => 'user_activity',
                'userId' => $userId,
                'timestamp' => now()->toISOString()
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Error updating last active: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update last active time'
            ], 500);
        }
    }

    public function getChatListItem(Request $request)
    {
        $userId = $request->input('user_id');
        $sendingToType = $request->input('sending_to_type');
        $currentUserId = $request->input('current_user_id');

        try {
            if ($sendingToType === 'manufacturer') {
                $user = Manufacturer::where('manufacturer_uid', $userId)->first();
                if ($user) {
                    // Get last message
                    $lastMessage = Chat::where(function ($query) use ($currentUserId, $userId) {
                        $query->where(function ($q) use ($currentUserId, $userId) {
                            $q->where('sent_by', $currentUserId)->where('sent_to', $userId);
                        })->orWhere(function ($q) use ($currentUserId, $userId) {
                            $q->where('sent_by', $userId)->where('sent_to', $currentUserId);
                        });
                    })->latest()->first();

                    // Get unseen count
                    $unseenCount = Chat::where('sent_to', $currentUserId)
                        ->where('sent_by', $userId)
                        ->where('seen', 0)
                        ->count();

                    // Format last message time - FIXED: Use proper timestamp comparison
                    $lastMessageTime = '';
                    if ($lastMessage) {
                        $now = \Carbon\Carbon::now();
                        $messageTime = \Carbon\Carbon::parse($lastMessage->created_at);
                        
                        if ($messageTime->diffInSeconds($now) < 60) {
                            $lastMessageTime = $messageTime->diffInSeconds($now) . 's';
                        } elseif ($messageTime->diffInMinutes($now) < 60) {
                            $lastMessageTime = $messageTime->diffInMinutes($now) . 'm';
                        } elseif ($messageTime->diffInHours($now) < 24) {
                            $lastMessageTime = $messageTime->diffInHours($now) . 'h';
                        } elseif ($messageTime->diffInDays($now) < 7) {
                            $lastMessageTime = $messageTime->diffInDays($now) . 'd';
                        } else {
                            $lastMessageTime = $messageTime->format('M j');
                        }
                    }

                    if ($lastMessage) {
                        if ($lastMessage->message_type === 'text') {
                            $lastMessageText = \Illuminate\Support\Str::limit($lastMessage->main_message, 40);
                            $messageIcon = '';
                        } else {
                            $isSent = $lastMessage->sent_by === $currentUserId;
                            $messageIcon = '<i class="fas fa-file-alt"></i>';
                            $lastMessageText = $isSent ? 'File sent' : 'File received';
                        }
                    } else {
                        $lastMessageText = 'No messages yet';
                        $messageIcon = '';
                    }

                    return response()->json([
                        'status' => 'success',
                        'user' => [
                            'id' => $user->manufacturer_uid,
                            'name' => $user->company_name_en,
                            'profile_picture' => $user->company_logo ? asset($user->company_logo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->company_name_en) . '&background=3b82f6&color=fff',
                            'last_message' => $lastMessageText,
                            'message_icon' => $messageIcon,
                            'last_message_time' => $lastMessageTime,
                            'unseen_count' => $unseenCount
                        ]
                    ]);
                }
            } else {
                $user = Wholesaler::where('wholesaler_uid', $userId)->first();
                if ($user) {
                    // Same logic for wholesaler
                    $lastMessage = Chat::where(function ($query) use ($currentUserId, $userId) {
                        $query->where(function ($q) use ($currentUserId, $userId) {
                            $q->where('sent_by', $currentUserId)->where('sent_to', $userId);
                        })->orWhere(function ($q) use ($currentUserId, $userId) {
                            $q->where('sent_by', $userId)->where('sent_to', $currentUserId);
                        });
                    })->latest()->first();

                    $unseenCount = Chat::where('sent_to', $currentUserId)
                        ->where('sent_by', $userId)
                        ->where('seen', 0)
                        ->count();

                    // Format last message time - FIXED: Use proper timestamp comparison
                    $lastMessageTime = '';
                    if ($lastMessage) {
                        $now = \Carbon\Carbon::now();
                        $messageTime = \Carbon\Carbon::parse($lastMessage->created_at);
                        
                        if ($messageTime->diffInSeconds($now) < 60) {
                            $lastMessageTime = $messageTime->diffInSeconds($now) . 's';
                        } elseif ($messageTime->diffInMinutes($now) < 60) {
                            $lastMessageTime = $messageTime->diffInMinutes($now) . 'm';
                        } elseif ($messageTime->diffInHours($now) < 24) {
                            $lastMessageTime = $messageTime->diffInHours($now) . 'h';
                        } elseif ($messageTime->diffInDays($now) < 7) {
                            $lastMessageTime = $messageTime->diffInDays($now) . 'd';
                        } else {
                            $lastMessageTime = $messageTime->format('M j');
                        }
                    }

                    if ($lastMessage) {
                        if ($lastMessage->message_type === 'text') {
                            $lastMessageText = \Illuminate\Support\Str::limit($lastMessage->main_message, 40);
                            $messageIcon = '';
                        } else {
                            $isSent = $lastMessage->sent_by === $currentUserId;
                            $messageIcon = '<i class="fas fa-file-alt"></i>';
                            $lastMessageText = $isSent ? 'File sent' : 'File received';
                        }
                    } else {
                        $lastMessageText = 'No messages yet';
                        $messageIcon = '';
                    }

                    return response()->json([
                        'status' => 'success',
                        'user' => [
                            'id' => $user->wholesaler_uid,
                            'name' => $user->company_name,
                            'profile_picture' => $user->profile_picture ? asset($user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->company_name) . '&background=3b82f6&color=fff',
                            'last_message' => $lastMessageText,
                            'message_icon' => $messageIcon,
                            'last_message_time' => $lastMessageTime,
                            'unseen_count' => $unseenCount
                        ]
                    ]);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching user info'
            ], 500);
        }
    }
}