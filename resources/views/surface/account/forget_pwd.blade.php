@extends('layouts.surface.app')

@section('title', 'Forget Password | ' . $user_type)

@section('style')

@endsection

@section('content')
    <section class="main mx-auto px-4 max-w-[1600px]">
        <section class="min-h-screen flex items-center justify-center bg-white px-4">
            <div class="px-2 w-full my-10 grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Image for mobile -->
                <div class="block md:hidden">
                    <img src="/assets/images/boxes.png" alt="SHIPEX" class="w-full h-full object-cover rounded-lg shadow-md">
                </div>

                <div class="flex flex-col justify-center md:px-9 max-w-[500px]" id="createForm">
                    <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                        Reset Password
                    </h2>

                    <p class="text-gray-500 mt-2 text-sm flex gap-2">
                        Enter the email address associated with your account and we will send you a link to reset your
                        password.
                    </p>

                    <div class="mt-8">

                        @php
                            if (session('email')) {
                                $email = session('email');
                            } else {
                                $email = '';
                            }
                        @endphp


                        <form action="/{{ $user_type }}/forget-password-request" method="post" id="forgetPasswordForm">
                            @csrf
                            <div class="mb-4">
                                <label for="email" class="text-sm text-gray-700 mb-2 block">Email</label>
                                <input type="email" id="email" name="email" placeholder="example@domain.com"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    required value="{{ $email }}">
                                <div id="emailError" class="error-message"></div>
                            </div>

                            @if (session('error_email'))
                                <div class="grid gap-4 mt-12">
                                    @if (session('error_email'))
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


                            <button type="submit" id="submitBtn"
                                class="enabled_acc_btn w-full mt-6 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg">
                                Send reset link
                            </button>

                            <a href="/{{ $user_type }}/login" class="text-[#003FB4] mt-4 text-center block">
                                Cancel
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="hidden md:block">
                    <img src="/assets/images/forget_pwd.png" alt="SHIPEX"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
            </div>
        </section>
    </section>

@endsection

@section('scripts')
    <script>
        function showSubmit(passedThis) {
            const button = passedThis;
            const originalText = button.innerHTML;
            
            button.innerHTML = '<span class="loading-spinner"></span>Sending reset link...';
            button.classList.add('btn-loading');
            
            document.getElementById('forgetPasswordForm').addEventListener('submit', function(e) {
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('btn-loading');
                    document.getElementById('forgetPasswordForm').submit();
                }, 3000);
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('forgetPasswordForm');
            
            form.addEventListener('submit', function(e) {
                showSubmit(submitBtn);
            });
        });
    </script>
@endsection