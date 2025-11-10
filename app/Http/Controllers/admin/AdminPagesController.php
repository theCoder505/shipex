<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CouponCode;
use App\Models\FAQ;
use App\Models\Manufacturer;
use App\Models\PaymentRecord;
use App\Models\Reviews;
use App\Models\WebsiteInformation;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Stripe\Review;

class AdminPagesController extends Controller
{





    public function adminDashboard()
    {
        $admin = Admin::where('id', 1)->first();
        $manufacturers = Manufacturer::orderBy('id', 'DESC')->get();
        $wholesalers = Wholesaler::orderBy('id', 'DESC')->get();
        return view('admin.dashboard', compact('admin', 'manufacturers', 'wholesalers'));
    }



    public function showManufacturers()
    {
        $manufacturers = Manufacturer::orderBy('id', 'DESC')->get();
        return view('admin.manufactures', compact('manufacturers'));
    }




    public function changeManufacturerStatus(Request $request)
    {
        $manufacturer_uid = $request['manufacturer_uid'];
        $status = $request['status'];
        $admin_comment = $request['admin_comment'];
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $manufacturer->status = $status;
        $manufacturer->admin_comment = $admin_comment;
        $manufacturer->save();

        $manufacturer_mail = $manufacturer->email;
        // $manufacturer_mail = 'eksofts7867@gmail.com';
        $company_name_en = $manufacturer->company_name_en;
        $data = [
            'monthly_fee_amount' => WebsiteInformation::where('id', 1)->value('monthly_fee_amount'),
            'half_yearly_fee_amount' => WebsiteInformation::where('id', 1)->value('half_yearly_fee_amount'),
            'yearly_fee_amount' => WebsiteInformation::where('id', 1)->value('yearly_fee_amount'),
            'email' => $manufacturer_mail,
            'company_name' => $company_name_en,
            'status' => $status,
            'admin_comment' => $admin_comment,
        ];
        Mail::send('mail.manufacturer_subscription', $data, function ($message) use ($manufacturer_mail) {
            $message->to($manufacturer_mail)->subject("Notification about application to SHIPEX");
        });

        return redirect()->back()->with('success', 'Manufacturer, UID: ' . $manufacturer_uid . ' Status Changed Successfully!');
    }




    public function showManufacturerReviews($manufacturer_uid)
    {
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $wholesalers = Wholesaler::all();
        $reviews = Reviews::where('manufacturer_uid', $manufacturer_uid)->orderBy('id', 'DESC')->get();
        return view('admin.manufacture_reviews', compact('manufacturer', 'reviews', 'wholesalers'));
    }



    public function reviewToggle($review_id, $option)
    {
        $review = Reviews::where('id', $review_id)->first();
        if ($option == 'show') {
            $review->status = 1;
        } elseif ($option == 'hide') {
            $review->status = 0;
        }
        $review->save();

        return redirect()->back()->with('success', 'Review ID: ' . $review_id . ' status changed successfully!');
    }



    public function deleteReview(Request $request, $review_id)
    {
        Reviews::where('id', $review_id)->delete();
        return redirect()->back()->with('success', 'Review ID: ' . $review_id . ' deleted successfully!');
    }












    public function showWholesalers()
    {
        $wholesalers = Wholesaler::orderBy('id', 'DESC')->get();
        return view('admin.wholesalers', compact('wholesalers'));
    }





    public function toggleWholesalerRestriction(Request $request)
    {
        // Validate the request
        $request->validate([
            'wholesaler_id' => 'required|integer',
            'wholesaler_uid' => 'required|string',
            'action_type' => 'required|in:restrict,unrestrict',
        ]);

        $wholesaler_id = $request->wholesaler_id;
        $wholesaler_uid = $request->wholesaler_uid;
        $action_type = $request->action_type;
        $admin_comment = $request->admin_comment;

        // Find wholesaler by ID
        $wholesaler = Wholesaler::where('id', $wholesaler_id)->first();

        if (!$wholesaler) {
            return redirect()->back()->with('error', 'Wholesaler not found!');
        }

        $wholesaler_email = $wholesaler->email;
        $company_name = $wholesaler->company_name ?? 'N/A';

        // Determine new status based on action type
        if ($action_type === 'restrict') {
            $new_status = 3; // Restricted
            $status_text = 'Restricted';
            $action_message = 'restricted';
        } else {
            $new_status = 1; // Active & Verified
            $status_text = 'Active & Verified';
            $action_message = 'unrestricted';
        }

        // Update wholesaler status and admin comment
        $wholesaler->status = $new_status;
        $wholesaler->admin_comment = $admin_comment;
        $wholesaler->save();

        // Prepare email data
        $data = [
            'brandname' => config('app.name', 'SHIPEX'),
            'company_name' => $company_name,
            'wholesaler_uid' => $wholesaler_uid,
            'email' => $wholesaler_email,
            'status' => $new_status,
            'status_text' => $status_text,
            'admin_comment' => $admin_comment,
            'action_type' => $action_type
        ];

        // Send email notification
        try {
            Mail::send('mail.wholesaler_account_restriction', $data, function ($message) use ($wholesaler_email) {
                $message->to($wholesaler_email)
                    ->subject("Important: Account Status Update - SHIPEX");
            });
        } catch (\Exception $e) {
            Log::error('Failed to send restriction email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Wholesaler UID: ' . $wholesaler_uid . ' has been ' . $action_message . ' successfully!');
    }


















    public function websiteSettingsPage()
    {
        $settings = WebsiteInformation::where('id', 1)->first();
        return view('admin.general_settings', compact('settings'));
    }









    public function updateWebsiteSettings(Request $request)
    {
        $request->validate([
            'brandname' => 'sometimes|string|max:255',
            'brandlogo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
            'website_icon' => 'sometimes|image|max:51200',
            'currency' => 'sometimes|string|max:10',
            'PAYPAL_CLIENT_ID' => 'sometimes|string|max:255',
            'PAYPAL_SECRET' => 'sometimes|string|max:255',
            'PAYPAL_MODE' => 'sometimes|string|max:255',

            'monthly_fee_amount' => 'sometimes|numeric',
            'half_yearly_fee_amount' => 'sometimes|numeric',
            'yearly_fee_amount' => 'sometimes|numeric',

            'open_dys' => 'sometimes|string|max:255',
            'open_time' => 'sometimes|string|max:255',
            'contact_mail' => 'sometimes|email|max:255',
            'contact_phone' => 'sometimes|string|max:20',
            'fb_url' => 'sometimes|url|max:255',
            'twitter_url' => 'sometimes|url|max:255',
            'instagram_url' => 'sometimes|url|max:255',
            'linkedin_url' => 'sometimes|url|max:255',
            'short_desc_about_brand' => 'sometimes|string|max:1000',
            'business_registration_number' => 'sometimes|string|max:255',
            'business_address' => 'sometimes|string|max:500',

            'terms_conditions => sometimes|string',
            'privacy_policy => sometimes|string',
        ]);

        $settings = WebsiteInformation::firstOrNew(['id' => 1]);

        if ($request->hasFile('brandlogo')) {
            if ($settings->brandlogo && Storage::disk('public')->exists($settings->brandlogo)) {
                Storage::disk('public')->delete($settings->brandlogo);
            }

            $brandLogoPath = $request->file('brandlogo')->store('website/logos', 'public');
            $settings->brandlogo = $brandLogoPath;
        }

        if ($request->hasFile('website_icon')) {
            if ($settings->website_icon && Storage::disk('public')->exists($settings->website_icon)) {
                Storage::disk('public')->delete($settings->website_icon);
            }

            $website_iconPath = $request->file('website_icon')->store('website/icons', 'public');
            $settings->website_icon = $website_iconPath;
        }

        $settings->fill($request->except(['brandlogo', 'website_icon', '_token', '_method']));
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Website settings updated successfully!'
        ]);
    }















    public function addNewCouponCode(Request $request)
    {
        $coupon_code = $request['coupon_code'];
        $monthly_fee_amount = $request['monthly_fee_amount'];
        $half_yearly_fee_amount = $request['half_yearly_fee_amount'];
        $yearly_fee_amount = $request['yearly_fee_amount'];
        $type = $request['type'];
        $discount_amount = $request['discount_amount'];
        $discount_percentage = $request['discount_percentage'];

        CouponCode::create([
            'coupon_code' => $coupon_code,
            'monthly_fee_amount' => $monthly_fee_amount,
            'half_yearly_fee_amount' => $half_yearly_fee_amount,
            'yearly_fee_amount' => $yearly_fee_amount,
            'type' => $type,
            'discount_amount' => $discount_amount,
            'discount_percentage' => $discount_percentage,
        ]);

        return redirect()->back()->with('success', 'Coupon code added successfully!');
    }




    public function couponCodes(Request $request)
    {
        $monthly_fee_amount = WebsiteInformation::where('id', 1)->value('monthly_fee_amount');
        $half_yearly_fee_amount = WebsiteInformation::where('id', 1)->value('half_yearly_fee_amount');
        $yearly_fee_amount = WebsiteInformation::where('id', 1)->value('yearly_fee_amount');
        $coupon_codes = CouponCode::orderBy('id', 'DESC')->get();
        return view('admin.coupon_codes', compact(
            'coupon_codes',
            'monthly_fee_amount',
            'half_yearly_fee_amount',
            'yearly_fee_amount',
        ));
    }




    public function updateCouponCode(Request $request)
    {
        $coupon_id = $request['coupon_id'];
        $coupon_code = $request['coupon_code'];
        $monthly_fee_amount = $request['monthly_fee_amount'];
        $half_yearly_fee_amount = $request['half_yearly_fee_amount'];
        $yearly_fee_amount = $request['yearly_fee_amount'];
        $type = $request['type'];
        $discount_amount = $request['discount_amount'];
        $discount_percentage = $request['discount_percentage'];

        CouponCode::where('id', $coupon_id)->update([
            'coupon_code' => $coupon_code,
            'monthly_fee_amount' => $monthly_fee_amount,
            'half_yearly_fee_amount' => $half_yearly_fee_amount,
            'yearly_fee_amount' => $yearly_fee_amount,
            'type' => $type,
            'discount_amount' => $discount_amount,
            'discount_percentage' => $discount_percentage,
        ]);

        return redirect()->back()->with('success', 'Coupon code updated successfully!');
    }




    public function deleteCouponCode(Request $request)
    {
        $coupon_id = $request['coupon_id'];
        CouponCode::where('id', $coupon_id)->delete();
        return redirect()->back()->with('success', 'Coupon code removed successfully!');
    }









    public function adminAccount()
    {
        $admin = Admin::where('id', 1)->first();
        return view('admin.account_settings', compact('admin'));
    }



    public function adminEmailVerification(Request $request)
    {
        $old_email = $request['email'];
        $new_email = $request['new_email'];
        $password = $request['password'];
        $admin = Admin::where('email', $old_email)->first();
        $otp = rand(111111, 999999);

        if ($admin) {
            if (password_verify($password, $admin->password)) {
                $admin->otp = $otp;
                $admin->save();

                $data = [
                    'admin_name' => $admin->name,
                    'otp' => $otp,
                ];

                Mail::send('mail.admin_account_settings', $data, function ($message) use ($old_email) {
                    $message->to($old_email)->subject("Account Settings OTP from SHIPEX");
                });

                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent to admin email, verify now!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin password did not match! Place the correct password please.'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Admin email did not match! Place the correct email please.'
            ]);
        }
    }




    public function verifyEmailChangeOTP(Request $request)
    {
        $old_email = $request['email'];
        $new_email = $request['new_email'];
        $otp = $request['otp'];
        $admin = Admin::where('email', $old_email)->first();

        if ($admin) {
            if ($admin->otp == $otp) {
                $admin->email = $new_email;
                $admin->otp = null;
                $admin->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Admin email updated successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP not matched!'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email not matched!'
            ]);
        }
    }













    public function adminPasswordVerification(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];
        $new_password = $request['new_password'];
        $admin = Admin::where('email', $email)->first();
        $otp = rand(111111, 999999);

        if ($admin) {
            if (password_verify($password, $admin->password)) {
                $admin->otp = $otp;
                $admin->save();

                $data = [
                    'admin_name' => $admin->name,
                    'otp' => $otp,
                ];

                Mail::send('mail.admin_account_settings', $data, function ($message) use ($email) {
                    $message->to($email)->subject("Account Settings OTP from SHIPEX");
                });

                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent to admin email, verify now!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin password did not match! Place the correct password please.'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Admin email did not match! Place the correct email please.'
            ]);
        }
    }




    public function verifyPasswordChangeOTP(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];
        $new_password = $request['new_password'];
        $otp = $request['otp'];
        $admin = Admin::where('email', $email)->first();

        if ($admin) {
            if ($admin->otp == $otp) {
                $admin->password = bcrypt($new_password);
                $admin->otp = null;
                $admin->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Admin password updated successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP not matched!'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Email not matched!'
            ]);
        }
    }










    public function subscriptionRecords()
    {
        $records = PaymentRecord::orderBy('id', 'DESC')->get();
        return view('admin.subscription_records', compact('records'));
    }






    public function showFAQs()
    {
        $records = FAQ::orderBy('id', 'DESC')->get();
        return view('admin.all_faqs', compact('records'));
    }


    public function createFAQ(Request $request)
    {
        $question = $request['question'];
        $answer = $request['answer'];

        FAQ::create([
            'question' => $question,
            'answer' => $answer,
        ]);

        return redirect()->back()->with('success', 'FAQ added successfully!');
    }


    public function updateFAQ(Request $request)
    {
        $id = $request['id'];
        $question = $request['question'];
        $answer = $request['answer'];

        FAQ::where('id', $id)->update([
            'question' => $question,
            'answer' => $answer,
        ]);

        return redirect()->back()->with('success', 'FAQ updated successfully!');
    }


    public function deleteFAQ(Request $request)
    {
        $id = $request['id'];
        FAQ::where('id', $id)->delete();
        return redirect()->back()->with('success', 'FAQ deleted successfully!');
    }

    //
}
