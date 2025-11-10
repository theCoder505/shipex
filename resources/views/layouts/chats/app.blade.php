<!doctype html>
<html lang="en" class="main_html">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @if (Auth::guard('wholesaler')->check())
        <meta name="user-type" content="wholesaler">
    @else
        <meta name="user-type" content="manufacturer">
    @endif


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ $website_icon }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="/assets/css/tailwind.css">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <title>{{ $brandname }} — @yield('title') </title>
    @yield('styles')
</head>


<body class="bg-white dark:bg-gray-950">

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="/assets/js/messaging.js"></script>

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
    </script>


    @yield('scripts')

</body>

</html>
