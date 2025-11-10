@extends('layouts.surface.app')
@section('title', 'Choose A Subscription Package')
@section('style')
    <style>
        body {
            background: linear-gradient(180deg, #0a1628 0%, #1a2332 100%);
            min-height: 100vh;
        }

        .subscription-card {
            background: linear-gradient(180deg, #1e3a5f 0%, #0f1f3d 100%);
            border-radius: 24px;
            position: relative;
            overflow: visible;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            min-height: 520px;
            border: 2px solid transparent;
        }

        .subscription-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 149, 255, 0.3);
            border-color: rgba(0, 149, 255, 0.5);
        }

        .subscription-card.active {
            border-color: #0095ff;
            box-shadow: 0 20px 60px rgba(0, 149, 255, 0.5);
            transform: translateY(-8px) scale(1.02);
        }

        .card-header {
            background: linear-gradient(135deg, #0095ff 0%, #0066cc 100%);
            height: 100px;
            border-radius: 24px 24px 0 0;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            clip-path: ellipse(100% 100% at 50% 0%);
        }

        .card-icon {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            bottom: -35px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            border: 4px solid #1e3a5f;
        }

        .price-section {
            padding-top: 50px;
            text-align: center;
            margin-bottom: 30px;
        }

        .price {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .period {
            color: #8b9dc3;
            font-size: 1rem;
            margin-left: 8px;
        }

        .feature-list {
            padding: 0 30px;
            margin-bottom: 30px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            color: white;
            font-size: 0.95rem;
        }

        .feature-item svg {
            flex-shrink: 0;
            color: #0095ff;
        }

        .shop-btn {
            background: linear-gradient(135deg, #0095ff 0%, #0066cc 100%);
            color: white;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 149, 255, 0.3);
            display: inline-block;
        }

        .shop-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 149, 255, 0.5);
        }

        .discount-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            z-index: 10;
        }

        .plan-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .toggle-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 50px;
        }

        .toggle-label {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .discount-text {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .coupon-section {
            background: linear-gradient(180deg, #1e3a5f 0%, #0f1f3d 100%);
            border: 2px solid #2d4a6f;
            border-radius: 20px;
        }

        .submit-section {
            background: linear-gradient(180deg, #1e3a5f 0%, #0f1f3d 100%);
            border: 2px dashed #2d4a6f;
            border-radius: 20px;
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

        .dark .subscription-card {
            background: linear-gradient(180deg, #1e3a5f 0%, #0f1f3d 100%);
        }

        @media (max-width: 768px) {
            .price {
                font-size: 2.5rem;
            }

            .hero-title {
                font-size: 2rem;
            }
        }
    </style>
@endsection
@section('content')
    <div class="hero_section my-8 px-4 lg:px-8 max-w-[1200px] mx-auto">
        <h1 class="hero-title">Purchase Your Package</h1>


        @if ($subscription_status == 1)
            <p class="hero-description">You already Subscribed to our <span
                    class="bg-blue-500 px-3 py-1 rounded-full text-xs text-white mx-1">{{ $subscription_type }}</span> Package. You can upgrade or degrade the
                subscription as your need!</p>
        @else
            <p class="hero-description">Choose a subscription package that fits your business needs and continue to grow with
                our
                platform</p>
        @endif

        <div class="toggle-section justify-center mt-8">
            <span class="toggle-label">Yearly</span>
            <div class="discount-text">{{ $yearly_discount }}% OFF</div>
        </div>
    </div>

    <div class="px-4 lg:px-8 max-w-[1200px] mx-auto mb-12">
        <form id="subscriptionForm" action="/manufacturer/purchase-subscription" method="POST">
            @csrf
            <input type="hidden" name="package_type" id="packageType" value="">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <!-- Monthly Package -->
                <div class="subscription-card @if ($package == 'monthly') active @endif" onclick="selectPackage('monthly', this)">
                    <div class="card-header">
                        <h3 class="plan-title">STARTER</h3>
                    </div>
                    <div class="card-icon">
                        <svg class="w-10 h-10 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>

                    <div class="price-section">
                        <span class="price">${{ $monthly_fee_amount }}</span>
                        <span class="period">/Month</span>
                    </div>

                    <div class="feature-list">
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>The point of using lorem offer</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Lorem Ipsum is simply dummy</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Premium Phone Support</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Unlimited Bandwidth</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Lorem Ipsum is simply dummy</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>The point of using lorem</span>
                        </div>
                    </div>

                    <div class="text-center pb-8">
                        <button type="button" class="shop-btn">SHOP NOW</button>
                    </div>
                </div>

                <!-- 6 Monthly Package -->
                <div class="subscription-card @if ($package == '6months') active @endif" onclick="selectPackage('6months', this)">
                    <div class="discount-badge">SAVE {{ $half_yearly_discount }}%</div>
                    <div class="card-header">
                        <h3 class="plan-title">PREMIUM</h3>
                    </div>
                    <div class="card-icon">
                        <svg class="w-10 h-10 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>

                    <div class="price-section">
                        <span class="price">${{ $half_yearly_fee_amount }}</span>
                        <span class="period">/6 Months</span>
                    </div>

                    <div class="feature-list">
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>The point of using lorem offer</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Lorem Ipsum is simply dummy</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Premium Phone Support</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Unlimited Bandwidth</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Lorem Ipsum is simply dummy</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>The point of using lorem</span>
                        </div>
                    </div>

                    <div class="text-center pb-8">
                        <button type="button" class="shop-btn">SHOP NOW</button>
                    </div>
                </div>

                <!-- Yearly Package -->
                <div class="subscription-card @if ($package == 'yearly') active @endif" onclick="selectPackage('yearly', this)">
                    <div class="discount-badge">SAVE {{ $yearly_discount }}%</div>
                    <div class="card-header">
                        <h3 class="plan-title">ULTIMATE</h3>
                    </div>
                    <div class="card-icon">
                        <svg class="w-10 h-10 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                            </path>
                        </svg>
                    </div>

                    <div class="price-section">
                        <span class="price">${{ $yearly_fee_amount }}</span>
                        <span class="period">/Year</span>
                    </div>

                    <div class="feature-list">
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>The point of using lorem offer</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Lorem Ipsum is simply dummy</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Premium Phone Support</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Unlimited Bandwidth</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>Lorem Ipsum is simply dummy</span>
                        </div>
                        <div class="feature-item">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span>The point of using lorem</span>
                        </div>
                    </div>

                    <div class="text-center pb-8">
                        <button type="button" class="shop-btn">SHOP NOW</button>
                    </div>
                </div>
            </div>

            <!-- Coupon Code Section -->
            <div class="coupon-section max-w-2xl mx-auto p-8 mb-10 mt-20">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-white">Have a Coupon Code?</h4>
                        <p class="text-sm text-gray-400">Enter your code to get additional discounts</p>
                    </div>
                </div>
                <div class="grid lg:flex gap-3">
                    <input type="text" id="couponCode" placeholder="Enter your coupon code" name="coupon_code"
                        class="flex-1 px-5 py-3 bg-[#0f1f3d] border-2 border-[#2d4a6f] text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0095ff] focus:border-transparent transition-all w-full lg:w-auto">
                    <button type="button" onclick="applyCoupon()"
                        class="px-8 py-3 bg-gradient-to-r from-[#2d4a6f] to-[#1e3a5f] text-white rounded-xl font-semibold hover:from-[#3d5a7f] hover:to-[#2e4a6d] transition-all w-full lg:w-auto">
                        Apply
                    </button>
                </div>
                <div id="couponMessage" class="mt-4 text-sm font-medium"></div>
            </div>

            <!-- Submit Button Section -->
            <div class="submit-section max-w-2xl mx-auto p-8 text-center mt-8">
                <div id="selectionMessage" class="mb-6">
                    <p class="text-gray-400 text-sm">👆 Please select a subscription package above to continue</p>
                </div>
                <button type="submit" id="submitBtn" disabled
                    class="shop-btn disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    Continue to Payment
                </button>
                <p class="text-xs text-gray-400 mt-4">
                    🔒 Secure payment processing • 30-day money-back guarantee
                </p>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        let selectedPackage = null;

        function selectPackage(packageType, element) {
            // Remove active class from all cards
            document.querySelectorAll('.subscription-card').forEach(card => {
                card.classList.remove('active');
            });

            // Add active class to selected card
            element.classList.add('active');

            // Store selected package
            selectedPackage = packageType;
            document.getElementById('packageType').value = packageType;

            // Enable submit button
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;

            // Update selection message
            const packageNames = {
                'monthly': 'Starter (Monthly)',
                '6months': 'Premium (6 Months)',
                'yearly': 'Ultimate (Yearly)'
            };

            document.getElementById('selectionMessage').innerHTML = `
                <div class="flex items-center justify-center gap-3 text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold">Selected: ${packageNames[packageType]}</span>
                </div>
            `;
        }



        function applyCoupon() {
            const couponCode = $('#couponCode').val().trim();
            const messageDiv = $('#couponMessage');

            if (!couponCode) {
                messageDiv.html(`
                    <div class="flex items-center gap-2 text-red-400 bg-red-900/20 px-4 py-3 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Please enter a valid coupon code</span>
                    </div>
                `);
                return;
            }

            $.ajax({
                url: '/manufacturer/check-coupon-code',
                method: 'POST',
                data: {
                    coupon_code: couponCode,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.type == 'success') {
                        messageDiv.html(`
                            <div class="flex items-center gap-2 text-green-400 bg-green-900/20 px-4 py-3 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Coupon code "<strong>${couponCode}</strong>" has been applied successfully!</span>
                            </div>
                        `);
                    } else {
                        messageDiv.html(`
                            <div class="flex items-center gap-2 text-red-400 bg-red-900/20 px-4 py-3 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Invalid or expired coupon code</span>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    messageDiv.html(`
                        <div class="flex items-center gap-2 text-red-400 bg-red-900/20 px-4 py-3 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Something went wrong while applying the coupon. Please try again.</span>
                        </div>
                    `);
                }
            });
        }

        document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
            if (!selectedPackage) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "Please select a subscription package before continuing",
                    timer: 4000,
                    showConfirmButton: true
                });
            }
        });

        document.getElementById('couponCode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                applyCoupon();
            }
        });
    </script>
@endsection
