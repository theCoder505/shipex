@extends('layouts.surface.app')

@section('title', 'Apply as a manufacturer')

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

        .enabled_acc_btn:disabled {
            background-color: #9ca3af !important;
            cursor: not-allowed;
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

                <!-- Signup Form -->
                <div class="flex flex-col justify-center md:px-9" id="createForm">
                    <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                        Apply as a manufacturer
                    </h2>

                    <p class="text-[16px] text-gray-500 mt-4 mb-2">
                        Already have an account?
                        <a href="/manufacturer/login" class="text-[#003FB4] underline">Sign in</a>
                    </p>

                    <p class="text-[16px] text-gray-500 mb-6">
                        Are you a wholesaler?
                        <a href="/wholesaler/signup" class="text-[#003FB4] underline">Register here</a>
                    </p>

                    <div class="max-w-[400px]">
                        <!-- Social Buttons -->
                        <div class="my-4 grid gap-5">
                            <a href="/manufacturer/social-signup/google"
                                class="border px-4 py-2 rounded-lg flex cursor-pointer gap-2 justify-center items-center border-[#BCBCBC] hover:bg-[#D6E2F7] text-sm">
                                <img src="/assets/images/google.png" alt="">
                                <p>Continue with Google</p>
                            </a>

                            <a href="/manufacturer/social-signup/kakao"
                                class="border px-4 py-2 rounded-lg flex cursor-pointer gap-2 justify-center items-center border-[#BCBCBC] hover:bg-[#D6E2F7] text-sm">
                                <img src="/assets/images/kakao.png" alt="">
                                <p>Continue with Kakao</p>
                            </a>
                        </div>

                        <!-- Divider -->
                        <div class="flex items-center my-4">
                            <hr class="flex-grow border-gray-300">
                            <span class="px-2 text-gray-500 text-sm">or</span>
                            <hr class="flex-grow border-gray-300">
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="text-sm text-gray-700 mb-2 block">Email</label>
                            <input type="email" id="email" name="email" placeholder="example@domain.com"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                required>
                            <div id="emailError" class="error-message"></div>
                        </div>

                        <!-- Password -->
                        <div class="mb-4 relative">
                            <label for="password" class="text-sm text-gray-700 mb-2 block">Password</label>
                            <input type="password" id="password" name="password" placeholder="********"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                required>
                            <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                                onclick="passwordToggle(this)">
                                <i class="fa fa-eye"></i>
                            </span>
                            <div id="passwordError" class="error-message"></div>
                            <p class="text-sm text-gray-500 mt-1">Minimum 8 characters and 1 number</p>
                        </div>

                        <button type="button" id="submitBtn" onclick="verifyFormAndProceed()"
                            class="enabled_acc_btn w-full mt-4 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg"
                            disabled>
                            Apply as a manufacturer
                        </button>

                        <p class="text-xs text-gray-500 mt-3">
                            By creating an account, you agree to our
                            <a href="/privacy-policy" class="text-[#003FB4] underline">Privacy Policy</a>
                            and
                            <a href="/terms-of-use" class="text-[#003FB4] underline">Terms of use</a>.
                        </p>
                    </div>
                </div>

                <!-- Right Image -->
                <div class="hidden md:block">
                    <img src="/assets/images/menufacturer_signup.png" alt="SHIPEX"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
            </div>
        </section>
    </section>

    <!-- OTP Modal -->
    <div id="otpModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Verify Your Email</h3>
                <button type="button" class="close-modal" onclick="closeOtpModal()">&times;</button>
            </div>
            <p class="text-gray-600 mb-4">We've sent a verification code to your email. Please enter it below.</p>

            <form id="otpForm">
                @csrf
                <input type="hidden" id="otpEmail" name="email">

                <div class="otp-input-container">
                    <input type="text" class="otp-input" maxlength="1" data-index="1" oninput="moveToNext(this)">
                    <input type="text" class="otp-input" maxlength="1" data-index="2" oninput="moveToNext(this)">
                    <input type="text" class="otp-input" maxlength="1" data-index="3" oninput="moveToNext(this)">
                    <input type="text" class="otp-input" maxlength="1" data-index="4" oninput="moveToNext(this)">
                    <input type="text" class="otp-input" maxlength="1" data-index="5" oninput="moveToNext(this)">
                    <input type="text" class="otp-input" maxlength="1" data-index="6" oninput="moveToNext(this)">
                </div>

                <div id="otpError" class="error-message mb-4"></div>

                <button type="button" id="verifyOtpBtn" onclick="verifyOtp()"
                    class="w-full mt-4 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg">
                    Verify Code
                </button>

                <div class="text-center mt-4">
                    <button type="button" id="resendOtpBtn" onclick="resendOtp()" class="resend-otp">
                        Resend Code
                    </button>
                    <p id="resendTimer" class="text-sm text-gray-500 mt-1" style="display: none;"></p>
                </div>
            </form>
        </div>
    </div>
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

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
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

            if (fieldName === 'email') {
                if (value === '') {
                    errorElement.textContent = 'Email is required';
                } else if (!isValidEmail(value)) {
                    errorElement.textContent = 'Please enter a valid email address';
                } else {
                    isValid = true;
                }
            } else if (fieldName === 'password') {
                if (value === '') {
                    errorElement.textContent = 'Password is required';
                } else if (!isValidPassword(value)) {
                    errorElement.textContent = 'Password must be at least 8 characters and contain at least 1 number';
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
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            const submitBtn = document.getElementById('submitBtn');

            const isEmailValid = validateField(emailField);
            const isPasswordValid = validateField(passwordField);

            if (isEmailValid && isPasswordValid) {
                submitBtn.disabled = false;
            } else {
                submitBtn.disabled = true;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');

            emailField.addEventListener('input', function() {
                validateField(this);
                validateForm();
            });

            passwordField.addEventListener('input', function() {
                validateField(this);
                validateForm();
            });

            validateForm();
        });

        function verifyFormAndProceed() {
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            const submitBtn = document.getElementById('submitBtn');

            const isEmailValid = validateField(emailField);
            const isPasswordValid = validateField(passwordField);

            if (isEmailValid && isPasswordValid) {
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';

                // Make AJAX POST request to /manufacturer/verify-signup
                fetch('/manufacturer/verify-signup', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: emailField.value,
                            password: passwordField.value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Apply as a manufacturer';

                        if (data.type === 'error') {
                            // Show error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message,
                                timer: 4000,
                                showConfirmButton: true
                            });
                        } else {
                            // Proceed to show OTP modal
                            showOtpModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Apply as a manufacturer';

                        // Show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            timer: 4000,
                            showConfirmButton: true
                        });
                    });
            } else {
                // Validate fields to show errors
                if (!isEmailValid) {
                    validateField(emailField);
                }
                if (!isPasswordValid) {
                    validateField(passwordField);
                }
            }
        }

        function showOtpModal() {
            const email = document.getElementById('email').value;
            document.getElementById('otpEmail').value = email;
            document.getElementById('otpModal').style.display = 'flex';

            // Clear any previous OTP inputs
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach(input => {
                input.value = '';
            });

            // Focus on first OTP input
            document.querySelector('.otp-input[data-index="1"]').focus();

            // Start resend timer
            startResendTimer();
        }

        function closeOtpModal() {
            document.getElementById('otpModal').style.display = 'none';
        }

        function moveToNext(input) {
            const index = parseInt(input.getAttribute('data-index'));
            const value = input.value;

            // Auto move to next input if current has value
            if (value && index < 6) {
                document.querySelector(`.otp-input[data-index="${index + 1}"]`).focus();
            }

            // If backspace is pressed and field is empty, move to previous
            if (!value && index > 1) {
                document.querySelector(`.otp-input[data-index="${index - 1}"]`).focus();
            }
        }

        function getOtpCode() {
            let otp = '';
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach(input => {
                otp += input.value;
            });
            return otp;
        }

        function verifyOtp() {
            const email = document.getElementById('otpEmail').value;
            const otp = getOtpCode();
            const errorElement = document.getElementById('otpError');

            // Validate OTP
            if (otp.length !== 6) {
                errorElement.textContent = 'Please enter the complete 6-digit code';
                errorElement.style.display = 'block';
                return;
            }

            // Show loading state
            const verifyBtn = document.getElementById('verifyOtpBtn');
            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Verifying...';

            // Send OTP verification request
            fetch('/manufacturer/otp-verification', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        email: email,
                        otp: otp
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.type === 'success') {
                        window.location.href = '/manufacturer/application';
                    } else {
                        // Show error
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            timer: 4000,
                            showConfirmButton: true
                        });

                        // Reset button
                        verifyBtn.disabled = false;
                        verifyBtn.textContent = 'Verify Code';

                        // Clear OTP inputs
                        const otpInputs = document.querySelectorAll('.otp-input');
                        otpInputs.forEach(input => {
                            input.value = '';
                        });
                        document.querySelector('.otp-input[data-index="1"]').focus();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        timer: 4000,
                        showConfirmButton: true
                    });

                    // Reset button
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verify Code';
                });
        }

        function resendOtp() {
            const email = document.getElementById('otpEmail').value;
            const resendBtn = document.getElementById('resendOtpBtn');
            const timerElement = document.getElementById('resendTimer');
            const passwordField = document.getElementById('password').value;

            // Disable resend button and start timer
            resendBtn.disabled = true;
            startResendTimer();

            // Send resend request
            fetch('/manufacturer/verify-signup', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        email: email,
                        password: passwordField,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.type === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Verification code has been resent to your email.',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            timer: 4000,
                            showConfirmButton: true
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'An error occurred. Please try again.',
                        timer: 4000,
                        showConfirmButton: true
                    });
                });
        }

        function startResendTimer() {
            const resendBtn = document.getElementById('resendOtpBtn');
            const timerElement = document.getElementById('resendTimer');

            resendBtn.disabled = true;
            timerElement.style.display = 'block';

            let timeLeft = 60;

            const timer = setInterval(() => {
                timerElement.textContent = `Resend available in ${timeLeft} seconds`;
                timeLeft--;

                if (timeLeft < 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    timerElement.style.display = 'none';
                }
            }, 1000);
        }
    </script>
@endsection