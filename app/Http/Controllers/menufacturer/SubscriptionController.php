<?php

namespace App\Http\Controllers\menufacturer;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use App\Models\Manufacturer;
use App\Models\PaymentRecord;
use App\Models\WebsiteInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omnipay\Omnipay;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
            Log::error('PayPal credentials are empty in database', [
                'client_id_exists' => !empty($webInfo->PAYPAL_CLIENT_ID),
                'secret_exists' => !empty($webInfo->PAYPAL_SECRET)
            ]);
            return;
        }

        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($webInfo->PAYPAL_CLIENT_ID);
        $this->gateway->setSecret($webInfo->PAYPAL_SECRET);
        
        // Set test mode based on database configuration
        $paypalMode = $webInfo->PAYPAL_MODE ?? 'sandbox';
        $this->gateway->setTestMode($paypalMode === 'sandbox');
        
        Log::info('PayPal gateway initialized', [
            'mode' => $paypalMode,
            'test_mode' => $paypalMode === 'sandbox'
        ]);
    }

    public function manufacturerSubscription()
    {
        if (Auth::guard('manufacturer')->check()) {
            $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        } else {
            $manufacturer_uid = 0;
        }

        $profile_data = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $subscription_status = $profile_data->subscription;
        $package = $profile_data->subscription_type;
        $subscription_type = $profile_data->subscription_type;

        if ($subscription_type == 'monthly') {
            $subscription_type = 'STARTER';
        }
        if ($subscription_type == '6months') {
            $subscription_type = 'PREMIUM';
        }
        if ($subscription_type == 'yearly') {
            $subscription_type = 'ULTIMATE';
        }

        $web_info = WebsiteInformation::where('id', 1)->first();
        $paypal_client_id = $web_info->PAYPAL_CLIENT_ID;
        $monthly_fee_amount = $web_info->monthly_fee_amount;
        $half_yearly_fee_amount = $web_info->half_yearly_fee_amount;
        $yearly_fee_amount = $web_info->yearly_fee_amount;
        $currency = $web_info->currency;
        $exchange_rate = $web_info->exchange_rate;

        if ($currency == 'usd') {
            $currency_icon = '$';
        } elseif ($currency == 'eur') {
            $currency_icon = '€';
        } else {
            $currency_icon = '₩';
        }

        $monthly_total = $monthly_fee_amount * 6;
        $yearly_total = $monthly_fee_amount * 12;
        $half_yearly_discount = 0;
        $yearly_discount = 0;

        if ($monthly_total > 0 && $half_yearly_fee_amount < $monthly_total) {
            $half_yearly_discount = round((($monthly_total - $half_yearly_fee_amount) / $monthly_total) * 100, 2);
        }
        if ($monthly_total > 0 && $yearly_fee_amount < $yearly_total) {
            $yearly_discount = round((($yearly_total - $yearly_fee_amount) / $yearly_total) * 100, 2);
        }

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
        ));
    }

    public function checkCouponCode(Request $request)
    {
        $asking_code = $request['coupon_code'];
        $check_code = CouponCode::where('coupon_code', $asking_code)->first();

        if ($check_code) {
            return response()->json([
                'type' => 'success',
                'message' => 'Code matched',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Invalid coupon code',
            ]);
        }
    }

    public function purchaseSubscription(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $package = $request['package_type'];
        $asking_code = $request['coupon_code'];
        $choosed_currency = $request['choosed_currency'] ?? 'usd';

        // Validate package type
        $validPackages = ['monthly', '6months', 'yearly'];
        if (!in_array($package, $validPackages)) {
            return redirect()->back()->with('error', 'Invalid package type selected.');
        }

        // Validate currency
        $validCurrencies = ['usd', 'krw'];
        if (!in_array($choosed_currency, $validCurrencies)) {
            return redirect()->back()->with('error', 'Invalid currency selected.');
        }

        // Always get fresh data from database
        $web_info = WebsiteInformation::where('id', 1)->first();
        if (!$web_info) {
            return redirect()->back()->with('error', 'Website configuration not found.');
        }

        $paypal_client_id = $web_info->PAYPAL_CLIENT_ID;
        $paypal_mode = $web_info->PAYPAL_MODE ?? 'sandbox';
        $exchange_rate = $web_info->exchange_rate;
        $base_currency = $web_info->currency;

        // Validate exchange rate
        if (!$exchange_rate || $exchange_rate <= 0) {
            return redirect()->back()->with('error', 'Exchange rate not configured properly.');
        }

        // Get base prices in the stored currency
        $base_monthly = $web_info->monthly_fee_amount;
        $base_half_yearly = $web_info->half_yearly_fee_amount;
        $base_yearly = $web_info->yearly_fee_amount;

        // Validate base prices
        if ($base_monthly <= 0 || $base_half_yearly <= 0 || $base_yearly <= 0) {
            return redirect()->back()->with('error', 'Subscription prices not configured properly.');
        }

        // Determine package price in the base currency
        $package_display = $package;
        if ($package == 'monthly') {
            $amount_base = $base_monthly;
        } elseif ($package == '6months') {
            $package_display = 'Half Yearly';
            $amount_base = $base_half_yearly;
        } elseif ($package == 'yearly') {
            $amount_base = $base_yearly;
        }

        // Calculate amounts based on currency combinations
        list($amount_display, $amount_usd) = $this->calculateCurrencyAmounts(
            $base_currency, 
            $choosed_currency, 
            $amount_base, 
            $exchange_rate
        );

        $type = '';
        $discount = 0;
        $discount_usd = 0;

        // Apply coupon if provided
        if ($asking_code !== null) {
            $coupon = CouponCode::where('coupon_code', $asking_code)->first();
            if ($coupon) {
                // Get coupon price in the base currency
                if ($package == 'monthly') {
                    $coupon_amount_base = $coupon->monthly_fee_amount;
                } elseif ($package == '6months') {
                    $coupon_amount_base = $coupon->half_yearly_fee_amount;
                } elseif ($package == 'yearly') {
                    $coupon_amount_base = $coupon->yearly_fee_amount;
                }

                // Validate coupon amounts
                if ($coupon_amount_base < 0) {
                    return redirect()->back()->with('error', 'Invalid coupon amount.');
                }

                // Update amounts with coupon
                $amount_base = $coupon_amount_base;

                // Recalculate display and USD amounts with coupon
                list($amount_display, $amount_usd) = $this->calculateCurrencyAmounts(
                    $base_currency, 
                    $choosed_currency, 
                    $amount_base, 
                    $exchange_rate
                );

                $type = $coupon->type;
                if ($type == 'fixed') {
                    $discount_base = $coupon->discount_amount;
                    list($discount, $discount_usd) = $this->calculateCurrencyAmounts(
                        $base_currency,
                        $choosed_currency,
                        $discount_base,
                        $exchange_rate
                    );
                } else {
                    $discount = $coupon->discount_percentage;
                    $discount_usd = $discount;
                }

                // Handle 100% discount coupon
                if ($coupon->discount_percentage == 100 || $amount_usd == 0) {
                    $subscriptionEndDate = $this->calculateSubscriptionEndDate($package);

                    $manufacturer->subscription = 1;
                    $manufacturer->subscription_type = $package;
                    $manufacturer->coupon_code = $asking_code;
                    $manufacturer->subscription_start_date = now();
                    $manufacturer->subscription_end_date = $subscriptionEndDate;
                    $manufacturer->save();

                    PaymentRecord::create([
                        'manufacturer_uid' => $manufacturer_uid,
                        'package_type' => $package,
                        'amount' => 0,
                        'currency' => $choosed_currency,
                        'payment_status' => 'completed',
                        'coupon_code' => $asking_code,
                        'billing_name' => $manufacturer->name,
                        'billing_email' => $manufacturer->email,
                        'payment_method' => 'coupon',
                        'payment_date' => now(),
                        'subscription_end_date' => $subscriptionEndDate,
                    ]);

                    return redirect('/manufacturer/set-up-manufacturer-profile')
                        ->with('success', 'Congrats! You have successfully subscribed to our platform.');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid or expired coupon code!');
            }
        }

        // Store minimal payment details in session for validation
        session([
            'payment_validation' => [
                'package' => $package,
                'amount_usd' => $amount_usd,
                'amount_display' => $amount_display,
                'currency' => $choosed_currency,
                'base_currency' => $base_currency,
                'coupon_code' => $asking_code,
                'timestamp' => now()->timestamp
            ]
        ]);

        return view('manufacturer.checkout', compact(
            'paypal_client_id',
            'paypal_mode',
            'amount_display',
            'amount_usd',
            'package',
            'package_display',
            'type',
            'discount',
            'discount_usd',
            'choosed_currency',
            'exchange_rate',
            'base_currency'
        ));
    }

    /**
     * Calculate display amount and USD amount based on currency combinations
     */
    private function calculateCurrencyAmounts($base_currency, $choosed_currency, $amount_base, $exchange_rate)
    {
        $amount_display = 0;
        $amount_usd = 0;

        // Case 1: Base currency and chosen currency are both KRW
        if ($base_currency == 'krw' && $choosed_currency == 'krw') {
            $amount_display = $amount_base; // Display in KRW
            $amount_usd = round($amount_base / $exchange_rate, 2); // Convert to USD for PayPal
        }
        // Case 2: Base currency is KRW, chosen currency is USD
        elseif ($base_currency == 'krw' && $choosed_currency == 'usd') {
            $amount_display = round($amount_base / $exchange_rate, 2); // Convert to USD for display
            $amount_usd = $amount_display; // Same for PayPal
        }
        // Case 3: Base currency is USD, chosen currency is KRW
        elseif ($base_currency == 'usd' && $choosed_currency == 'krw') {
            $amount_display = round($amount_base * $exchange_rate); // Convert to KRW for display
            $amount_usd = $amount_base; // Original USD for PayPal
        }
        // Case 4: Base currency and chosen currency are both USD
        elseif ($base_currency == 'usd' && $choosed_currency == 'usd') {
            $amount_display = $amount_base; // Display in USD
            $amount_usd = $amount_base; // Same for PayPal
        }

        return [$amount_display, $amount_usd];
    }

    public function processSubscriptionPayment(Request $request)
    {
        // Check if gateway was initialized properly
        if (!$this->gateway) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway not configured properly. Please contact support.',
            ], 500);
        }

        try {
            // Get validation data from session
            $validationData = session('payment_validation');

            if (!$validationData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment session expired. Please restart the payment process.',
                ], 400);
            }

            // Validate session timestamp (prevent stale requests)
            if (now()->timestamp - $validationData['timestamp'] > 1800) { // 30 minutes
                session()->forget('payment_validation');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment session expired. Please restart the payment process.',
                ], 400);
            }

            $choosed_currency = $request->choosed_currency ?? 'usd';
            $amount_display = $request->amount_display;
            $amount_usd = $request->amount_usd;
            $package_type = $request->package_type;
            $coupon_code = $request->coupon_code;

            // SECURITY VALIDATION: Compare with session data to prevent tampering
            if (
                $package_type !== $validationData['package'] ||
                $choosed_currency !== $validationData['currency'] ||
                floatval($amount_usd) !== floatval($validationData['amount_usd']) ||
                floatval($amount_display) !== floatval($validationData['amount_display']) ||
                $coupon_code !== $validationData['coupon_code']
            ) {

                Log::warning('Payment data tampering detected', [
                    'expected_package' => $validationData['package'],
                    'received_package' => $package_type,
                    'expected_currency' => $validationData['currency'],
                    'received_currency' => $choosed_currency,
                    'expected_amount_usd' => $validationData['amount_usd'],
                    'received_amount_usd' => $amount_usd,
                    'expected_amount_display' => $validationData['amount_display'],
                    'received_amount_display' => $amount_display,
                    'expected_coupon_code' => $validationData['coupon_code'],
                    'received_coupon_code' => $coupon_code,
                    'manufacturer' => Auth::guard('manufacturer')->user()->manufacturer_uid
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment data detected. Please restart the payment process.',
                ], 400);
            }

            // Get fresh data from database for validation
            $web_info = WebsiteInformation::where('id', 1)->first();
            if (!$web_info) {
                return response()->json([
                    'success' => false,
                    'message' => 'Website configuration not found.',
                ], 400);
            }

            $exchange_rate = $web_info->exchange_rate;
            $base_currency = $web_info->currency;

            // Validate exchange rate
            if (!$exchange_rate || $exchange_rate <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Exchange rate not configured properly.',
                ], 400);
            }

            // Recalculate expected amounts using fresh database data
            $expected_amounts = $this->recalculateExpectedAmounts(
                $base_currency,
                $choosed_currency,
                $amount_display,
                $amount_usd,
                $exchange_rate,
                $validationData
            );

            if (!$expected_amounts['valid']) {
                Log::warning('Currency conversion validation failed', [
                    'base_currency' => $base_currency,
                    'chosen_currency' => $choosed_currency,
                    'amount_display' => $amount_display,
                    'amount_usd' => $amount_usd,
                    'exchange_rate' => $exchange_rate,
                    'expected_display' => $expected_amounts['expected_display'],
                    'expected_usd' => $expected_amounts['expected_usd']
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Currency conversion validation failed. Please restart the payment process.',
                ], 400);
            }

            Log::info('Starting PayPal payment process', [
                'amount_display' => $amount_display,
                'amount_usd' => $amount_usd,
                'display_currency' => $choosed_currency,
                'base_currency' => $base_currency,
                'package' => $package_type,
                'manufacturer' => Auth::guard('manufacturer')->user()->manufacturer_uid,
                'paypal_mode' => $web_info->PAYPAL_MODE ?? 'sandbox'
            ]);

            // PayPal always processes in USD
            $response = $this->gateway->purchase([
                'amount' => number_format($amount_usd, 2, '.', ''),
                'currency' => 'USD',
                'returnUrl' => route('manufacturer.subscription-success'),
                'cancelUrl' => route('manufacturer.subscription-cancel'),
                'description' => $package_type . ' Subscription Package',
            ])->send();

            Log::info('PayPal purchase response', [
                'isRedirect' => $response->isRedirect(),
                'message' => $response->getMessage(),
                'data' => $response->getData()
            ]);

            if ($response->isRedirect()) {
                // Store temporary session data
                session([
                    'payment_data' => [
                        'manufacturer_uid' => Auth::guard('manufacturer')->user()->manufacturer_uid,
                        'package_type' => $package_type,
                        'amount_usd' => $amount_usd,
                        'amount_display' => $amount_display,
                        'currency' => $choosed_currency,
                        'coupon_code' => $coupon_code,
                        'billing_name' => $request->full_name,
                        'billing_email' => $request->email,
                    ]
                ]);

                // Clear validation session
                session()->forget('payment_validation');

                return response()->json([
                    'success' => true,
                    'redirect_url' => $response->getRedirectUrl(),
                ]);
            } else {
                Log::error('PayPal purchase failed', [
                    'message' => $response->getMessage(),
                    'data' => $response->getData()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'PayPal error: ' . $response->getMessage(),
                ], 400);
            }
        } catch (\Throwable $th) {
            Log::error('PayPal payment exception', [
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment initialization failed: ' . $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Recalculate expected amounts for validation using fresh database data
     */
    private function recalculateExpectedAmounts($base_currency, $choosed_currency, $amount_display, $amount_usd, $exchange_rate, $validationData)
    {
        $tolerance = 0.01; // 1 cent tolerance for USD, about 15 KRW tolerance
        
        // Since we don't have the original base amount, we need to validate the relationship
        // between display amount and USD amount based on the currency combination
        
        $valid = false;
        $expected_display = 0;
        $expected_usd = 0;

        // Case 1: Base currency and chosen currency are both KRW
        if ($base_currency == 'krw' && $choosed_currency == 'krw') {
            // For KRW→KRW: amount_usd should be amount_display / exchange_rate
            $expected_usd = $amount_display / $exchange_rate;
            $valid = (abs($amount_usd - $expected_usd) < $tolerance);
        }
        // Case 2: Base currency is KRW, chosen currency is USD
        elseif ($base_currency == 'krw' && $choosed_currency == 'usd') {
            // For KRW→USD: amount_display should be approximately amount_usd
            $valid = (abs($amount_display - $amount_usd) < $tolerance);
        }
        // Case 3: Base currency is USD, chosen currency is KRW
        elseif ($base_currency == 'usd' && $choosed_currency == 'krw') {
            // For USD→KRW: amount_display should be amount_usd * exchange_rate
            $expected_display = $amount_usd * $exchange_rate;
            $valid = (abs($amount_display - $expected_display) < ($exchange_rate * $tolerance));
        }
        // Case 4: Base currency and chosen currency are both USD
        elseif ($base_currency == 'usd' && $choosed_currency == 'usd') {
            // For USD→USD: amounts should be equal
            $valid = (abs($amount_display - $amount_usd) < $tolerance);
        }

        return [
            'valid' => $valid,
            'expected_display' => $expected_display,
            'expected_usd' => $expected_usd
        ];
    }

    public function subscriptionSuccess(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $paymentData = session('payment_data');

            if (!$paymentData) {
                return redirect('/manufacturer/subscription-cancel')
                    ->with('error', 'Payment session expired. Please try again.');
            }

            // Re-initialize gateway to ensure fresh configuration
            $this->initializePayPalGateway();

            if (!$this->gateway) {
                return redirect('/manufacturer/subscription-cancel')
                    ->with('error', 'Payment gateway configuration error. Please contact support.');
            }

            $transaction = $this->gateway->completePurchase([
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ]);

            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $arr = $response->getData();

                $manufacturer_uid = $paymentData['manufacturer_uid'];
                $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

                $subscriptionEndDate = $this->calculateSubscriptionEndDate($paymentData['package_type']);

                // Update manufacturer
                $manufacturer->update([
                    'subscription' => 1,
                    'subscription_type' => $paymentData['package_type'],
                    'coupon_code' => $paymentData['coupon_code'] ?? null,
                    'paypal_payer_id' => $arr['payer']['payer_info']['payer_id'],
                    'paypal_payment_id' => $arr['id'],
                    'subscription_start_date' => now(),
                    'subscription_end_date' => $subscriptionEndDate,
                ]);

                // Create payment record with display amount in selected currency
                PaymentRecord::create([
                    'manufacturer_uid' => $manufacturer_uid,
                    'paypal_payment_id' => $arr['id'],
                    'paypal_payer_id' => $arr['payer']['payer_info']['payer_id'],
                    'paypal_order_id' => $request->input('paymentId'),
                    'paypal_transaction_id' => $arr['transactions'][0]['related_resources'][0]['sale']['id'] ?? $arr['id'],
                    'package_type' => $paymentData['package_type'],
                    'amount' => $paymentData['amount_display'],
                    'currency' => $paymentData['currency'],
                    'payment_status' => 'completed',
                    'coupon_code' => $paymentData['coupon_code'] ?? null,
                    'billing_name' => $paymentData['billing_name'],
                    'billing_email' => $paymentData['billing_email'],
                    'payment_method' => 'paypal',
                    'paypal_response' => json_encode($arr),
                    'payment_date' => now(),
                    'subscription_end_date' => $subscriptionEndDate,
                ]);

                // Clear session data
                session()->forget('payment_data');

                return redirect('/manufacturer/manage-subscription')
                    ->with('success', 'Congrats! You have successfully subscribed to our platform.');
            } else {
                return redirect('/manufacturer/subscription-cancel')
                    ->with('error', 'Payment failed: ' . $response->getMessage());
            }
        } else {
            return redirect('/manufacturer/subscription-cancel')
                ->with('error', 'Payment was cancelled or invalid payment data received.');
        }
    }

    public function subscriptionCancel()
    {
        session()->forget('payment_data');
        session()->forget('payment_validation');
        return redirect('/manufacturer/packages')
            ->with('error', 'Payment was cancelled. Please try again.');
    }

    protected function calculateSubscriptionEndDate($packageType)
    {
        switch ($packageType) {
            case 'monthly':
                return Carbon::now()->addMonth();
            case '6months':
                return Carbon::now()->addMonths(6);
            case 'yearly':
                return Carbon::now()->addYear();
            default:
                return Carbon::now()->addMonth();
        }
    }

    public function manageSubscription()
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $payment_records = PaymentRecord::where('manufacturer_uid', $manufacturer_uid)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manufacturer.manage_subscription', compact('manufacturer', 'payment_records'));
    }

    public function cancelSubscription(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        if (!$manufacturer->subscription) {
            return redirect()->back()->with('error', 'No active subscription found to cancel.');
        }

        try {
            $manufacturer->update([
                'subscription' => 0,
                'subscription_type' => null,
                'coupon_code' => null,
                'subscription_end_date' => now(),
            ]);

            PaymentRecord::where('manufacturer_uid', $manufacturer_uid)
                ->where('payment_status', 'completed')
                ->update([
                    'payment_status' => 'canceled',
                ]);

            return redirect()->back()
                ->with('success', 'Your subscription has been cancelled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    public function getSubscriptionDetails(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        if (!$manufacturer->subscription) {
            return response()->json([
                'success' => false,
                'message' => 'No active subscription found'
            ]);
        }

        return response()->json([
            'success' => true,
            'subscription' => [
                'status' => $manufacturer->subscription ? 'active' : 'inactive',
                'package_type' => $manufacturer->subscription_type,
                'start_date' => $manufacturer->subscription_start_date,
                'end_date' => $manufacturer->subscription_end_date,
                'days_remaining' => $manufacturer->subscription_end_date
                    ? Carbon::now()->diffInDays(Carbon::parse($manufacturer->subscription_end_date), false)
                    : 0,
            ]
        ]);
    }
}