@extends('layouts.surface.app')

@section('title', 'Checkout – Complete Your Subscription')

@section('style')
    <style>
        body {
            background: linear-gradient(180deg, #0a1628 0%, #1a2332 100%);
            min-height: 100vh;
        }

        .hero-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 12px;
        }

        .hero-description {
            color: #8b9dc3;
            font-size: 1rem;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        .method-tabs { display: flex; gap: 12px; margin-bottom: 28px; flex-wrap: wrap; }

        .method-tab {
            flex: 1;
            min-width: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 20px;
            border-radius: 14px;
            border: 2px solid #2d4a6f;
            background: #0f1f3d;
            cursor: pointer;
            transition: all 0.25s ease;
            color: #8b9dc3;
            font-weight: 600;
            font-size: 0.95rem;
            position: relative;
            user-select: none;
        }

        .method-tab:hover { border-color: rgba(0,149,255,.45); background: #152b50; color: #fff; }

        .method-tab.active {
            border-color: #0095ff;
            background: linear-gradient(135deg, #1a3a6f 0%, #0f2244 100%);
            color: #fff;
            box-shadow: 0 0 20px rgba(0,149,255,.25);
        }

        .method-tab .tab-check {
            position: absolute; top: -8px; right: -8px;
            width: 22px; height: 22px;
            background: #0095ff;
            border-radius: 50%;
            font-size: 0.65rem; color: #fff;
            display: none; align-items: center; justify-content: center;
            font-weight: 800;
            box-shadow: 0 2px 8px rgba(0,149,255,.5);
        }
        .method-tab.active .tab-check { display: flex; }

        .method-icon {
            width: 38px; height: 38px;
            background: #fff; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 3px 8px rgba(0,0,0,.2);
        }

        .payment-card {
            background: linear-gradient(180deg, #1e3a5f 0%, #0f1f3d 100%);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 2px solid #2d4a6f;
        }

        .gateway-panel { display: none; }
        .gateway-panel.visible { display: block; }

        .paypal-badge {
            background: linear-gradient(135deg, #003087 0%, #009cde 100%);
            border-radius: 12px; text-align: center; padding: 20px; margin-bottom: 24px;
        }

        .toss-badge {
            background: linear-gradient(135deg, #0064ff 0%, #00b0f0 100%);
            border-radius: 12px; text-align: center; padding: 20px; margin-bottom: 24px;
        }

        .stripe-badge {
            background: linear-gradient(135deg, #635BFF 0%, #7C73FF 100%);
            border-radius: 12px; text-align: center; padding: 20px; margin-bottom: 24px;
        }

        .form-input {
            width: 100%; background: #0a1628; color: #fff;
            padding: 11px 14px; border: 1.5px solid #2d4a6f;
            border-radius: 10px; outline: none; font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-input:focus { border-color: #0095ff; }

        .form-label {
            display: block; font-size: 0.82rem; font-weight: 600;
            color: #8b9dc3; margin-bottom: 6px;
            text-transform: uppercase; letter-spacing: 0.4px;
        }

        .pay-btn {
            width: 100%; padding: 14px 24px;
            border-radius: 12px; font-size: 1rem; font-weight: 700;
            cursor: pointer; border: none; transition: all 0.3s ease; letter-spacing: 0.3px;
        }

        .pay-btn-paypal {
            background: linear-gradient(135deg, #0070ba 0%, #003087 100%);
            color: #fff; box-shadow: 0 6px 20px rgba(0,112,186,.4);
        }
        .pay-btn-paypal:hover:not(:disabled) {
            background: linear-gradient(135deg, #005ea6 0%, #002070 100%);
            transform: translateY(-1px);
        }

        .pay-btn-toss {
            background: linear-gradient(135deg, #0064ff 0%, #0040cc 100%);
            color: #fff; box-shadow: 0 6px 20px rgba(0,100,255,.4);
        }
        .pay-btn-toss:hover:not(:disabled) {
            background: linear-gradient(135deg, #0050dd 0%, #0030aa 100%);
            transform: translateY(-1px);
        }

        .pay-btn-stripe {
            background: linear-gradient(135deg, #635BFF 0%, #4B44E8 100%);
            color: #fff; box-shadow: 0 6px 20px rgba(99,91,255,.4);
        }
        .pay-btn-stripe:hover:not(:disabled) {
            background: linear-gradient(135deg, #5248f0 0%, #3b34d0 100%);
            transform: translateY(-1px);
        }

        .pay-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none !important; }

        .info-banner {
            border-radius: 10px; padding: 14px 16px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 12px;
        }
        .info-banner-blue  { background: rgba(0,149,255,.08); border: 1px solid rgba(0,149,255,.3); }
        .info-banner-teal  { background: rgba(20,184,166,.08); border: 1px solid rgba(20,184,166,.3); }
        .info-banner-purple { background: rgba(99,91,255,.08); border: 1px solid rgba(99,91,255,.3); }

        .spinner {
            width: 36px; height: 36px;
            border: 3px solid #e5e7eb; border-top-color: #0070ba;
            border-radius: 50%; animation: spin .8s linear infinite;
            margin: 0 auto 16px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .summary-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,.07);
            font-size: 0.9rem; color: #8b9dc3;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-row.total { color: #fff; font-weight: 700; font-size: 1.1rem; padding-top: 14px; }
        .summary-row.total span:last-child { color: #0095ff; font-size: 1.25rem; }
    </style>
@endsection

@section('content')
    <div class="my-8 px-4 lg:px-8 max-w-[1100px] mx-auto">

        <h1 class="hero-title">Complete Your Subscription</h1>
        <p class="hero-description">
            You've selected the <span class="text-white font-semibold capitalize">{{ $package_display }}</span> package.
            @if ($type == 'fixed')
                A @if($choosed_currency == 'krw') ₩{{ number_format($discount, 0) }} @else ${{ number_format($discount, 2) }} @endif fixed discount has been applied.
            @elseif($type == 'percentage')
                A {{ $discount }}% discount has been applied.
            @endif
        </p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-6">

            {{-- ── Left: Form ──────────────────────────────────────────────── --}}
            <div class="lg:col-span-2">
                <div class="payment-card p-8">

                    <p class="text-xs uppercase tracking-widest text-gray-400 font-semibold mb-4">Payment Method</p>

                    <div class="method-tabs">
                        {{-- PayPal tab --}}
                        <div class="method-tab {{ $payment_option === 'paypal' ? 'active' : '' }}"
                             id="tab-paypal" onclick="switchMethod('paypal')">
                            <div class="method-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="#009cde">
                                    <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106zm14.146-14.42a3.35 3.35 0 0 0-.607-.541c-.013.076-.026.175-.041.254-.59 3.025-2.566 6.082-8.558 6.082H9.825l-1.273 8.05h3.98c.46 0 .85-.334.922-.789l.038-.197.733-4.64.047-.257a.932.932 0 0 1 .921-.789h.58c3.757 0 6.698-1.527 7.554-5.945.359-1.845.172-3.386-.705-4.228z"/>
                                </svg>
                            </div>
                            <span>PayPal</span>
                            <div class="tab-check">✓</div>
                        </div>

                        {{-- TOSS tab --}}
                        <div class="method-tab {{ $payment_option === 'toss' ? 'active' : '' }}"
                             id="tab-toss" onclick="switchMethod('toss')">
                            <div class="method-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-5 h-5">
                                    <rect width="48" height="48" rx="10" fill="#0064FF"/>
                                    <text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle"
                                          fill="white" font-size="16" font-weight="800" font-family="Arial">T</text>
                                </svg>
                            </div>
                            <span>TOSS</span>
                            <div class="tab-check">✓</div>
                        </div>

                        {{-- Stripe tab --}}
                        <div class="method-tab {{ $payment_option === 'stripe' ? 'active' : '' }}"
                             id="tab-stripe" onclick="switchMethod('stripe')">
                            <div class="method-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="#635BFF">
                                    <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/>
                                </svg>
                            </div>
                            <span>Stripe</span>
                            <div class="tab-check">✓</div>
                        </div>
                    </div>

                    {{-- ── PayPal Panel ──────────────────────────────────── --}}
                    <div id="panel-paypal" class="gateway-panel {{ $payment_option === 'paypal' ? 'visible' : '' }}">
                        <div class="paypal-badge mb-6">
                            <p class="text-white font-semibold text-sm uppercase tracking-wide">🔒 Secure Payment via PayPal</p>
                        </div>

                        @if($choosed_currency == 'krw')
                            <div class="info-banner info-banner-teal mb-5">
                                <svg class="w-5 h-5 text-teal-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-teal-200 text-sm font-semibold">Currency Conversion</p>
                                    <p class="text-teal-300 text-xs mt-0.5">PayPal processes in USD. ₩{{ number_format($amount_display, 0) }} ≈ ${{ number_format($amount_usd, 2) }} USD (rate: {{ number_format($exchange_rate, 2) }} KRW/USD)</p>
                                </div>
                            </div>
                        @else
                            <div class="info-banner info-banner-blue mb-5">
                                <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-blue-200 text-sm font-semibold">USD Payment</p>
                                    <p class="text-blue-300 text-xs mt-0.5">${{ number_format($amount_display, 2) }} will be charged in USD via PayPal.</p>
                                </div>
                            </div>
                        @endif

                        <form id="form-paypal" class="space-y-5">
                            @csrf
                            <input type="hidden" name="package_type"     value="{{ $package }}">
                            <input type="hidden" name="amount_display"   value="{{ $amount_display }}">
                            <input type="hidden" name="amount_usd"       value="{{ $amount_usd }}">
                            <input type="hidden" name="choosed_currency" value="{{ $choosed_currency }}">
                            <input type="hidden" name="coupon_code"      value="{{ request('coupon_code') ?? '' }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" id="paypal_full_name" name="full_name" placeholder="John Doe" required class="form-input">
                                    <p class="text-red-400 text-xs mt-1 hidden" id="err-paypal-name">Please enter your full name.</p>
                                </div>
                                <div>
                                    <label class="form-label">Email *</label>
                                    <input type="email" id="paypal_email" name="email" placeholder="john@example.com" required class="form-input">
                                    <p class="text-red-400 text-xs mt-1 hidden" id="err-paypal-email">Please enter a valid email.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 py-4">
                                <input type="checkbox" id="paypal_terms" class="rounded mt-0.5 cursor-pointer">
                                <label for="paypal_terms" class="text-gray-300 text-sm cursor-pointer">
                                    I agree to the <a href="/terms-of-use" class="text-[#0095ff] hover:underline">Terms of Use</a> and authorise this one-time payment.
                                </label>
                            </div>
                            <p class="text-red-400 text-xs hidden" id="err-paypal-terms">You must accept the terms.</p>

                            <button type="submit" id="btn-paypal" class="pay-btn pay-btn-paypal mt-2">
                                Pay with PayPal –
                                @if($choosed_currency == 'krw') ₩{{ number_format($amount_display, 0) }}
                                @else ${{ number_format($amount_display, 2) }} @endif
                            </button>
                        </form>
                    </div>

                    {{-- ── TOSS Panel ────────────────────────────────────── --}}
                    <div id="panel-toss" class="gateway-panel {{ $payment_option === 'toss' ? 'visible' : '' }}">
                        <div class="toss-badge mb-6">
                            <p class="text-white font-semibold text-sm uppercase tracking-wide">🔒 Secure Payment via TOSS Payments</p>
                        </div>

                        <div class="info-banner info-banner-blue mb-5">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-blue-200 text-sm font-semibold">Korean Won (KRW) Payment</p>
                                <p class="text-blue-300 text-xs mt-0.5">TOSS Payments processes in KRW. You will be charged ₩{{ number_format($amount_display, 0) }}. You will be redirected to TOSS checkout.</p>
                            </div>
                        </div>

                        <form id="form-toss" class="space-y-5">
                            @csrf
                            <input type="hidden" name="package_type"     value="{{ $package }}">
                            <input type="hidden" name="amount_display"   value="{{ $amount_display }}">
                            <input type="hidden" name="choosed_currency" value="{{ $choosed_currency }}">
                            <input type="hidden" name="coupon_code"      value="{{ request('coupon_code') ?? '' }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" id="toss_full_name" name="full_name" placeholder="Hong Yong" required class="form-input">
                                    <p class="text-red-400 text-xs mt-1 hidden" id="err-toss-name">Please enter your full name.</p>
                                </div>
                                <div>
                                    <label class="form-label">Email *</label>
                                    <input type="email" id="toss_email" name="email" placeholder="hong@example.com" required class="form-input">
                                    <p class="text-red-400 text-xs mt-1 hidden" id="err-toss-email">Please enter a valid email.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 py-4">
                                <input type="checkbox" id="toss_terms" class="rounded mt-0.5 cursor-pointer">
                                <label for="toss_terms" class="text-gray-300 text-sm cursor-pointer">
                                    I agree to the <a href="/terms-of-use" class="text-[#0095ff] hover:underline">Terms of Use</a> and authorise this payment.
                                </label>
                            </div>
                            <p class="text-red-400 text-xs hidden" id="err-toss-terms">You must accept the terms.</p>

                            <button type="submit" id="btn-toss" class="pay-btn pay-btn-toss mt-2">
                                Pay with TOSS – ₩{{ number_format($amount_display, 0) }}
                            </button>
                        </form>
                    </div>

                    {{-- ── Stripe Panel ──────────────────────────────────── --}}
                    <div id="panel-stripe" class="gateway-panel {{ $payment_option === 'stripe' ? 'visible' : '' }}">
                        <div class="stripe-badge mb-6">
                            <p class="text-white font-semibold text-sm uppercase tracking-wide">🔒 Secure Payment via Stripe</p>
                        </div>

                        <div class="info-banner info-banner-purple mb-5">
                            <svg class="w-5 h-5 text-purple-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-purple-200 text-sm font-semibold">USD Card Payment</p>
                                <p class="text-purple-300 text-xs mt-0.5">
                                    Stripe processes in USD. You will be charged ${{ number_format($amount_usd, 2) }}.
                                    You'll be redirected to Stripe's secure checkout page. Accepts all major cards.
                                </p>
                            </div>
                        </div>

                        <form id="form-stripe" class="space-y-5">
                            @csrf
                            <input type="hidden" name="package_type"     value="{{ $package }}">
                            <input type="hidden" name="amount_display"   value="{{ $amount_display }}">
                            <input type="hidden" name="amount_usd"       value="{{ $amount_usd }}">
                            <input type="hidden" name="choosed_currency" value="{{ $choosed_currency }}">
                            <input type="hidden" name="coupon_code"      value="{{ request('coupon_code') ?? '' }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" id="stripe_full_name" name="full_name" placeholder="John Doe" required class="form-input">
                                    <p class="text-red-400 text-xs mt-1 hidden" id="err-stripe-name">Please enter your full name.</p>
                                </div>
                                <div>
                                    <label class="form-label">Email *</label>
                                    <input type="email" id="stripe_email" name="email" placeholder="john@example.com" required class="form-input">
                                    <p class="text-red-400 text-xs mt-1 hidden" id="err-stripe-email">Please enter a valid email.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 py-4">
                                <input type="checkbox" id="stripe_terms" class="rounded mt-0.5 cursor-pointer">
                                <label for="stripe_terms" class="text-gray-300 text-sm cursor-pointer">
                                    I agree to the <a href="/terms-of-use" class="text-[#0095ff] hover:underline">Terms of Use</a> and authorise this one-time payment.
                                </label>
                            </div>
                            <p class="text-red-400 text-xs hidden" id="err-stripe-terms">You must accept the terms.</p>

                            <div class="flex items-center gap-3 mt-2 text-gray-400 text-xs">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <span>Accepts Visa, Mastercard, Amex, and other major cards. PCI-DSS compliant.</span>
                            </div>

                            <button type="submit" id="btn-stripe" class="pay-btn pay-btn-stripe mt-2">
                                Pay with Stripe – ${{ number_format($amount_usd, 2) }}
                            </button>
                        </form>
                    </div>

                </div>{{-- /payment-card --}}

                <p class="text-center text-xs text-gray-500 mt-8">
                    🔒 All payments are encrypted and securely processed. &nbsp;·&nbsp; 30-day money-back guarantee.
                </p>
            </div>

            {{-- ── Right: Order Summary ─────────────────────────────────── --}}
            <div>
                <div class="payment-card p-6">
                    <h3 class="text-white font-bold text-lg mb-4">Order Summary</h3>

                    <div class="summary-row">
                        <span>Package</span>
                        <span class="text-white font-semibold capitalize">{{ $package_display }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Currency</span>
                        <span class="text-white font-semibold uppercase">{{ $choosed_currency }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Payment via</span>
                        <span class="text-white font-semibold capitalize" id="summary-method">{{ ucfirst($payment_option) }}</span>
                    </div>

                    @if($type === 'fixed' || $type === 'percentage')
                        <div class="summary-row">
                            <span>Discount</span>
                            <span class="text-green-400 font-semibold">
                                @if($type == 'fixed')
                                    @if($choosed_currency == 'krw') −₩{{ number_format($discount, 0) }}
                                    @else −${{ number_format($discount, 2) }} @endif
                                @else
                                    −{{ $discount }}%
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="summary-row total">
                        <span>Total Due</span>
                        <span>
                            @if($choosed_currency == 'krw') ₩{{ number_format($amount_display, 0) }}
                            @else ${{ number_format($amount_display, 2) }} @endif
                        </span>
                    </div>

                    @if($choosed_currency == 'krw')
                        <p class="text-xs text-gray-500 mt-4">PayPal / Stripe equivalent: ${{ number_format($amount_usd, 2) }} USD</p>
                    @endif
                </div>

                {{-- Gateway info cards --}}
                <div class="payment-card p-5 mt-4">
                    <h4 class="text-white font-semibold text-sm mb-3">Gateway Info</h4>
                    <div id="gw-paypal-info" class="text-xs text-gray-400 space-y-1">
                        <p>✅ PayPal — USD only, global coverage</p>
                        <p>🔒 Redirect to PayPal secure checkout</p>
                    </div>
                    <div id="gw-toss-info" class="text-xs text-gray-400 space-y-1 hidden">
                        <p>✅ TOSS — KRW only, Korean cards</p>
                        <p>🔒 Redirect to TOSS secure checkout</p>
                    </div>
                    <div id="gw-stripe-info" class="text-xs text-gray-400 space-y-1 hidden">
                        <p>✅ Stripe — USD, all major cards</p>
                        <p>🔒 Redirect to Stripe secure checkout</p>
                        <p>💳 Visa, MC, Amex, and more</p>
                    </div>
                </div>
            </div>

        </div>{{-- /grid --}}
    </div>

    {{-- Processing Modal --}}
    <div id="processingModal" class="fixed inset-0 bg-black/60 items-center justify-center hidden z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl px-8 py-10 w-full max-w-sm mx-4 text-center shadow-2xl">
            <div class="spinner mb-4" id="modal-spinner"></div>
            <h3 class="font-bold text-gray-900 text-lg" id="modal-title">Redirecting…</h3>
            <p class="text-gray-500 text-sm mt-2" id="modal-message">Please wait while we redirect you to the payment gateway.</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.tosspayments.com/v1/payment"></script>

    <script>
        const TOSS_CLIENT_KEY  = '{{ $toss_client_key }}';
        const AMOUNT_DISPLAY   = {{ $amount_display }};
        const AMOUNT_USD       = {{ $amount_usd }};
        const PACKAGE          = '{{ $package }}';
        const PACKAGE_DISPLAY  = '{{ $package_display }}';
        const CURRENCY         = '{{ $choosed_currency }}';
        const CSRF_TOKEN       = '{{ csrf_token() }}';

        // ── Tab switching ────────────────────────────────────────────────────
        function switchMethod(method) {
            ['paypal', 'toss', 'stripe'].forEach(m => {
                document.getElementById(`tab-${m}`).classList.toggle('active', m === method);
                document.getElementById(`panel-${m}`).classList.toggle('visible', m === method);
                document.getElementById(`gw-${m}-info`).classList.toggle('hidden', m !== method);
            });

            const labels = { paypal: 'PayPal', toss: 'TOSS', stripe: 'Stripe' };
            document.getElementById('summary-method').textContent = labels[method];
        }

        // ── Validation ───────────────────────────────────────────────────────
        function validateFields(prefix) {
            let ok = true;

            const name = document.getElementById(`${prefix}_full_name`).value.trim();
            const errName = document.getElementById(`err-${prefix}-name`);
            if (!name) { errName.classList.remove('hidden'); ok = false; }
            else errName.classList.add('hidden');

            const email = document.getElementById(`${prefix}_email`).value.trim();
            const errEmail = document.getElementById(`err-${prefix}-email`);
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { errEmail.classList.remove('hidden'); ok = false; }
            else errEmail.classList.add('hidden');

            const terms = document.getElementById(`${prefix}_terms`).checked;
            const errTerms = document.getElementById(`err-${prefix}-terms`);
            if (!terms) { errTerms.classList.remove('hidden'); ok = false; }
            else errTerms.classList.add('hidden');

            return ok;
        }

        function showModal(title, message) {
            document.getElementById('modal-title').textContent   = title;
            document.getElementById('modal-message').textContent = message;
            const m = document.getElementById('processingModal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function hideModal() {
            const m = document.getElementById('processingModal');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        // ── PayPal form submit ───────────────────────────────────────────────
        document.getElementById('form-paypal').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validateFields('paypal')) return;

            showModal('Redirecting to PayPal…', 'Please wait while we set up your secure PayPal session.');
            const btn = document.getElementById('btn-paypal');
            btn.disabled = true;

            const formData = new FormData(this);
            formData.set('full_name', document.getElementById('paypal_full_name').value.trim());
            formData.set('email', document.getElementById('paypal_email').value.trim());

            fetch('{{ route('manufacturer.process-payment') }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            })
            .then(r => r.ok ? r.json() : r.json().then(d => Promise.reject(d)))
            .then(data => {
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    throw { message: data.message || 'Payment initialisation failed.' };
                }
            })
            .catch(err => {
                hideModal();
                btn.disabled = false;
                alert('Error: ' + (err.message || 'Something went wrong. Please try again.'));
            });
        });

        // ── TOSS form submit ─────────────────────────────────────────────────
        document.getElementById('form-toss').addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!validateFields('toss')) return;

            const btn    = document.getElementById('btn-toss');
            btn.disabled = true;

            const orderId = 'ORDER-' + Date.now() + '-' + Math.random().toString(36).substr(2, 6).toUpperCase();

            showModal('Connecting to TOSS…', 'Please wait while we prepare your payment.');

            const formData = new FormData(this);
            formData.set('full_name', document.getElementById('toss_full_name').value.trim());
            formData.set('email',     document.getElementById('toss_email').value.trim());
            formData.append('order_id', orderId);

            try {
                const res  = await fetch('{{ route('manufacturer.process-toss') }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                });
                const data = await res.json();

                if (!data.success) throw new Error(data.message || 'Failed to initialise TOSS payment.');

                const tossPayments = TossPayments(TOSS_CLIENT_KEY);
                await tossPayments.requestPayment('카드', {
                    amount:       AMOUNT_DISPLAY,
                    orderId:      orderId,
                    orderName:    PACKAGE_DISPLAY + ' Subscription',
                    customerName: document.getElementById('toss_full_name').value.trim(),
                    successUrl:   '{{ route('manufacturer.toss-success') }}',
                    failUrl:      '{{ route('manufacturer.toss-fail') }}',
                });

            } catch (err) {
                hideModal();
                btn.disabled = false;
                if (err.code !== 'USER_CANCEL') {
                    alert('Error: ' + (err.message || 'TOSS payment failed.'));
                }
            }
        });

        // ── Stripe form submit ───────────────────────────────────────────────
        document.getElementById('form-stripe').addEventListener('submit', async function (e) {
            e.preventDefault();
            if (!validateFields('stripe')) return;

            const btn    = document.getElementById('btn-stripe');
            btn.disabled = true;

            showModal('Connecting to Stripe…', 'Please wait while we prepare your Stripe checkout session.');

            const formData = new FormData(this);
            formData.set('full_name', document.getElementById('stripe_full_name').value.trim());
            formData.set('email',     document.getElementById('stripe_email').value.trim());

            try {
                const res  = await fetch('{{ route('manufacturer.process-stripe') }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                });
                const data = await res.json();

                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    throw new Error(data.message || 'Stripe payment initialisation failed.');
                }
            } catch (err) {
                hideModal();
                btn.disabled = false;
                alert('Error: ' + (err.message || 'Something went wrong. Please try again.'));
            }
        });

        // ── Live validation hints ────────────────────────────────────────────
        ['paypal', 'toss', 'stripe'].forEach(prefix => {
            document.getElementById(`${prefix}_full_name`).addEventListener('input', function () {
                if (this.value.trim()) document.getElementById(`err-${prefix}-name`).classList.add('hidden');
            });
            document.getElementById(`${prefix}_email`).addEventListener('input', function () {
                if (this.value.trim() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value))
                    document.getElementById(`err-${prefix}-email`).classList.add('hidden');
            });
            document.getElementById(`${prefix}_terms`).addEventListener('change', function () {
                if (this.checked) document.getElementById(`err-${prefix}-terms`).classList.add('hidden');
            });
        });

        // ── Safety timeout ───────────────────────────────────────────────────
        setTimeout(() => {
            const m = document.getElementById('processingModal');
            if (m && !m.classList.contains('hidden')) {
                hideModal();
                ['btn-paypal', 'btn-toss', 'btn-stripe'].forEach(id => {
                    const b = document.getElementById(id);
                    if (b) b.disabled = false;
                });
                alert('The request timed out. Please try again.');
            }
        }, 45000);

        // ── Init ─────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            // Show correct gateway info card on load
            const activeMethod = '{{ $payment_option }}';
            ['paypal', 'toss', 'stripe'].forEach(m => {
                document.getElementById(`gw-${m}-info`).classList.toggle('hidden', m !== activeMethod);
            });
        });
    </script>
@endsection