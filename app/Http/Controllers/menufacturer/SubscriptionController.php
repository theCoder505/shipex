<?php

namespace App\Http\Controllers\menufacturer;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use App\Models\Manufacturer;
use App\Models\PackageDetails;
use App\Models\PaymentRecord;
use App\Models\WebsiteInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omnipay\Omnipay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class SubscriptionController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->initializePayPalGateway();
    }

    private function initializePayPalGateway()
    {
        $webInfo = WebsiteInformation::where('id', 1)->first();

        if (!$webInfo) {
            Log::error('Website information not found for PayPal configuration');
            return;
        }

        if (empty($webInfo->PAYPAL_CLIENT_ID) || empty($webInfo->PAYPAL_SECRET)) {
            Log::error('PayPal credentials are empty in database');
            return;
        }

        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($webInfo->PAYPAL_CLIENT_ID);
        $this->gateway->setSecret($webInfo->PAYPAL_SECRET);

        $paypalMode = $webInfo->PAYPAL_MODE ?? 'sandbox';
        $this->gateway->setTestMode($paypalMode === 'sandbox');

        Log::info('PayPal gateway initialized', ['mode' => $paypalMode]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Subscription page
    // ─────────────────────────────────────────────────────────────────────────

    public function manufacturerSubscription()
    {
        if (Auth::guard('manufacturer')->check()) {
            $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        } else {
            $manufacturer_uid = 0;
        }

        $profile_data        = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $subscription_status = $profile_data->subscription;
        $package             = $profile_data->subscription_type;
        $subscription_type   = $profile_data->subscription_type;

        if ($subscription_type == 'monthly')  $subscription_type = 'STARTER';
        if ($subscription_type == '6months')  $subscription_type = 'PREMIUM';
        if ($subscription_type == 'yearly')   $subscription_type = 'ULTIMATE';

        $web_info               = WebsiteInformation::where('id', 1)->first();
        $paypal_client_id       = $web_info->PAYPAL_CLIENT_ID;
        $monthly_fee_amount     = $web_info->monthly_fee_amount;
        $half_yearly_fee_amount = $web_info->half_yearly_fee_amount;
        $yearly_fee_amount      = $web_info->yearly_fee_amount;
        $currency               = $web_info->currency;
        $exchange_rate          = $web_info->exchange_rate;

        $currency_icon = match ($currency) {
            'usd'   => '$',
            'eur'   => '€',
            default => '₩',
        };

        $monthly_total        = $monthly_fee_amount * 6;
        $yearly_total         = $monthly_fee_amount * 12;
        $half_yearly_discount = 0;
        $yearly_discount      = 0;

        if ($monthly_total > 0 && $half_yearly_fee_amount < $monthly_total) {
            $half_yearly_discount = round((($monthly_total - $half_yearly_fee_amount) / $monthly_total) * 100, 2);
        }
        if ($monthly_total > 0 && $yearly_fee_amount < $yearly_total) {
            $yearly_discount = round((($yearly_total - $yearly_fee_amount) / $yearly_total) * 100, 2);
        }

        $services = PackageDetails::orderBy('package_of')->orderBy('id')->get();

        return view('manufacturer.subscription', compact(
            'profile_data',
            'package',
            'subscription_status',
            'subscription_type',
            'monthly_fee_amount',
            'half_yearly_fee_amount',
            'yearly_fee_amount',
            'half_yearly_discount',
            'yearly_discount',
            'paypal_client_id',
            'currency',
            'currency_icon',
            'exchange_rate',
            'services'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Coupon check
    // ─────────────────────────────────────────────────────────────────────────

    public function checkCouponCode(Request $request)
    {
        $asking_code = $request['coupon_code'];
        $check_code  = CouponCode::where('coupon_code', $asking_code)->first();

        if ($check_code) {
            return response()->json(['type' => 'success', 'message' => 'Code matched']);
        }

        return response()->json(['type' => 'error', 'message' => 'Invalid coupon code']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Purchase – route to checkout view
    // ─────────────────────────────────────────────────────────────────────────

    public function purchaseSubscription(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer     = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $package          = $request['package_type'];
        $asking_code      = $request['coupon_code'];
        $choosed_currency = $request['choosed_currency'] ?? 'usd';
        $payment_option   = $request['payment_option'] ?? 'paypal'; // 'paypal' | 'toss' | 'stripe'

        if (!in_array($package, ['monthly', '6months', 'yearly'])) {
            return redirect()->back()->with('error', 'Invalid package type selected.');
        }
        if (!in_array($choosed_currency, ['usd', 'krw'])) {
            return redirect()->back()->with('error', 'Invalid currency selected.');
        }
        if (!in_array($payment_option, ['paypal', 'toss', 'stripe'])) {
            return redirect()->back()->with('error', 'Invalid payment option selected.');
        }

        // TOSS requires KRW
        if ($payment_option === 'toss' && $choosed_currency !== 'krw') {
            return redirect()->back()->with('error', 'TOSS payments only support KRW currency. Please switch to KRW.');
        }

        // Stripe requires USD
        if ($payment_option === 'stripe' && $choosed_currency !== 'usd') {
            return redirect()->back()->with('error', 'Stripe payments only support USD currency. Please switch to USD.');
        }

        $web_info = WebsiteInformation::where('id', 1)->first();
        if (!$web_info) {
            return redirect()->back()->with('error', 'Website configuration not found.');
        }

        $paypal_client_id  = $web_info->PAYPAL_CLIENT_ID;
        $paypal_mode       = $web_info->PAYPAL_MODE ?? 'sandbox';
        $toss_client_key   = $web_info->TOSS_CLIENT_KEY ?? '';
        $stripe_client_key = $web_info->stripe_client_id ?? '';
        $exchange_rate     = $web_info->exchange_rate;
        $base_currency     = $web_info->currency;

        if (!$exchange_rate || $exchange_rate <= 0) {
            return redirect()->back()->with('error', 'Exchange rate not configured properly.');
        }

        $base_monthly     = $web_info->monthly_fee_amount;
        $base_half_yearly = $web_info->half_yearly_fee_amount;
        $base_yearly      = $web_info->yearly_fee_amount;

        if ($base_monthly <= 0 || $base_half_yearly <= 0 || $base_yearly <= 0) {
            return redirect()->back()->with('error', 'Subscription prices not configured properly.');
        }

        $package_display = match ($package) {
            '6months' => 'Half Yearly',
            default   => ucfirst($package),
        };
        $amount_base = match ($package) {
            'monthly' => $base_monthly,
            '6months' => $base_half_yearly,
            'yearly'  => $base_yearly,
            default   => $base_monthly,
        };

        [$amount_display, $amount_usd] = $this->calculateCurrencyAmounts(
            $base_currency,
            $choosed_currency,
            $amount_base,
            $exchange_rate
        );

        $type         = '';
        $discount     = 0;
        $discount_usd = 0;

        // Apply coupon
        if (!empty($asking_code)) {
            $coupon = CouponCode::where('coupon_code', $asking_code)->first();
            if ($coupon) {
                $coupon_amount_base = match ($package) {
                    'monthly' => $coupon->monthly_fee_amount,
                    '6months' => $coupon->half_yearly_fee_amount,
                    'yearly'  => $coupon->yearly_fee_amount,
                    default   => $coupon->monthly_fee_amount,
                };

                if ($coupon_amount_base < 0) {
                    return redirect()->back()->with('error', 'Invalid coupon amount.');
                }

                $amount_base = $coupon_amount_base;
                [$amount_display, $amount_usd] = $this->calculateCurrencyAmounts(
                    $base_currency,
                    $choosed_currency,
                    $amount_base,
                    $exchange_rate
                );

                $type = $coupon->type;
                if ($type == 'fixed') {
                    $discount_base = $coupon->discount_amount;
                    [$discount, $discount_usd] = $this->calculateCurrencyAmounts(
                        $base_currency,
                        $choosed_currency,
                        $discount_base,
                        $exchange_rate
                    );
                } else {
                    $discount     = $coupon->discount_percentage;
                    $discount_usd = $discount;
                }

                // 100% discount – activate immediately
                if ($coupon->discount_percentage == 100 || $amount_usd == 0) {
                    $subscriptionEndDate = $this->calculateSubscriptionEndDate($package);

                    $manufacturer->subscription            = 1;
                    $manufacturer->subscription_type       = $package;
                    $manufacturer->coupon_code             = $asking_code;
                    $manufacturer->subscription_start_date = now();
                    $manufacturer->subscription_end_date   = $subscriptionEndDate;
                    $manufacturer->save();

                    PaymentRecord::create([
                        'manufacturer_uid'      => $manufacturer_uid,
                        'package_type'          => $package,
                        'amount'                => 0,
                        'currency'              => $choosed_currency,
                        'payment_status'        => 'completed',
                        'coupon_code'           => $asking_code,
                        'billing_name'          => $manufacturer->name,
                        'billing_email'         => $manufacturer->email,
                        'payment_method'        => 'coupon',
                        'payment_date'          => now(),
                        'subscription_end_date' => $subscriptionEndDate,
                    ]);

                    return redirect('/manufacturer/set-up-manufacturer-profile')
                        ->with('success', 'Congrats! You have successfully subscribed to our platform.');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid or expired coupon code!');
            }
        }

        // Store session for later validation
        session([
            'payment_validation' => [
                'package'        => $package,
                'amount_usd'     => $amount_usd,
                'amount_display' => $amount_display,
                'currency'       => $choosed_currency,
                'base_currency'  => $base_currency,
                'coupon_code'    => $asking_code,
                'payment_option' => $payment_option,
                'timestamp'      => now()->timestamp,
            ],
        ]);

        return view('manufacturer.checkout', compact(
            'paypal_client_id',
            'paypal_mode',
            'toss_client_key',
            'stripe_client_key',
            'amount_display',
            'amount_usd',
            'package',
            'package_display',
            'type',
            'discount',
            'discount_usd',
            'choosed_currency',
            'exchange_rate',
            'base_currency',
            'payment_option'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Currency helper
    // ─────────────────────────────────────────────────────────────────────────

    private function calculateCurrencyAmounts($base_currency, $choosed_currency, $amount_base, $exchange_rate)
    {
        if ($base_currency == 'krw' && $choosed_currency == 'krw') {
            $amount_display = $amount_base;
            $amount_usd     = round($amount_base / $exchange_rate, 2);
        } elseif ($base_currency == 'krw' && $choosed_currency == 'usd') {
            $amount_display = round($amount_base / $exchange_rate, 2);
            $amount_usd     = $amount_display;
        } elseif ($base_currency == 'usd' && $choosed_currency == 'krw') {
            $amount_display = round($amount_base * $exchange_rate);
            $amount_usd     = $amount_base;
        } else {
            $amount_display = $amount_base;
            $amount_usd     = $amount_base;
        }

        return [$amount_display, $amount_usd];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Process payment – PayPal path
    // ─────────────────────────────────────────────────────────────────────────

    public function processSubscriptionPayment(Request $request)
    {
        if (!$this->gateway) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway not configured properly. Please contact support.',
            ], 500);
        }

        try {
            $validationData = session('payment_validation');

            if (!$validationData) {
                return response()->json(['success' => false, 'message' => 'Payment session expired.'], 400);
            }

            if (now()->timestamp - $validationData['timestamp'] > 1800) {
                session()->forget('payment_validation');
                return response()->json(['success' => false, 'message' => 'Payment session expired.'], 400);
            }

            $choosed_currency = $request->choosed_currency ?? 'usd';
            $amount_display   = $request->amount_display;
            $amount_usd       = $request->amount_usd;
            $package_type     = $request->package_type;
            $coupon_code      = $request->coupon_code;

            if (
                $package_type !== $validationData['package'] ||
                $choosed_currency !== $validationData['currency'] ||
                floatval($amount_usd) !== floatval($validationData['amount_usd']) ||
                floatval($amount_display) !== floatval($validationData['amount_display']) ||
                $coupon_code !== $validationData['coupon_code']
            ) {
                Log::warning('Payment data tampering detected (PayPal)', ['manufacturer' => Auth::guard('manufacturer')->user()->manufacturer_uid]);
                return response()->json(['success' => false, 'message' => 'Invalid payment data detected.'], 400);
            }

            $web_info      = WebsiteInformation::where('id', 1)->first();
            $exchange_rate = $web_info->exchange_rate;
            $base_currency = $web_info->currency;

            $expected = $this->recalculateExpectedAmounts($base_currency, $choosed_currency, $amount_display, $amount_usd, $exchange_rate, $validationData);

            if (!$expected['valid']) {
                return response()->json(['success' => false, 'message' => 'Currency conversion validation failed.'], 400);
            }

            $response = $this->gateway->purchase([
                'amount'      => number_format($amount_usd, 2, '.', ''),
                'currency'    => 'USD',
                'returnUrl'   => route('manufacturer.subscription-success'),
                'cancelUrl'   => route('manufacturer.subscription-cancel'),
                'description' => $package_type . ' Subscription Package',
            ])->send();

            if ($response->isRedirect()) {
                session([
                    'payment_data' => [
                        'manufacturer_uid' => Auth::guard('manufacturer')->user()->manufacturer_uid,
                        'package_type'     => $package_type,
                        'amount_usd'       => $amount_usd,
                        'amount_display'   => $amount_display,
                        'currency'         => $choosed_currency,
                        'coupon_code'      => $coupon_code,
                        'billing_name'     => $request->full_name,
                        'billing_email'    => $request->email,
                        'payment_method'   => 'paypal',
                    ],
                ]);

                session()->forget('payment_validation');

                return response()->json(['success' => true, 'redirect_url' => $response->getRedirectUrl()]);
            }

            return response()->json(['success' => false, 'message' => 'PayPal error: ' . $response->getMessage()], 400);

        } catch (\Throwable $th) {
            Log::error('PayPal payment exception', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Payment initialization failed: ' . $th->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Process payment – TOSS path
    // ─────────────────────────────────────────────────────────────────────────

    public function processTossPayment(Request $request)
    {
        try {
            $validationData = session('payment_validation');

            if (!$validationData) {
                return response()->json(['success' => false, 'message' => 'Payment session expired.'], 400);
            }

            if (now()->timestamp - $validationData['timestamp'] > 1800) {
                session()->forget('payment_validation');
                return response()->json(['success' => false, 'message' => 'Payment session expired.'], 400);
            }

            $choosed_currency = $request->choosed_currency ?? 'krw';
            $amount_display   = $request->amount_display;
            $package_type     = $request->package_type;
            $coupon_code      = $request->coupon_code;
            $order_id         = $request->order_id;

            if (
                $package_type !== $validationData['package'] ||
                $choosed_currency !== $validationData['currency'] ||
                floatval($amount_display) !== floatval($validationData['amount_display']) ||
                $coupon_code !== $validationData['coupon_code']
            ) {
                Log::warning('TOSS payment data tampering detected', ['manufacturer' => Auth::guard('manufacturer')->user()->manufacturer_uid]);
                return response()->json(['success' => false, 'message' => 'Invalid payment data detected.'], 400);
            }

            session([
                'payment_data' => [
                    'manufacturer_uid' => Auth::guard('manufacturer')->user()->manufacturer_uid,
                    'package_type'     => $package_type,
                    'amount_usd'       => $validationData['amount_usd'],
                    'amount_display'   => $amount_display,
                    'currency'         => $choosed_currency,
                    'coupon_code'      => $coupon_code,
                    'billing_name'     => $request->full_name,
                    'billing_email'    => $request->email,
                    'payment_method'   => 'toss',
                    'toss_order_id'    => $order_id,
                ],
            ]);

            session()->forget('payment_validation');

            return response()->json(['success' => true]);

        } catch (\Throwable $th) {
            Log::error('TOSS payment session error', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to initialise TOSS payment: ' . $th->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Process payment – Stripe path (creates Stripe Checkout Session)
    // ─────────────────────────────────────────────────────────────────────────

    public function processStripePayment(Request $request)
    {
        try {
            $validationData = session('payment_validation');

            if (!$validationData) {
                return response()->json(['success' => false, 'message' => 'Payment session expired.'], 400);
            }

            if (now()->timestamp - $validationData['timestamp'] > 1800) {
                session()->forget('payment_validation');
                return response()->json(['success' => false, 'message' => 'Payment session expired.'], 400);
            }

            $choosed_currency = $request->choosed_currency ?? 'usd';
            $amount_display   = $request->amount_display;
            $amount_usd       = $request->amount_usd;
            $package_type     = $request->package_type;
            $coupon_code      = $request->coupon_code;

            if (
                $package_type !== $validationData['package'] ||
                $choosed_currency !== $validationData['currency'] ||
                floatval($amount_usd) !== floatval($validationData['amount_usd']) ||
                floatval($amount_display) !== floatval($validationData['amount_display']) ||
                $coupon_code !== $validationData['coupon_code']
            ) {
                Log::warning('Stripe payment data tampering detected', ['manufacturer' => Auth::guard('manufacturer')->user()->manufacturer_uid]);
                return response()->json(['success' => false, 'message' => 'Invalid payment data detected.'], 400);
            }

            $web_info      = WebsiteInformation::where('id', 1)->first();
            $stripe_secret = $web_info->stripe_secret_key ?? '';

            if (empty($stripe_secret)) {
                return response()->json(['success' => false, 'message' => 'Stripe is not configured. Please contact support.'], 500);
            }

            Stripe::setApiKey($stripe_secret);

            // Stripe amount is in cents for USD
            $stripe_amount = intval(round(floatval($amount_usd) * 100));

            $package_display = match ($package_type) {
                '6months' => 'Half Yearly',
                default   => ucfirst($package_type),
            };

            $stripe_session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'unit_amount'  => $stripe_amount,
                        'product_data' => [
                            'name' => $package_display . ' Subscription Package',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode'          => 'payment',
                'success_url'   => route('manufacturer.stripe-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'    => route('manufacturer.stripe-cancel'),
                'customer_email' => $request->email,
            ]);

            // Store payment session data
            session([
                'payment_data' => [
                    'manufacturer_uid'  => Auth::guard('manufacturer')->user()->manufacturer_uid,
                    'package_type'      => $package_type,
                    'amount_usd'        => $amount_usd,
                    'amount_display'    => $amount_display,
                    'currency'          => $choosed_currency,
                    'coupon_code'       => $coupon_code,
                    'billing_name'      => $request->full_name,
                    'billing_email'     => $request->email,
                    'payment_method'    => 'stripe',
                    'stripe_session_id' => $stripe_session->id,
                ],
            ]);

            session()->forget('payment_validation');

            return response()->json(['success' => true, 'redirect_url' => $stripe_session->url]);

        } catch (\Throwable $th) {
            Log::error('Stripe payment exception', ['error' => $th->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Stripe payment failed: ' . $th->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Stripe success callback
    // ─────────────────────────────────────────────────────────────────────────

    public function stripeSuccess(Request $request)
    {
        $stripe_session_id = $request->query('session_id');

        if (!$stripe_session_id) {
            return redirect('/manufacturer/stripe-cancel')->with('error', 'Invalid Stripe payment response.');
        }

        $paymentData = session('payment_data');
        if (!$paymentData || ($paymentData['payment_method'] ?? '') !== 'stripe') {
            return redirect('/manufacturer/stripe-cancel')->with('error', 'Payment session expired. Please try again.');
        }

        if ($paymentData['stripe_session_id'] !== $stripe_session_id) {
            Log::warning('Stripe session ID mismatch', [
                'expected' => $paymentData['stripe_session_id'],
                'received' => $stripe_session_id,
            ]);
            return redirect('/manufacturer/stripe-cancel')->with('error', 'Payment session mismatch. Please contact support.');
        }

        try {
            $web_info      = WebsiteInformation::where('id', 1)->first();
            $stripe_secret = $web_info->stripe_secret_key ?? '';

            Stripe::setApiKey($stripe_secret);

            $stripe_session = StripeSession::retrieve($stripe_session_id);

            if ($stripe_session->payment_status !== 'paid') {
                Log::error('Stripe payment not completed', ['status' => $stripe_session->payment_status]);
                return redirect('/manufacturer/stripe-cancel')->with('error', 'Stripe payment was not completed.');
            }

            $manufacturer_uid    = $paymentData['manufacturer_uid'];
            $manufacturer        = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
            $subscriptionEndDate = $this->calculateSubscriptionEndDate($paymentData['package_type']);

            $manufacturer->update([
                'subscription'            => 1,
                'subscription_type'       => $paymentData['package_type'],
                'coupon_code'             => $paymentData['coupon_code'] ?? null,
                'subscription_start_date' => now(),
                'subscription_end_date'   => $subscriptionEndDate,
            ]);

            PaymentRecord::create([
                'manufacturer_uid'      => $manufacturer_uid,
                'stripe_payment_id'     => $stripe_session->payment_intent ?? $stripe_session->id,
                'stripe_session_id'     => $stripe_session_id,
                'package_type'          => $paymentData['package_type'],
                'amount'                => $paymentData['amount_display'],
                'currency'              => $paymentData['currency'],
                'payment_status'        => 'completed',
                'coupon_code'           => $paymentData['coupon_code'] ?? null,
                'billing_name'          => $paymentData['billing_name'],
                'billing_email'         => $paymentData['billing_email'],
                'payment_method'        => 'stripe',
                'stripe_response'       => json_encode($stripe_session->toArray()),
                'payment_date'          => now(),
                'subscription_end_date' => $subscriptionEndDate,
            ]);

            session()->forget('payment_data');

            return redirect('/manufacturer/manage-subscription')
                ->with('success', 'Congrats! Your Stripe payment was successful and your subscription is now active.');

        } catch (\Throwable $th) {
            Log::error('Stripe success callback exception', ['error' => $th->getMessage()]);
            return redirect('/manufacturer/stripe-cancel')->with('error', 'Stripe payment verification failed: ' . $th->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Stripe cancel callback
    // ─────────────────────────────────────────────────────────────────────────

    public function stripeCancel()
    {
        session()->forget('payment_data');
        session()->forget('payment_validation');
        return redirect('/manufacturer/packages')->with('error', 'Stripe payment was cancelled. Please try again.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOSS success callback
    // ─────────────────────────────────────────────────────────────────────────

    public function tossSuccess(Request $request)
    {
        $paymentKey = $request->query('paymentKey');
        $orderId    = $request->query('orderId');
        $amount     = $request->query('amount');

        if (!$paymentKey || !$orderId || !$amount) {
            return redirect('/manufacturer/subscription-cancel')->with('error', 'Invalid TOSS payment response.');
        }

        $paymentData = session('payment_data');
        if (!$paymentData || ($paymentData['payment_method'] ?? '') !== 'toss') {
            return redirect('/manufacturer/subscription-cancel')->with('error', 'Payment session expired. Please try again.');
        }

        if ((int) $amount !== (int) $paymentData['amount_display']) {
            Log::warning('TOSS amount mismatch', ['expected' => $paymentData['amount_display'], 'received' => $amount]);
            return redirect('/manufacturer/subscription-cancel')->with('error', 'Payment amount mismatch. Please contact support.');
        }

        $web_info  = WebsiteInformation::where('id', 1)->first();
        $secretKey = $web_info->TOSS_SECRET_KEY ?? '';

        try {
            $client   = new \GuzzleHttp\Client();
            $response = $client->post('https://api.tosspayments.com/v1/payments/confirm', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($secretKey . ':'),
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'paymentKey' => $paymentKey,
                    'orderId'    => $orderId,
                    'amount'     => (int) $amount,
                ],
            ]);

            $tossData = json_decode($response->getBody()->getContents(), true);

            if (($tossData['status'] ?? '') !== 'DONE') {
                Log::error('TOSS confirmation failed', ['data' => $tossData]);
                return redirect('/manufacturer/subscription-cancel')->with('error', 'TOSS payment confirmation failed.');
            }

            $manufacturer_uid    = $paymentData['manufacturer_uid'];
            $manufacturer        = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
            $subscriptionEndDate = $this->calculateSubscriptionEndDate($paymentData['package_type']);

            $manufacturer->update([
                'subscription'            => 1,
                'subscription_type'       => $paymentData['package_type'],
                'coupon_code'             => $paymentData['coupon_code'] ?? null,
                'subscription_start_date' => now(),
                'subscription_end_date'   => $subscriptionEndDate,
            ]);

            PaymentRecord::create([
                'manufacturer_uid'      => $manufacturer_uid,
                'toss_payment_key'      => $paymentKey,
                'toss_order_id'         => $orderId,
                'toss_transaction_id'   => $tossData['transactionKey'] ?? null,
                'package_type'          => $paymentData['package_type'],
                'amount'                => $paymentData['amount_display'],
                'currency'              => $paymentData['currency'],
                'payment_status'        => 'completed',
                'coupon_code'           => $paymentData['coupon_code'] ?? null,
                'billing_name'          => $paymentData['billing_name'],
                'billing_email'         => $paymentData['billing_email'],
                'payment_method'        => 'toss',
                'toss_response'         => json_encode($tossData),
                'payment_date'          => now(),
                'subscription_end_date' => $subscriptionEndDate,
            ]);

            session()->forget('payment_data');

            return redirect('/manufacturer/manage-subscription')
                ->with('success', 'Congrats! Your TOSS payment was successful and your subscription is now active.');

        } catch (\Throwable $th) {
            Log::error('TOSS confirmation exception', ['error' => $th->getMessage()]);
            return redirect('/manufacturer/subscription-cancel')->with('error', 'TOSS payment confirmation error: ' . $th->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // TOSS fail callback
    // ─────────────────────────────────────────────────────────────────────────

    public function tossFail(Request $request)
    {
        session()->forget('payment_data');
        session()->forget('payment_validation');

        $message = $request->query('message', 'TOSS payment was cancelled or failed.');
        return redirect('/manufacturer/packages')->with('error', $message);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Currency validation helper
    // ─────────────────────────────────────────────────────────────────────────

    private function recalculateExpectedAmounts($base_currency, $choosed_currency, $amount_display, $amount_usd, $exchange_rate, $validationData)
    {
        $tolerance = 0.01;
        $valid     = false;

        if ($base_currency == 'krw' && $choosed_currency == 'krw') {
            $expected_usd = $amount_display / $exchange_rate;
            $valid        = (abs($amount_usd - $expected_usd) < $tolerance);
        } elseif ($base_currency == 'krw' && $choosed_currency == 'usd') {
            $valid = (abs($amount_display - $amount_usd) < $tolerance);
        } elseif ($base_currency == 'usd' && $choosed_currency == 'krw') {
            $expected_display = $amount_usd * $exchange_rate;
            $valid            = (abs($amount_display - $expected_display) < ($exchange_rate * $tolerance));
        } else {
            $valid = (abs($amount_display - $amount_usd) < $tolerance);
        }

        return ['valid' => $valid];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PayPal success callback
    // ─────────────────────────────────────────────────────────────────────────

    public function subscriptionSuccess(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $paymentData = session('payment_data');

            if (!$paymentData) {
                return redirect('/manufacturer/subscription-cancel')->with('error', 'Payment session expired. Please try again.');
            }

            $this->initializePayPalGateway();

            if (!$this->gateway) {
                return redirect('/manufacturer/subscription-cancel')->with('error', 'Payment gateway configuration error.');
            }

            $transaction = $this->gateway->completePurchase([
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ]);

            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $arr = $response->getData();

                $manufacturer_uid    = $paymentData['manufacturer_uid'];
                $manufacturer        = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
                $subscriptionEndDate = $this->calculateSubscriptionEndDate($paymentData['package_type']);

                $manufacturer->update([
                    'subscription'            => 1,
                    'subscription_type'       => $paymentData['package_type'],
                    'coupon_code'             => $paymentData['coupon_code'] ?? null,
                    'paypal_payer_id'         => $arr['payer']['payer_info']['payer_id'],
                    'paypal_payment_id'       => $arr['id'],
                    'subscription_start_date' => now(),
                    'subscription_end_date'   => $subscriptionEndDate,
                ]);

                PaymentRecord::create([
                    'manufacturer_uid'      => $manufacturer_uid,
                    'paypal_payment_id'     => $arr['id'],
                    'paypal_payer_id'       => $arr['payer']['payer_info']['payer_id'],
                    'paypal_order_id'       => $request->input('paymentId'),
                    'paypal_transaction_id' => $arr['transactions'][0]['related_resources'][0]['sale']['id'] ?? $arr['id'],
                    'package_type'          => $paymentData['package_type'],
                    'amount'                => $paymentData['amount_display'],
                    'currency'              => $paymentData['currency'],
                    'payment_status'        => 'completed',
                    'coupon_code'           => $paymentData['coupon_code'] ?? null,
                    'billing_name'          => $paymentData['billing_name'],
                    'billing_email'         => $paymentData['billing_email'],
                    'payment_method'        => 'paypal',
                    'paypal_response'       => json_encode($arr),
                    'payment_date'          => now(),
                    'subscription_end_date' => $subscriptionEndDate,
                ]);

                session()->forget('payment_data');

                return redirect('/manufacturer/manage-subscription')
                    ->with('success', 'Congrats! You have successfully subscribed to our platform.');
            }

            return redirect('/manufacturer/subscription-cancel')->with('error', 'Payment failed: ' . $response->getMessage());
        }

        return redirect('/manufacturer/subscription-cancel')->with('error', 'Payment was cancelled or invalid payment data received.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Cancel / common cancel
    // ─────────────────────────────────────────────────────────────────────────

    public function subscriptionCancel()
    {
        session()->forget('payment_data');
        session()->forget('payment_validation');
        return redirect('/manufacturer/packages')->with('error', 'Payment was cancelled. Please try again.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Subscription end date helper
    // ─────────────────────────────────────────────────────────────────────────

    protected function calculateSubscriptionEndDate($packageType)
    {
        return match ($packageType) {
            'monthly' => Carbon::now()->addMonth(),
            '6months' => Carbon::now()->addMonths(6),
            'yearly'  => Carbon::now()->addYear(),
            default   => Carbon::now()->addMonth(),
        };
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Manage / cancel subscription
    // ─────────────────────────────────────────────────────────────────────────

    public function manageSubscription()
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer     = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $payment_records  = PaymentRecord::where('manufacturer_uid', $manufacturer_uid)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manufacturer.manage_subscription', compact('manufacturer', 'payment_records'));
    }

    public function cancelSubscription(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer     = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        if (!$manufacturer->subscription) {
            return redirect()->back()->with('error', 'No active subscription found to cancel.');
        }

        try {
            $manufacturer->update([
                'subscription'          => 0,
                'subscription_type'     => null,
                'coupon_code'           => null,
                'subscription_end_date' => now(),
            ]);

            PaymentRecord::where('manufacturer_uid', $manufacturer_uid)
                ->where('payment_status', 'completed')
                ->update(['payment_status' => 'canceled']);

            return redirect()->back()->with('success', 'Your subscription has been cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    public function getSubscriptionDetails(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer     = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        if (!$manufacturer->subscription) {
            return response()->json(['success' => false, 'message' => 'No active subscription found']);
        }

        return response()->json([
            'success'      => true,
            'subscription' => [
                'status'         => $manufacturer->subscription ? 'active' : 'inactive',
                'package_type'   => $manufacturer->subscription_type,
                'start_date'     => $manufacturer->subscription_start_date,
                'end_date'       => $manufacturer->subscription_end_date,
                'days_remaining' => $manufacturer->subscription_end_date
                    ? Carbon::now()->diffInDays(Carbon::parse($manufacturer->subscription_end_date), false)
                    : 0,
            ],
        ]);
    }
}