@extends('layouts.surface.app')

@section('title', 'Admin Panel Login Arena')

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
    <section class="main mx-auto px-4 lg:px-8 bg-[#011e50]">
        <section class="min-h-screen flex items-center justify-center px-4">
            <div class="px-2 lg:px-8 w-full my-10">
                <div class="flex justify-center items-center md:px-9" id="createForm">
                    <div class="w-full lg:w-[500px] p-6 rounded-xl shadow-xl bg-[#00000036] text-white">
                        @php
                            if (session('error') || session('error')) {
                                $email = session('email');
                                $password = session('password');
                            } else {
                                $email = '';
                                $password = '';
                            }
                        @endphp

                        <img src="{{ $website_icon }}" alt=""
                            class="w-20 bg-white h-20 p-1 mx-auto my-4 rounded-full block">

                        <h2 class="text-xl lg:text-[40px] text-white mb-4 text-center font-semibold">
                            {{ $brandname }} ADMIN
                        </h2>

                        <form action="/admin/verify-login" method="post">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="text-sm text-gray-100 mb-2 block">Email</label>
                                <input type="email" id="email" name="email" placeholder="example@domain.com"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    required value="{{ $email }}">
                                <div id="emailError" class="error-message"></div>
                            </div>

                            <div class="mb-1 relative">
                                <label for="password" class="text-sm text-gray-100 mb-2 block">Password</label>
                                <input type="password" id="password" name="password" placeholder="********"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    required value="{{ $password }}">
                                <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                                    onclick="passwordToggle(this)">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <div id="passwordError" class="error-message"></div>
                            </div>


                            <button type="submit" id="submitBtn"
                                class="enabled_acc_btn w-full mt-6 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-3 text-xl uppercase rounded-lg">
                                Log in
                            </button>
                        </form>
                    </div>
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
