@extends('layouts.surface.app')

@section('title', 'Choose A Subscription Package')

@section('style')
    <link rel="stylesheet" href="/assets/css/packages.css">
@endsection

@section('content')
    <div class="hero_section my-8 px-4 lg:px-8 max-w-[1200px] mx-auto">
        <h1 class="hero-title">Purchase Your Package</h1>

        @if ($subscription_status == 1)
            <p class="hero-description">You already Subscribed to our <span
                    class="bg-blue-500 px-3 py-1 rounded-full text-xs text-white mx-1">{{ $subscription_type }}</span>
                Package. You can upgrade or degrade the
                subscription as your need!</p>
        @else
            <p class="hero-description">Choose a subscription package that fits your business needs and continue to grow with
                our
                platform</p>
        @endif

        <div class="mx-auto justify-center flex gap-2 mt-8 mb-12">
            <div class="@if ($currency == 'usd') choosed_currency @else unchoosed_currency @endif currency" onclick="chooseCurrency(this)" data-id="usd">USD</div>
            <div class="@if ($currency == 'krw') choosed_currency @else unchoosed_currency @endif currency" onclick="chooseCurrency(this)" data-id="krw">KRW</div>
        </div>
    </div>

    <div class="px-4 lg:px-8 max-w-[1200px] mx-auto mb-12">
        <form id="subscriptionForm" action="/manufacturer/purchase-subscription" method="POST">
            @csrf
            <input type="hidden" name="package_type" id="packageType" value="">
            <input type="hidden" name="choosed_currency" id="choosedCurrency" value="{{ $currency }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <!-- Starter Package (Monthly) -->
                <div class="subscription-card @if ($package == 'monthly') active @endif"
                    onclick="selectPackage('monthly', this)">
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
                        @php
                            $monthly_usd = $currency == 'usd' ? $monthly_fee_amount : round($monthly_fee_amount / $exchange_rate, 2);
                            $monthly_krw = $currency == 'krw' ? $monthly_fee_amount : round($monthly_fee_amount * $exchange_rate);
                        @endphp
                        <span class="price monthly_amount" data-usd="{{ $monthly_usd }}" data-krw="{{ $monthly_krw }}">
                            @if($currency == 'usd')
                                ${{ number_format($monthly_usd, 2) }}
                            @else
                                ₩{{ number_format($monthly_krw, 0) }}
                            @endif
                        </span>
                        <span class="period">/Month</span>
                    </div>

                    <div class="feature-list">
                        @php
                            $starterServices = $services->where('package_of', 'starter');
                        @endphp
                        
                        @if($starterServices->count() > 0)
                            @foreach($starterServices as $service)
                                <div class="feature-item {{ $service->service_available ? '' : '' }}">
                                    @if($service->service_available)
                                        <svg class="w-5 h-5 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                    <span class="{{ $service->service_available ? '' : 'text-gray-300' }}">{{ $service->service_name }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="feature-item">
                                <svg class="w-5 h-5 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Basic Features Included</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-center pb-8">
                        <button type="button" class="shop-btn">SHOP NOW</button>
                    </div>
                </div>

                <!-- Premium Package (6 Months) -->
                <div class="subscription-card @if ($package == '6months') active @endif"
                    onclick="selectPackage('6months', this)">
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
                        @php
                            $half_yearly_usd = $currency == 'usd' ? $half_yearly_fee_amount : round($half_yearly_fee_amount / $exchange_rate, 2);
                            $half_yearly_krw = $currency == 'krw' ? $half_yearly_fee_amount : round($half_yearly_fee_amount * $exchange_rate);
                        @endphp
                        <span class="price half_yearly_amount" data-usd="{{ $half_yearly_usd }}" data-krw="{{ $half_yearly_krw }}">
                            @if($currency == 'usd')
                                ${{ number_format($half_yearly_usd, 2) }}
                            @else
                                ₩{{ number_format($half_yearly_krw, 0) }}
                            @endif
                        </span>
                        <span class="period">/6 Months</span>
                    </div>

                    <div class="feature-list">
                        @php
                            $premiumServices = $services->where('package_of', 'premium');
                        @endphp
                        
                        @if($premiumServices->count() > 0)
                            @foreach($premiumServices as $service)
                                <div class="feature-item {{ $service->service_available ? '' : '' }}">
                                    @if($service->service_available)
                                        <svg class="w-5 h-5 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                    <span class="{{ $service->service_available ? '' : 'text-gray-300' }}">{{ $service->service_name }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="feature-item">
                                <svg class="w-5 h-5 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Premium Features Included</span>
                            </div>
                        @endif
                    </div>

                    <div class="text-center pb-8">
                        <button type="button" class="shop-btn">SHOP NOW</button>
                    </div>
                </div>

                <!-- Ultimate Package (Yearly) -->
                <div class="subscription-card @if ($package == 'yearly') active @endif"
                    onclick="selectPackage('yearly', this)">
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
                        @php
                            $yearly_usd = $currency == 'usd' ? $yearly_fee_amount : round($yearly_fee_amount / $exchange_rate, 2);
                            $yearly_krw = $currency == 'krw' ? $yearly_fee_amount : round($yearly_fee_amount * $exchange_rate);
                        @endphp
                        <span class="price yearly_amount" data-usd="{{ $yearly_usd }}" data-krw="{{ $yearly_krw }}">
                            @if($currency == 'usd')
                                ${{ number_format($yearly_usd, 2) }}
                            @else
                                ₩{{ number_format($yearly_krw, 0) }}
                            @endif
                        </span>
                        <span class="period">/Year</span>
                    </div>

                    <div class="feature-list">
                        @php
                            $ultimateServices = $services->where('package_of', 'ultimate');
                        @endphp
                        
                        @if($ultimateServices->count() > 0)
                            @foreach($ultimateServices as $service)
                                <div class="feature-item {{ $service->service_available ? '' : '' }}">
                                    @if($service->service_available)
                                        <svg class="w-5 h-5 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                    <span class="{{ $service->service_available ? '' : 'text-gray-300' }}">{{ $service->service_name }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="feature-item">
                                <svg class="w-5 h-5 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Ultimate Features Included</span>
                            </div>
                        @endif
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
        const exchangeRate = {{ $exchange_rate }};
        let currentCurrency = '{{ $currency }}';

        function selectPackage(packageType, element) {
            document.querySelectorAll('.subscription-card').forEach(card => {
                card.classList.remove('active');
            });

            element.classList.add('active');
            selectedPackage = packageType;
            document.getElementById('packageType').value = packageType;

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = false;

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

        function chooseCurrency(element) {
            const currency = element.getAttribute('data-id');
            
            $(".currency").removeClass("choosed_currency").addClass("unchoosed_currency");
            $(element).removeClass("unchoosed_currency").addClass("choosed_currency");
            
            currentCurrency = currency;
            document.getElementById('choosedCurrency').value = currency;
            
            updatePrices(currency);
        }

        function updatePrices(currency) {
            const currencySymbol = currency === 'usd' ? '$' : '₩';
            
            const monthlyElement = document.querySelector('.monthly_amount');
            const monthlyAmount = monthlyElement.getAttribute(`data-${currency}`);
            monthlyElement.textContent = `${currencySymbol}${formatAmount(monthlyAmount, currency)}`;
            
            const halfYearlyElement = document.querySelector('.half_yearly_amount');
            const halfYearlyAmount = halfYearlyElement.getAttribute(`data-${currency}`);
            halfYearlyElement.textContent = `${currencySymbol}${formatAmount(halfYearlyAmount, currency)}`;
            
            const yearlyElement = document.querySelector('.yearly_amount');
            const yearlyAmount = yearlyElement.getAttribute(`data-${currency}`);
            yearlyElement.textContent = `${currencySymbol}${formatAmount(yearlyAmount, currency)}`;
        }

        function formatAmount(amount, currency) {
            const numAmount = parseFloat(amount);
            if (currency === 'krw') {
                return Math.round(numAmount).toLocaleString('en-US');
            } else {
                return numAmount.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
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