@extends('layouts.admin.app')

@section('title', 'Account Settings')

@section('style')
<style>
    .loader {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3498db;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: inline-block;
        margin-left: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fefefe;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .dark .modal-content {
        background-color: #1f2937;
    }

    .otp-input {
        width: 50px;
        height: 50px;
        text-align: center;
        font-size: 24px;
        margin: 0 5px;
        border: 2px solid #d1d5db;
        border-radius: 8px;
    }

    .otp-input:focus {
        outline: none;
        border-color: #3b82f6;
    }

    .dark .otp-input {
        background-color: #374151;
        border-color: #4b5563;
        color: white;
    }
</style>
@endsection

@section('content')

    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Account Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage admin email and password settings.</p>
    </div>


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
        <div class="shadow-xl bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-300 dark:border-gray-700">
            <h3 class="text-2xl font-semibold mb-4 text-gray-700 dark:text-gray-100">Change Admin Email</h3>
            <form id="emailChangeForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Old Email Address</label>
                    <input type="email" id="email" name="email" value="" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label for="new_email" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">New Email Address</label>
                    <input type="email" id="new_email" name="new_email" value="" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-6">
                    <div class="mb-1 relative">
                        <label for="password"
                            class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Password</label>
                        <input type="password" id="password" name="password" placeholder="********"
                            class="border w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4] text-black dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required="" value="">
                        <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500 dark:text-gray-300"
                            onclick="passwordToggle(this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" id="emailSubmitBtn"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update Settings
                </button>
            </form>
        </div>


        <div class="shadow-xl bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-300 dark:border-gray-700">
            <h3 class="text-2xl font-semibold mb-4 text-gray-700 dark:text-gray-100">Update Admin Password</h3>
            <form id="passwordChangeForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="password_email" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Email
                        Address</label>
                    <input type="email" id="password_email" name="email" value="" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-6">
                    <div class="mb-1 relative">
                        <label for="old_password" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Old
                            Password</label>
                        <input type="password" id="old_password" name="password" placeholder="********"
                            class="border w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4] text-black dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required="" value="">
                        <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500 dark:text-gray-300"
                            onclick="passwordToggle(this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="mb-1 relative">
                        <label for="new_password" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">New
                            Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="********"
                            class="border w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4] text-black dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            required="" value="">
                        <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500 dark:text-gray-300"
                            onclick="passwordToggle(this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" id="passwordSubmitBtn"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Update Settings
                </button>
            </form>
        </div>
    </div>


    <!-- OTP Modal for Email Change -->
    <div id="emailOtpModal" class="modal">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white text-center">Verify OTP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-center">Enter the OTP sent to your email</p>
            
            <form id="emailOtpForm">
                <div class="flex justify-center mb-6">
                    <input type="text" maxlength="1" class="otp-input" id="email_otp_1" />
                    <input type="text" maxlength="1" class="otp-input" id="email_otp_2" />
                    <input type="text" maxlength="1" class="otp-input" id="email_otp_3" />
                    <input type="text" maxlength="1" class="otp-input" id="email_otp_4" />
                    <input type="text" maxlength="1" class="otp-input" id="email_otp_5" />
                    <input type="text" maxlength="1" class="otp-input" id="email_otp_6" />
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeEmailOtpModal()"
                        class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" id="emailOtpSubmitBtn"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Verify OTP
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- OTP Modal for Password Change -->
    <div id="passwordOtpModal" class="modal">
        <div class="modal-content">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white text-center">Verify OTP</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6 text-center">Enter the OTP sent to your email</p>
            
            <form id="passwordOtpForm">
                <div class="flex justify-center mb-6">
                    <input type="text" maxlength="1" class="otp-input" id="password_otp_1" />
                    <input type="text" maxlength="1" class="otp-input" id="password_otp_2" />
                    <input type="text" maxlength="1" class="otp-input" id="password_otp_3" />
                    <input type="text" maxlength="1" class="otp-input" id="password_otp_4" />
                    <input type="text" maxlength="1" class="otp-input" id="password_otp_5" />
                    <input type="text" maxlength="1" class="otp-input" id="password_otp_6" />
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closePasswordOtpModal()"
                        class="flex-1 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" id="passwordOtpSubmitBtn"
                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Verify OTP
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <script>
        // Password Toggle Function
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

        // Variables to store form data
        let emailFormData = {};
        let passwordFormData = {};

        // Email Change Form Submission
        document.getElementById('emailChangeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('emailSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Processing... <span class="loader"></span>';
            submitBtn.disabled = true;

            emailFormData = {
                email: document.getElementById('email').value,
                new_email: document.getElementById('new_email').value,
                password: document.getElementById('password').value,
                _token: '{{ csrf_token() }}'
            };

            fetch('/admin/verify-account-email-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(emailFormData)
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    openEmailOtpModal();
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
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    timer: 4000,
                    showConfirmButton: true
                });
            });
        });

        // Password Change Form Submission
        document.getElementById('passwordChangeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('passwordSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Processing... <span class="loader"></span>';
            submitBtn.disabled = true;

            passwordFormData = {
                email: document.getElementById('password_email').value,
                password: document.getElementById('old_password').value,
                new_password: document.getElementById('new_password').value,
                _token: '{{ csrf_token() }}'
            };

            fetch('/admin/verify-account-password-update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(passwordFormData)
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    openPasswordOtpModal();
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
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    timer: 4000,
                    showConfirmButton: true
                });
            });
        });

        // Email OTP Modal Functions
        function openEmailOtpModal() {
            document.getElementById('emailOtpModal').classList.add('show');
            document.getElementById('email_otp_1').focus();
        }

        function closeEmailOtpModal() {
            document.getElementById('emailOtpModal').classList.remove('show');
            clearEmailOtpInputs();
        }

        function clearEmailOtpInputs() {
            for (let i = 1; i <= 6; i++) {
                document.getElementById('email_otp_' + i).value = '';
            }
        }

        // Password OTP Modal Functions
        function openPasswordOtpModal() {
            document.getElementById('passwordOtpModal').classList.add('show');
            document.getElementById('password_otp_1').focus();
        }

        function closePasswordOtpModal() {
            document.getElementById('passwordOtpModal').classList.remove('show');
            clearPasswordOtpInputs();
        }

        function clearPasswordOtpInputs() {
            for (let i = 1; i <= 6; i++) {
                document.getElementById('password_otp_' + i).value = '';
            }
        }

        // OTP Input Auto-focus for Email
        for (let i = 1; i <= 6; i++) {
            document.getElementById('email_otp_' + i).addEventListener('input', function() {
                if (this.value.length === 1 && i < 6) {
                    document.getElementById('email_otp_' + (i + 1)).focus();
                }
            });

            document.getElementById('email_otp_' + i).addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && i > 1) {
                    document.getElementById('email_otp_' + (i - 1)).focus();
                }
            });
        }

        // OTP Input Auto-focus for Password
        for (let i = 1; i <= 6; i++) {
            document.getElementById('password_otp_' + i).addEventListener('input', function() {
                if (this.value.length === 1 && i < 6) {
                    document.getElementById('password_otp_' + (i + 1)).focus();
                }
            });

            document.getElementById('password_otp_' + i).addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && i > 1) {
                    document.getElementById('password_otp_' + (i - 1)).focus();
                }
            });
        }

        // Email OTP Form Submission
        document.getElementById('emailOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let otp = '';
            for (let i = 1; i <= 6; i++) {
                otp += document.getElementById('email_otp_' + i).value;
            }

            if (otp.length !== 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please enter all 6 digits of the OTP.',
                    timer: 4000,
                    showConfirmButton: true
                });
                return;
            }

            const submitBtn = document.getElementById('emailOtpSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Verifying... <span class="loader"></span>';
            submitBtn.disabled = true;

            fetch('/admin/verify-email-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: emailFormData.email,
                    new_email: emailFormData.new_email,
                    otp: otp,
                    _token: '{{ csrf_token() }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (data.success) {
                    closeEmailOtpModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
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
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    timer: 4000,
                    showConfirmButton: true
                });
            });
        });

        // Password OTP Form Submission
        document.getElementById('passwordOtpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            let otp = '';
            for (let i = 1; i <= 6; i++) {
                otp += document.getElementById('password_otp_' + i).value;
            }

            if (otp.length !== 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Please enter all 6 digits of the OTP.',
                    timer: 4000,
                    showConfirmButton: true
                });
                return;
            }

            const submitBtn = document.getElementById('passwordOtpSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Verifying... <span class="loader"></span>';
            submitBtn.disabled = true;

            fetch('/admin/verify-password-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    email: passwordFormData.email,
                    password: passwordFormData.password,
                    new_password: passwordFormData.new_password,
                    otp: otp,
                    _token: '{{ csrf_token() }}'
                })
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                if (data.success) {
                    closePasswordOtpModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
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
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong. Please try again.',
                    timer: 4000,
                    showConfirmButton: true
                });
            });
        });
    </script>
@endsection