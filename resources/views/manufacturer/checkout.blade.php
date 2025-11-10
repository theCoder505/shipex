@extends('layouts.surface.app')

@section('title', 'Checkout and complete subscription with PayPal')

@section('style')
    <style>
        body {
            background: linear-gradient(180deg, #0a1628 0%, #1a2332 100%);
            min-height: 100vh;
        }

        .hero-title {
            color: white;
            font-size: 3rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 15px;
        }

        .hero-description {
            color: #8b9dc3;
            font-size: 1.1rem;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .payment-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .loader {
            border: 3px solid #f3f3f3;
            border-radius: 50%;
            border-top: 3px solid #0070ba;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .pay-now-btn {
            background: #0070ba;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .pay-now-btn:hover {
            background: #005ea6;
        }

        .pay-now-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
    <div class="hero_section my-8 px-4 lg:px-8 max-w-[1200px] mx-auto">
        <h1 class="hero-title">Pay With PayPal</h1>
        <p class="hero-description">
            Checkout and complete subscription with PayPal. <br>
            You've chosen a
            <span class="capitalize font-semibold text-white">{{ $package_display }}</span>
            subscription that has
            @if ($type == 'fixed')
                ${{ $discount }}
            @elseif($type == 'percentage')
                {{ $discount }}%
            @else
                no
            @endif
            discount.
            <br>
            Your total billing amount is <span class="font-semibold text-white">${{ $amount }}</span> which you need to
            pay with PayPal!
        </p>

        <div class="mt-12 max-w-2xl mx-auto">
            <div class="payment-card p-8">
                <div class="bg-[#0070ba] text-[#fff] rounded-xl text-center p-6 mb-8">
                    <p class="text-[16px] font-semibold uppercase">Secure Payment Integrated With PayPal</p>
                </div>

                <div class="my-5">
                    <h2 class="font-bold text-[20px] text-white">Payment Information</h2>
                    <p class="text-sm text-[#8b9dc3]">
                        Enter your payment details below to complete your subscription.
                    </p>
                </div>

                <form id="payment-form" class="space-y-6" method="POST" action="{{ route('manufacturer.process-payment') }}">
                    @csrf

                    <input type="hidden" name="package_type" id="package_type" value="{{ $package }}">
                    <input type="hidden" name="amount" id="amount" value="{{ $amount }}">
                    <input type="hidden" name="coupon_code" id="coupon_code" value="{{ request('coupon_code') ?? '' }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                            <input type="text" id="full_name" placeholder="John Doe" name="full_name" required
                                class="w-full bg-[#1a2332] text-white px-3 py-2 mt-1 border border-gray-600 rounded-md outline-none focus:border-[#0070ba]">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                            <input type="email" id="email" placeholder="john.doe@example.com" name="email" required
                                class="w-full bg-[#1a2332] text-white px-3 py-2 mt-1 border border-gray-600 rounded-md outline-none focus:border-[#0070ba]">
                        </div>
                    </div>

                    <div class="flex gap-4 items-center mt-6">
                        <input type="checkbox" id="terms" required class="rounded">
                        <label for="terms" class="text-gray-300 text-[14px]">
                            I agree to the <a href="/terms-of-use" class="text-[#0070ba] hover:underline">Terms of Use</a>
                            and authorize this one-time payment.
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="pay-now-btn" class="pay-now-btn">
                        Pay Now ${{ $amount }}
                    </button>
                </form>
            </div>

            <div class="text-center mt-6 text-gray-400 text-sm">
                <p>Your payment is secure and encrypted. PayPal protects your financial information.</p>
            </div>
        </div>
    </div>

    <!-- Processing Modal -->
    <div id="processingModal" class="fixed inset-0 bg-[#00000040] items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl px-6 py-8 w-full max-w-md mx-4 text-center">
            <div class="loader mx-auto"></div>
            <h3 class="font-semibold text-[#1E1E1E] mt-4">Redirecting to PayPal...</h3>
            <p class="text-[#5D5E5E] text-sm mt-2">Please wait while we redirect you to PayPal for secure payment processing.</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            if (!validateForm()) {
                return;
            }

            // Show processing modal
            document.getElementById('processingModal').classList.remove('hidden');
            document.getElementById('processingModal').classList.add('flex');

            // Disable the submit button to prevent multiple submissions
            const submitBtn = document.getElementById('pay-now-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            // Submit the form via AJAX to handle the PayPal redirect
            const formData = new FormData(this);

            fetch('{{ route('manufacturer.process-payment') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Omnipay will return a redirect URL - follow it
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            // If no redirect URL, assume the response contains the redirect
                            // The form submission will handle the redirect automatically
                            this.submit();
                        }
                    } else {
                        // Hide processing modal
                        document.getElementById('processingModal').classList.add('hidden');
                        document.getElementById('processingModal').classList.remove('flex');
                        
                        // Re-enable button
                        submitBtn.disabled = false;
                        submitBtn.textContent = `Pay Now ${{ $amount }}`;
                        
                        // Show error message
                        alert(data.message || 'Payment initialization failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Hide processing modal
                    document.getElementById('processingModal').classList.add('hidden');
                    document.getElementById('processingModal').classList.remove('flex');
                    
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = `Pay Now ${{ $amount }}`;
                    
                    alert('An error occurred. Please try again.');
                });
        });

        function validateForm() {
            const fullName = document.getElementById('full_name').value;
            const email = document.getElementById('email').value;
            const terms = document.getElementById('terms').checked;

            if (!fullName || !email || !terms) {
                alert('Please fill in all required fields and accept the terms.');
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            return true;
        }

        // Auto-hide processing modal after 10 seconds as a fallback
        setTimeout(() => {
            const processingModal = document.getElementById('processingModal');
            if (processingModal && !processingModal.classList.contains('hidden')) {
                processingModal.classList.add('hidden');
                processingModal.classList.remove('flex');
                
                const submitBtn = document.getElementById('pay-now-btn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = `Pay Now ${{ $amount }}`;
                }
                
                alert('The request is taking longer than expected. Please try again.');
            }
        }, 10000);
    </script>
@endsection