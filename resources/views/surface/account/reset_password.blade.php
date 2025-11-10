@extends('layouts.surface.app')

@section('title', 'Reset Your Password')

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
    </style>
@endsection

@section('content')
    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px]">
        <section class="min-h-screen flex justify-center bg-white px-4">
            <div class="px-2 lg:px-8 w-full my-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="grid items-start md:px-9">
                    <div class="mt-12 max-w-[500px]">
                        <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                            Reset your password
                        </h2>
                        <p class="text-[16px] text-gray-500 mb-6">
                            Enter a new password below
                        </p>

                        <form id="resetPasswordForm" action="/{{ $user_type }}/setup-new-password" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ $user_email }}">
                            <input type="hidden" name="reset_token" value="{{ $reset_token }}">

                            <div class="mb-4 relative">
                                <label for="password" class="text-sm text-gray-700 mb-2 block">New Password</label>
                                <input type="password" id="password" name="password" placeholder="********"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    required>
                                <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                                    onclick="passwordToggle(this)">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <div id="passwordError" class="error-message"></div>
                            </div>

                            <div class="mb-4 relative">
                                <label for="c_password" class="text-sm text-gray-700 mb-2 block">Confirm Password</label>
                                <input type="password" id="c_password" name="c_password" placeholder="********"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    required>
                                <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                                    onclick="passwordToggle(this)">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <div id="c_passwordError" class="error-message"></div>
                                <p class="text-sm text-gray-500 mt-1">Minimum 8 characters and 1 number</p>
                            </div>

                            <button type="submit" id="submitBtn"
                                class="enabled_acc_btn w-full mt-4 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg">
                                Reset password
                            </button>
                        </form>
                    </div>
                </div>

                <div class="">
                    <img src="/assets/images/locks.png" alt="SHIPEX"
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

        function isValidPassword(password) {
            return password.length >= 8 && /\d/.test(password);
        }

        function validateField(field) {
            const value = field.value.trim();
            const fieldName = field.name;
            const errorElement = document.getElementById(`${fieldName}Error`);

            field.classList.remove('input-error', 'input-success');
            errorElement.style.display = 'none';

            let isValid = false;

            if (fieldName === 'password') {
                if (value === '') {
                    errorElement.textContent = 'Password is required';
                } else if (!isValidPassword(value)) {
                    errorElement.textContent = 'Password must be at least 8 characters and contain at least 1 number';
                } else {
                    isValid = true;
                }
            } else if (fieldName === 'c_password') {
                const passwordValue = document.getElementById('password').value.trim();
                if (value === '') {
                    errorElement.textContent = 'Please confirm your password';
                } else if (value !== passwordValue) {
                    errorElement.textContent = 'Passwords do not match';
                } else {
                    isValid = true;
                }
            }

            if (!isValid && value !== '') {
                field.classList.add('input-error');
                errorElement.style.display = 'block';
            } else if (isValid) {
                field.classList.add('input-success');
            }

            return isValid;
        }

        function validateForm() {
            const passwordField = document.getElementById('password');
            const cPasswordField = document.getElementById('c_password');
            const submitBtn = document.getElementById('submitBtn');

            const isPasswordValid = validateField(passwordField);
            const isCPasswordValid = validateField(cPasswordField);

            if (isPasswordValid && isCPasswordValid) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const cPasswordField = document.getElementById('c_password');
            const form = document.getElementById('resetPasswordForm');

            passwordField.addEventListener('input', function() {
                validateField(this);
                // Also validate confirm password when password changes
                if (cPasswordField.value.trim() !== '') {
                    validateField(cPasswordField);
                }
                validateForm();
            });

            cPasswordField.addEventListener('input', function() {
                validateField(this);
                validateForm();
            });

            // Handle form submission
            form.addEventListener('submit', function(e) {
                const passwordField = document.getElementById('password');
                const cPasswordField = document.getElementById('c_password');

                const isPasswordValid = validateField(passwordField);
                const isCPasswordValid = validateField(cPasswordField);

                if (!isPasswordValid || !isCPasswordValid) {
                    e.preventDefault();
                    
                    // Show error message for form validation
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Please fix the validation errors before submitting.',
                        timer: 4000,
                        showConfirmButton: true
                    });
                }
            });

            // Initial form validation
            validateForm();
        });

        // Show server-side errors if any
        @if(Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ Session::get('error') }}",
                timer: 4000,
                showConfirmButton: true
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ $errors->first() }}",
                timer: 4000,
                showConfirmButton: true
            });
        @endif
    </script>
@endsection