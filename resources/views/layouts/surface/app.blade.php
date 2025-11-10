<!doctype html>
<html lang="en" class="main_html">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    @auth
        @if (auth()->guard('wholesaler')->check())
            <meta name="user-id" content="{{ auth()->guard('wholesaler')->user()->wholesaler_uid }}">
            <meta name="user-type" content="wholesaler">
            <meta name="user-name" content="{{ auth()->guard('wholesaler')->user()->company_name }}">
        @elseif(auth()->guard('manufacturer')->check())
            <meta name="user-id" content="{{ auth()->guard('manufacturer')->user()->manufacturer_uid }}">
            <meta name="user-type" content="manufacturer">
            <meta name="user-name" content="{{ auth()->guard('manufacturer')->user()->company_name_en }}">
        @else
            <meta name="user-id" content="">
            <meta name="user-type" content="">
            <meta name="user-name" content="">
        @endif
    @endauth

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
</body>

</html>
