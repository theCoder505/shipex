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
                Package. You can upgrade or downgrade the subscription as your need!</p>
        @else
            <p class="hero-description">Choose a subscription package that fits your business needs and continue to grow
                with our platform</p>
        @endif

        <div class="mx-auto justify-center flex gap-2 mt-8 mb-12">
            <div class="@if ($currency == 'usd') choosed_currency @else unchoosed_currency @endif currency"
                onclick="chooseCurrency(this)" data-id="usd">USD</div>
            <div class="@if ($currency == 'krw') choosed_currency @else unchoosed_currency @endif currency"
                onclick="chooseCurrency(this)" data-id="krw">KRW</div>
        </div>
    </div>

    <div class="px-4 lg:px-8 max-w-[1200px] mx-auto mb-12">
        <form id="subscriptionForm" action="/manufacturer/purchase-subscription" method="POST">
            @csrf
            <input type="hidden" name="package_type" id="packageType" value="">
            <input type="hidden" name="choosed_currency" id="choosedCurrency" value="{{ $currency }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

                {{-- Starter (Monthly) --}}
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
                            @if ($currency == 'usd') ${{ number_format($monthly_usd, 2) }}
                            @else ₩{{ number_format($monthly_krw, 0) }} @endif
                        </span>
                        <span class="period">/Month</span>
                    </div>
                    <div class="feature-list">
                        @php $starterServices = $services->where('package_of', 'starter'); @endphp
                        @if ($starterServices->count() > 0)
                            @foreach ($starterServices as $service)
                                <div class="feature-item">
                                    @if ($service->service_available)
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

                {{-- Premium (6 Months) --}}
                <div class="subscription-card @if ($package == '6months') active @endif"
                    onclick="selectPackage('6months', this)">
                    <div class="discount-badge">SAVE {{ $half_yearly_discount }}%</div>
                    <div class="card-header">
                        <h3 class="plan-title">PREMIUM</h3>
                    </div>
                    <div class="card-icon">
                        <svg class="w-10 h-10 text-[#0095ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="price-section">
                        @php
                            $half_yearly_usd = $currency == 'usd' ? $half_yearly_fee_amount : round($half_yearly_fee_amount / $exchange_rate, 2);
                            $half_yearly_krw = $currency == 'krw' ? $half_yearly_fee_amount : round($half_yearly_fee_amount * $exchange_rate);
                        @endphp
                        <span class="price half_yearly_amount" data-usd="{{ $half_yearly_usd }}" data-krw="{{ $half_yearly_krw }}">
                            @if ($currency == 'usd') ${{ number_format($half_yearly_usd, 2) }}
                            @else ₩{{ number_format($half_yearly_krw, 0) }} @endif
                        </span>
                        <span class="period">/6 Months</span>
                    </div>
                    <div class="feature-list">
                        @php $premiumServices = $services->where('package_of', 'premium'); @endphp
                        @if ($premiumServices->count() > 0)
                            @foreach ($premiumServices as $service)
                                <div class="feature-item">
                                    @if ($service->service_available)
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

                {{-- Ultimate (Yearly) --}}
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
                            @if ($currency == 'usd') ${{ number_format($yearly_usd, 2) }}
                            @else ₩{{ number_format($yearly_krw, 0) }} @endif
                        </span>
                        <span class="period">/Year</span>
                    </div>
                    <div class="feature-list">
                        @php $ultimateServices = $services->where('package_of', 'ultimate'); @endphp
                        @if ($ultimateServices->count() > 0)
                            @foreach ($ultimateServices as $service)
                                <div class="feature-item">
                                    @if ($service->service_available)
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

            {{-- Coupon Code --}}
            <div class="coupon-section max-w-2xl mx-auto p-8 mb-10 mt-20">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center">
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

            {{-- Payment Method & Submit --}}
            <div class="submit-section max-w-2xl mx-auto p-8 text-center mt-8">
                <div id="selectionMessage" class="mb-6">
                    <p class="text-gray-400 text-sm">👆 Please select a subscription package above to continue</p>
                </div>

                <p class="text-gray-400 text-xs uppercase tracking-widest mb-4 font-semibold">Choose Payment Method</p>

                {{-- Payment note banners --}}
                <div id="note-paypal" class="text-xs text-blue-300 bg-blue-900/20 border border-blue-800/40 rounded-lg px-4 py-2 mb-4 hidden">
                    💳 PayPal supports <strong>USD</strong> only. KRW will be auto-converted.
                </div>
                <div id="note-toss" class="text-xs text-blue-300 bg-blue-900/20 border border-blue-800/40 rounded-lg px-4 py-2 mb-4 hidden">
                    💳 TOSS Payments supports <strong>KRW only</strong>. Currency will switch to KRW automatically.
                </div>
                <div id="note-stripe" class="text-xs text-purple-300 bg-purple-900/20 border border-purple-800/40 rounded-lg px-4 py-2 mb-4 hidden">
                    💳 Stripe supports <strong>USD only</strong>. Currency will switch to USD automatically.
                </div>

                <div class="flex gap-4 justify-center mb-6 flex-wrap">

                    {{-- PayPal --}}
                    <label for="paypal" class="payment-option-card" id="paypal-card">
                        <input type="radio" name="payment_option" id="paypal" value="paypal" checked
                            class="sr-only" onchange="onPaymentChange('paypal')">
                        <div class="payment-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="#009cde">
                                <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.59 3.025-2.566 6.082-8.558 6.082H9.825l-1.273 8.05h3.98c.46 0 .85-.334.922-.789l.038-.197.733-4.64.047-.257a.932.932 0 0 1 .921-.789h.58c3.757 0 6.698-1.527 7.554-5.945.359-1.845.172-3.386-.705-4.228z"/>
                            </svg>
                        </div>
                        <span class="payment-label">PayPal</span>
                        <div class="payment-check">✓</div>
                    </label>

                    {{-- TOSS --}}
                    <label for="toss" class="payment-option-card" id="toss-card">
                        <input type="radio" name="payment_option" id="toss" value="toss" class="sr-only"
                            onchange="onPaymentChange('toss')">
                        <div class="payment-icon">
                            <img src="/assets/images/toss.png" alt="" class="w-6 h-6">
                        </div>
                        <span class="payment-label">TOSS</span>
                        <div class="payment-check">✓</div>
                    </label>

                    {{-- Stripe --}}
                    <label for="stripe" class="payment-option-card" id="stripe-card">
                        <input type="radio" name="payment_option" id="stripe" value="stripe" class="sr-only"
                            onchange="onPaymentChange('stripe')">
                        <div class="payment-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="#635BFF">
                                <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/>
                            </svg>
                        </div>
                        <span class="payment-label">Stripe</span>
                        <div class="payment-check">✓</div>
                    </label>
                </div>

                <button type="submit" id="submitBtn" disabled
                    class="shop-btn disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    Continue to Payment
                </button>
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
            document.querySelectorAll('.subscription-card').forEach(c => c.classList.remove('active'));
            element.classList.add('active');
            selectedPackage = packageType;
            document.getElementById('packageType').value = packageType;
            document.getElementById('submitBtn').disabled = false;

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
            const sym = currency === 'usd' ? '$' : '₩';
            ['monthly_amount', 'half_yearly_amount', 'yearly_amount'].forEach(cls => {
                const el = document.querySelector('.' + cls);
                if (el) el.textContent = sym + formatAmount(el.getAttribute('data-' + currency), currency);
            });
        }

        function formatAmount(amount, currency) {
            const n = parseFloat(amount);
            return currency === 'krw'
                ? Math.round(n).toLocaleString('en-US')
                : n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function onPaymentChange(method) {
            // Update card styles
            ['paypal-card', 'toss-card', 'stripe-card'].forEach(id => {
                document.getElementById(id).classList.remove('selected');
            });
            document.getElementById(method + '-card').classList.add('selected');

            // Show relevant note
            document.getElementById('note-paypal').classList.add('hidden');
            document.getElementById('note-toss').classList.add('hidden');
            document.getElementById('note-stripe').classList.add('hidden');
            document.getElementById('note-' + method).classList.remove('hidden');

            // Auto-switch currency for TOSS (KRW) and Stripe (USD)
            if (method === 'toss' && currentCurrency !== 'krw') {
                const krwEl = document.querySelector('.currency[data-id="krw"]');
                if (krwEl) chooseCurrency(krwEl);
            }
            if (method === 'stripe' && currentCurrency !== 'usd') {
                const usdEl = document.querySelector('.currency[data-id="usd"]');
                if (usdEl) chooseCurrency(usdEl);
            }
        }

        function applyCoupon() {
            const couponCode = $('#couponCode').val().trim();
            const messageDiv = $('#couponMessage');
            if (!couponCode) {
                messageDiv.html(`<div class="flex items-center gap-2 text-red-400 bg-red-900/20 px-4 py-3 rounded-lg"><span>Please enter a valid coupon code</span></div>`);
                return;
            }
            $.ajax({
                url: '/manufacturer/check-coupon-code',
                method: 'POST',
                data: { coupon_code: couponCode, _token: '{{ csrf_token() }}' },
                success: function (response) {
                    if (response.type == 'success') {
                        messageDiv.html(`<div class="flex items-center gap-2 text-green-400 bg-green-900/20 px-4 py-3 rounded-lg"><span>Coupon "<strong>${couponCode}</strong>" applied!</span></div>`);
                    } else {
                        messageDiv.html(`<div class="flex items-center gap-2 text-red-400 bg-red-900/20 px-4 py-3 rounded-lg"><span>Invalid or expired coupon code</span></div>`);
                    }
                },
                error: function () {
                    messageDiv.html(`<div class="flex items-center gap-2 text-red-400 bg-red-900/20 px-4 py-3 rounded-lg"><span>Something went wrong. Please try again.</span></div>`);
                }
            });
        }

        document.getElementById('subscriptionForm').addEventListener('submit', function (e) {
            if (!selectedPackage) {
                e.preventDefault();
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Please select a subscription package before continuing', timer: 4000, showConfirmButton: true });
            }
        });

        document.getElementById('couponCode').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); applyCoupon(); }
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('paypal-card').classList.add('selected');
            document.getElementById('note-paypal').classList.remove('hidden');
        });
    </script>
@endsection