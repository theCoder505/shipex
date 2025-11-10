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
use Illuminate\Support\FacadesLog;

class SubscriptionController extends Controller
{




    private $gateway;

    public function __construct()
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
        $this->gateway->setTestMode(true); // Always use sandbox for now
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

        $monthly_fee_amount = WebsiteInformation::where('id', 1)->value('monthly_fee_amount');
        $half_yearly_fee_amount = WebsiteInformation::where('id', 1)->value('half_yearly_fee_amount');
        $yearly_fee_amount = WebsiteInformation::where('id', 1)->value('yearly_fee_amount');

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

        $paypal_client_id = WebsiteInformation::where('id', 1)->value('PAYPAL_CLIENT_ID');

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
            'paypal_client_id'
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

        $web_info = WebsiteInformation::where('id', 1)->first();
        $paypal_client_id = $web_info->PAYPAL_CLIENT_ID;
        $paypal_mode = $web_info->PAYPAL_MODE ?? 'sandbox';

        $original_monthly_price = $web_info->monthly_fee_amount;
        $original_half_yearly_price = $web_info->half_yearly_fee_amount;
        $original_yearly_price = $web_info->yearly_fee_amount;

        $package_display = $package;
        if ($package == 'monthly') {
            $amount = $original_monthly_price;
        }
        if ($package == '6months') {
            $package_display = 'Half Yearly';
            $amount = $original_half_yearly_price;
        }
        if ($package == 'yearly') {
            $amount = $original_yearly_price;
        }

        $type = '';
        $discount = 0;

        if ($asking_code !== null) {
            $coupon = CouponCode::where('coupon_code', $asking_code)->first();
            if ($coupon) {
                if ($package == 'monthly') {
                    $amount = $coupon->monthly_fee_amount;
                    $type = $coupon->type;
                    if ($type == 'fixed') {
                        $discount = $coupon->discount_amount;
                    } else {
                        $discount = $coupon->discount_percentage;
                    }
                }
                if ($package == '6months') {
                    $amount = $coupon->half_yearly_fee_amount;
                    $type = $coupon->type;
                    if ($type == 'fixed') {
                        $discount = $coupon->discount_amount;
                    } else {
                        $discount = $coupon->discount_percentage;
                    }
                }
                if ($package == 'yearly') {
                    $amount = $coupon->yearly_fee_amount;
                    $type = $coupon->type;
                    if ($type == 'fixed') {
                        $discount = $coupon->discount_amount;
                    } else {
                        $discount = $coupon->discount_percentage;
                    }
                }

                if ($coupon->discount_percentage == 100 || $amount == 0) {
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
                        'currency' => 'usd',
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

        return view('manufacturer.checkout', compact(
            'paypal_client_id',
            'paypal_mode',
            'amount',
            'package',
            'package_display',
            'type',
            'discount',
        ));
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
            Log::info('Starting PayPal payment process', [
                'amount' => $request->amount,
                'package' => $request->package_type,
                'manufacturer' => Auth::guard('manufacturer')->user()->manufacturer_uid
            ]);

            $response = $this->gateway->purchase([
                'amount' => $request->amount,
                'currency' => 'USD',
                'returnUrl' => route('manufacturer.subscription-success'),
                'cancelUrl' => route('manufacturer.subscription-cancel'),
                'description' => $request->package_type . ' Subscription Package',
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
                        'package_type' => $request->package_type,
                        'amount' => $request->amount,
                        'coupon_code' => $request->coupon_code,
                        'billing_name' => $request->full_name,
                        'billing_email' => $request->email,
                    ]
                ]);

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







    public function subscriptionSuccess(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $paymentData = session('payment_data');

            if (!$paymentData) {
                return redirect('/manufacturer/subscription-cancel')
                    ->with('error', 'Payment session expired. Please try again.');
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

                // Create payment record
                PaymentRecord::create([
                    'manufacturer_uid' => $manufacturer_uid,
                    'paypal_payment_id' => $arr['id'],
                    'paypal_payer_id' => $arr['payer']['payer_info']['payer_id'],
                    'paypal_order_id' => $request->input('paymentId'),
                    'paypal_transaction_id' => $arr['transactions'][0]['related_resources'][0]['sale']['id'] ?? $arr['id'],
                    'package_type' => $paymentData['package_type'],
                    'amount' => $paymentData['amount'],
                    'currency' => 'usd',
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
