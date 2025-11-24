<!doctype html>
<html lang="en" class="main_html">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="no-referrer">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="icon" href="{{ $website_icon }}" type="image/x-icon">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <link rel="stylesheet" href="/assets/css/styles.css">

    @if (Auth::guard('wholesaler')->check())
        <meta name="user-id" content="{{ Auth::guard('wholesaler')->user()->wholesaler_uid }}">
        <meta name="user-type" content="wholesaler">
        <meta name="user-name" content="{{ Auth::guard('wholesaler')->user()->company_name }}">
    @elseif(Auth::guard('manufacturer')->check())
        <meta name="user-id" content="{{ Auth::guard('manufacturer')->user()->manufacturer_uid }}">
        <meta name="user-type" content="manufacturer">
        <meta name="user-name" content="{{ Auth::guard('manufacturer')->user()->company_name_en }}">
    @else
        <meta name="user-id" content="">
        <meta name="user-type" content="">
        <meta name="user-name" content="">
    @endif

    @yield('style')
    <title>{{ $brandname }} — @yield('title')</title>
</head>

<body class="bg-white dark:bg-gray-950">
    @include('layouts.surface.header')

    @yield('content')

    @include('layouts.surface.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="/assets/js/index.js"></script>


    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,es,fr,de,it,pt,ru,zh-CN,ja,ko,ar,hi,vi,th,tr,nl,pl',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'gLang');

            setTimeout(function() {
                const googleBanner = document.querySelector('.goog-te-banner-frame');
                if (googleBanner) {
                    googleBanner.style.display = 'none';
                }

                const body = document.querySelector('body');
                if (body) {
                    body.style.top = '0px';
                }
            }, 100);
        }
    </script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>

    <script>
        // SweetAlert2 notifications
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ Session::get('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ Session::get('error') }}",
                timer: 4000,
                showConfirmButton: true
            });
        @endif

        @if (Session::has('info'))
            Swal.fire({
                icon: 'info',
                title: 'Information',
                text: "{{ Session::get('info') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (Session::has('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Warning!',
                text: "{{ Session::get('warning') }}",
                timer: 4000,
                showConfirmButton: true
            });
        @endif

        // Handle language change events
        document.addEventListener('DOMContentLoaded', function() {
            const restoreBodyPosition = function() {
                const body = document.querySelector('body');
                if (body) {
                    body.style.top = '0px';
                }

                const googleBanner = document.querySelector('.goog-te-banner-frame');
                if (googleBanner) {
                    googleBanner.style.display = 'none';
                }
            };

            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        restoreBodyPosition();
                    }
                });
            });

            const body = document.querySelector('body');
            if (body) {
                observer.observe(body, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }

            restoreBodyPosition();
        });

        // Configure toastr globally
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    </script>

    @yield('scripts')



    <script>
        // Global WebSocket connection for notifications (app.blade.php)
        (function() {
            // Only initialize if user is authenticated
            const userId = document.querySelector('meta[name="user-id"]')?.content;
            const userType = document.querySelector('meta[name="user-type"]')?.content;

            if (!userId || !userType) {
                console.log('No user authenticated, skipping WebSocket initialization');
                return;
            }

            let ws = null;
            let reconnectInterval = null;
            let isReconnecting = false;
            let notificationPermissionRequested = false;
            let pendingNotifications = [];

            // Initialize WebSocket connection
            function initGlobalWebSocket() {
                if (isReconnecting) return;

                const wsUrl = `wss://shipex.co.kr/ws?userId=${userId}&userType=${userType}`;

                try {
                    ws = new WebSocket(wsUrl);

                    ws.onopen = function() {
                        console.log('🔔 Notification WebSocket connected');
                        isReconnecting = false;
                        clearInterval(reconnectInterval);

                        // Request initial unread count
                        requestTotalUnreadCount();
                    };

                    ws.onmessage = function(event) {
                        try {
                            const data = JSON.parse(event.data);
                            handleNotificationMessage(data);
                        } catch (error) {
                            console.error('Error parsing notification message:', error);
                        }
                    };

                    ws.onerror = function(error) {
                        console.error('🔔 Notification WebSocket error:', error);
                    };

                    ws.onclose = function() {
                        console.log('🔔 Notification WebSocket disconnected');

                        // Attempt to reconnect after 5 seconds
                        if (!isReconnecting) {
                            isReconnecting = true;
                            reconnectInterval = setInterval(function() {
                                console.log('Attempting to reconnect notification WebSocket...');
                                initGlobalWebSocket();
                            }, 5000);
                        }
                    };

                    // Store WebSocket in window for access from other scripts
                    window.globalWebSocket = {
                        ws: ws,
                        userId: userId,
                        userType: userType
                    };

                } catch (error) {
                    console.error('Failed to create WebSocket connection:', error);
                }
            }

            // Handle incoming notification messages
            function handleNotificationMessage(data) {
                console.log('🔔 Notification received:', data.type);

                switch (data.type) {
                    case 'connected':
                        console.log('Connected to notification server');
                        break;

                    case 'new_message':
                        handleNewMessageNotification(data);
                        break;

                    case 'total_unread_count':
                        updateNotificationBadge(data.count);
                        break;

                    case 'messages_seen':
                        // Refresh unread count when messages are marked as seen
                        requestTotalUnreadCount();
                        break;

                    case 'pong':
                        // Heartbeat response
                        break;

                    default:
                        // Ignore other message types
                        break;
                }
            }

            // Handle new message notification
            function handleNewMessageNotification(data) {
                const {
                    senderId,
                    receiverId,
                    messageType,
                    message
                } = data;

                // Only increment if the message is FOR this user (not FROM this user)
                if (receiverId === userId && senderId !== userId) {
                    console.log('📨 New message received from:', senderId);

                    // Increment the badge
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        const currentCount = parseInt(badge.textContent) || 0;
                        const newCount = currentCount + 1;
                        badge.textContent = newCount;

                        // Show badge if it was hidden
                        if (badge.classList.contains('hidden')) {
                            badge.classList.remove('hidden');
                        }

                        // Add animation effect
                        badge.style.animation = 'none';
                        setTimeout(() => {
                            badge.style.animation = 'pulse 0.5s ease-in-out';
                        }, 10);
                    }

                    // Play notification sound (optional)
                    playNotificationSound();

                    // Queue browser notification for user interaction
                    queueBrowserNotification(data);
                }
            }

            // Queue browser notification to be shown on next user interaction
            function queueBrowserNotification(data) {
                pendingNotifications.push(data);
                
                // If we haven't requested permission yet, do it on next user click
                if (!notificationPermissionRequested && Notification.permission === "default") {
                    setupNotificationPermissionRequest();
                } else if (Notification.permission === "granted") {
                    // If permission is already granted, show notification immediately
                    createNotification(data);
                }
                // If permission is denied, do nothing
            }

            // Setup notification permission request on user interaction
            function setupNotificationPermissionRequest() {
                const requestPermission = function() {
                    if (!notificationPermissionRequested && Notification.permission === "default") {
                        notificationPermissionRequested = true;
                        
                        Notification.requestPermission().then(function(permission) {
                            if (permission === "granted") {
                                console.log('Notification permission granted');
                                // Show any pending notifications
                                pendingNotifications.forEach(createNotification);
                                pendingNotifications = [];
                            }
                        });
                    }
                    
                    // Remove event listeners after first interaction
                    document.removeEventListener('click', requestPermission);
                    document.removeEventListener('keydown', requestPermission);
                };

                // Request permission on first user interaction
                document.addEventListener('click', requestPermission, { once: true });
                document.addEventListener('keydown', requestPermission, { once: true });
            }

            // Show any pending notifications when user interacts with the page
            function showPendingNotificationsOnInteraction() {
                if (pendingNotifications.length > 0 && Notification.permission === "granted") {
                    pendingNotifications.forEach(createNotification);
                    pendingNotifications = [];
                }
            }

            // Create browser notification (only call this when permission is granted)
            function createNotification(data) {
                if (Notification.permission !== "granted") {
                    return;
                }

                const {
                    senderId,
                    messageType,
                    message
                } = data;

                let notificationBody = messageType === 'text' ?
                    (message.length > 50 ? message.substring(0, 50) + '...' : message) :
                    'Sent you a file';

                const notification = new Notification('New Message', {
                    body: notificationBody,
                    icon: '/assets/images/chat.png',
                    badge: '/assets/images/chat.png',
                    tag: senderId,
                    requireInteraction: false
                });

                notification.onclick = function() {
                    window.focus();
                    // Redirect to chat page
                    const chatUrl = userType === 'wholesaler' ?
                        '/wholesaler/chats' :
                        '/manufacturer/chats';
                    window.location.href = chatUrl;
                    notification.close();
                };

                // Auto close after 5 seconds
                setTimeout(() => notification.close(), 5000);
            }

            // Request total unread count from server
            function requestTotalUnreadCount() {
                if (ws && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        type: 'get_total_unread_count',
                        userId: userId
                    }));
                } else {
                    // Fallback to AJAX request
                    fetchUnreadCountViaAjax();
                }
            }

            // Fetch unread count via AJAX (fallback)
            function fetchUnreadCountViaAjax() {
                $.ajax({
                    url: '/get-total-unread-count',
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.count !== undefined) {
                            updateNotificationBadge(response.count);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching unread count:', error);
                    }
                });
            }

            // Update notification badge
            function updateNotificationBadge(count) {
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    badge.textContent = count;

                    if (count > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            }

            // Play notification sound
            function playNotificationSound() {
                // Optional: Add notification sound
                const audio = new Audio('/assets/notification.mp3');
                audio.play().catch(e => console.log('Could not play sound:', e));
            }

            // Heartbeat to keep connection alive
            setInterval(function() {
                if (ws && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        type: 'ping',
                        timestamp: new Date().toISOString()
                    }));
                }
            }, 30000);

            // Update last active timestamp periodically
            setInterval(function() {
                if (userId) {
                    $.ajax({
                        url: '/update-last-active',
                        type: 'POST',
                        data: {
                            '_token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // Last active updated
                        }
                    });
                }
            }, 60000); // Every minute

            // Initialize on page load
            $(document).ready(function() {
                initGlobalWebSocket();

                // Fetch initial unread count via AJAX as fallback
                fetchUnreadCountViaAjax();

                // Setup notification system
                setupNotificationPermissionRequest();

                // Show pending notifications when user interacts with page
                document.addEventListener('click', showPendingNotificationsOnInteraction);
                document.addEventListener('keydown', showPendingNotificationsOnInteraction);
            });

            // Clean up on page unload
            window.addEventListener('beforeunload', function() {
                if (ws && ws.readyState === WebSocket.OPEN) {
                    ws.close();
                }
            });

            // Add pulse animation to CSS
            const style = document.createElement('style');
            style.textContent = `
                @keyframes pulse {
                    0%, 100% {
                        transform: scale(1);
                    }
                    50% {
                        transform: scale(1.1);
                    }
                }
                .notification-badge {
                    animation: pulse 0.5s ease-in-out;
                }
            `;
            document.head.appendChild(style);
        })();
    </script>
</body>

</html>