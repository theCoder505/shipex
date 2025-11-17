@extends('layouts.surface.app')

@section('title', 'Log in as a ' . $user_type)

@section('style')
    <style>
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .input-error {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 1px #dc2626 !important;
        }

        .input-success {
            border-color: #16a34a !important;
        }

        /* Modal styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 8px;
            padding: 24px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #121212;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
        }

        .otp-input-container {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 1.25rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }

        .otp-input:focus {
            outline: none;
            border-color: #003FB4;
            box-shadow: 0 0 0 1px #003FB4;
        }

        .resend-otp {
            color: #003FB4;
            background: none;
            border: none;
            cursor: pointer;
            text-decoration: underline;
            margin-top: 8px;
        }

        .resend-otp:disabled {
            color: #9ca3af;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px]">
        <section class="min-h-screen flex items-center justify-center bg-white px-4">
            <div class="px-2 lg:px-8 w-full my-10 grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Image for mobile -->
                <div class="block md:hidden">
                    <img src="/assets/images/boxes.png" alt="SHIPEX" class="w-full h-full object-cover rounded-lg shadow-md">
                </div>

                <div class="flex flex-col justify-center md:px-9" id="createForm">
                    <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                        Log In to {{ $brandname }} as a
                        <span class="capitalize">{{ $user_type }}</span>
                    </h2>

                    <p class="text-gray-500 mb-2 mt-4 text-sm flex gap-2">
                        <span>Log in as</span>
                        <a href="/wholesaler/login" class="text-[#003FB4] underline">Wholesaler</a>
                        <a href="/manufacturer/login" class="text-[#003FB4] underline">Manufacturer</a>
                    </p>

                    <p class="text-sm text-gray-500 mb-6">
                        Don't have an account yet?
                        <a href="/{{ $user_type }}/signup" class="text-[#003FB4] underline">Register here</a>
                    </p>

                    <div class="max-w-[400px]">
                        <!-- Social Buttons -->
                        <div class="my-4 grid gap-5">
                            <a href="/{{ $user_type }}/login-with-google"
                                class="border px-4 py-2 rounded-lg flex cursor-pointer gap-2 justify-center items-center border-[#BCBCBC] hover:bg-[#D6E2F7] text-sm">
                                <img src="/assets/images/google.png" alt="">
                                <p>Continue with Google</p>
                            </a>

                            <a href="/{{ $user_type }}/login-with-kakao"
                                class="border px-4 py-2 rounded-lg flex cursor-pointer gap-2 justify-center items-center border-[#BCBCBC] hover:bg-[#D6E2F7] text-sm">
                                <img src="/assets/images/kakao.png" alt="">
                                <p>Continue with Kakao</p>
                            </a>
                        </div>

                        <div class="flex items-center my-4">
                            <hr class="flex-grow border-gray-300">
                            <span class="px-2 text-gray-500 text-sm">or</span>
                            <hr class="flex-grow border-gray-300">
                        </div>

                        @php
                            if (session('error_password') || session('error_email') || session('error')) {
                                $email = session('email');
                                $password = session('password');
                            } else {
                                $email = '';
                                $password = '';
                            }
                        @endphp


                        <form action="/{{ $user_type }}/sign-in-verification" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="text-sm text-gray-700 mb-2 block">Email</label>
                                <input type="email" id="email" name="email" placeholder="example@domain.com"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    required value="{{ $email }}">
                                <div id="emailError" class="error-message"></div>
                            </div>

                            <div class="mb-1 relative">
                                <label for="password" class="text-sm text-gray-700 mb-2 block">Password</label>
                                <input type="password" id="password" name="password" placeholder="********"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    required value="{{ $password }}">
                                <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                                    onclick="passwordToggle(this)">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <div id="passwordError" class="error-message"></div>
                            </div>


                            <p class="text-xs text-gray-500">
                                <a href="/{{ $user_type }}/forget-password" class="underline">Password forgotten?</a>
                            </p>


                            @if (session('error_password') || session('error_email'))
                                <div class="grid gap-4 mt-12">
                                    @if (session('error_email'))
                                        <div class="error_msg bg-red-100 p-4 rounded">
                                            <div class="flex gap-2">
                                                <div class="w-18">
                                                    <img src="/assets/images/warning.png" alt=""
                                                        class="w-full h-auto">
                                                </div>
                                                <p class="text-red-500 text-sm">
                                                    This email address does not exist. Please
                                                    <a href="/create-account" class="underline font-medium">create an
                                                        account</a>
                                                    or reach out customer support at
                                                    <a href="mailto:{{ $contact_mail }}"
                                                        class="underline font-medium">{{ $contact_mail }}.</a>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if (session('error_password'))
                                        <div class="error_msg bg-red-100 p-4 rounded">
                                            <div class="flex gap-2">
                                                <div class="w-18">
                                                    <img src="/assets/images/warning.png" alt=""
                                                        class="w-full h-auto">
                                                </div>
                                                <p class="text-red-500 text-sm">
                                                    The Password to this email address does not match. Please try again or
                                                    <a href="/create-account" class="underline font-medium">create an
                                                        account</a>
                                                    or reach out customer support at
                                                    <a href="mailto:{{ $contact_mail }}"
                                                        class="underline font-medium">{{ $contact_mail }}.</a>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif


                            <button type="submit" id="submitBtn" onclick="verifyFormAndProceed()"
                                class="enabled_acc_btn w-full mt-12 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg">
                                Log in
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="hidden md:block">
                    <img src="/assets/images/signin_box.png" alt="SHIPEX"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
            </div>
        </section>
    </section>

@endsection

@section('scripts')
    <script>
        function passwordToggle(el) {
            const input = el.parentElement.querySelector('input');
            const icon = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
