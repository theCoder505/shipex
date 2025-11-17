<?php

namespace App\Http\Controllers\wholesaler;

use App\Http\Controllers\Controller;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CredentialsController extends Controller
{



    public function wholesalerSignupPages()
    {
        if (Auth::guard('wholesaler')->check()) {
            return redirect('/');
        } else {
            return view('surface.account.wholesaler_signup');
        }
    }





    public function verifySignUp(Request $request)
    {
        $wholesaler_uid = 'WS_' . rand(11111111, 99999999);
        $email = $request['email'];
        $password = $request['password'];
        $otp = rand(111111, 999999);
        $wholesaler = Wholesaler::where('email', $email)->first();

        $data = [
            'email' => $email,
            'otp' => $otp,
        ];

        Mail::send('mail.wholesaler_signup_otp', $data, function ($message) use ($email) {
            $message->to($email)->subject("Your OTP Code for Sign-Up");
        });

        if ($wholesaler) {
            $wholesaler = Wholesaler::where('email', $email)->update([
                'password' => bcrypt($password),
                'otp' => $otp,
            ]);
        } else {
            $wholesaler = Wholesaler::create([
                'wholesaler_uid' => $wholesaler_uid,
                'email' => $email,
                'password' => bcrypt($password),
                'otp' => $otp,
            ]);
        }


        return response()->json([
            'type' => 'success',
            'message' => 'OTP Sent To Your Email Address For Verification!',
        ]);
    }















    public function OTPVerification(Request $request)
    {
        $email = $request['email'];
        $otp = $request['otp'];
        $wholesaler = Wholesaler::where('email', $email)->where('otp', $otp)->first();

        if ($wholesaler) {
            $wholesaler->status = 1;
            $wholesaler->save();
            Auth::guard('wholesaler')->login($wholesaler);

            return response()->json([
                'type' => 'success',
                'message' => 'OTP Verification Successful!',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'OTP Invalid! Try again!',
            ]);
        }
    }






    public function wholesalerForgetPwd()
    {
        if (Auth::guard('wholesaler')->check()) {
            return redirect('/');
        } else {
            $user_type = 'wholesaler';
            return view('surface.account.forget_pwd', compact('user_type'));
        }
    }





    public function wholesalerForgetPwdRequest(Request $request)
    {
        $email = $request->input('email');
        $wholesaler = Wholesaler::where('email', $email)->first();

        if ($wholesaler) {
            $verification_token = Str::random(60);
            $wholesaler->verification_token = $verification_token;
            $wholesaler->save();

            $data = [
                'brandname' => config('app.name'),
                'company_name' => $wholesaler->company_name,
                'verification_link' => url('/wholesaler/verify-reset-password/' . $verification_token),
            ];

            Mail::send('mail.pass_retrive_link', $data, function ($message) use ($email) {
                $message->to($email)->subject("Reset Your Password");
            });

            return redirect('/wholesaler/reset-link-sent')
                ->with('success', 'Reset password link sent successfully to your email!');
        } else {
            return redirect()->back()
                ->with('error', 'Email not found in our records!')->with('email', $email);
        }
    }









    public function resetLinkSent()
    {
        return view('surface.account.reset_link_sent');
    }




    public function verifyResetPassword($reset_token)
    {
        $wholesaler = Wholesaler::where('verification_token', $reset_token)->first();
        if ($wholesaler) {
            $user_email = $wholesaler->email;
            $user_type = 'wholesaler';
            return view('surface.account.reset_password', compact('user_email', 'user_type', 'reset_token'));
        } else {
            return redirect('/wholesaler/forget-password')->with('error', 'Token Invalid! Try again!');
        }
    }



    public function resetPassword(Request $request)
    {
        $email = $request['email'];
        $reset_token = $request['reset_token'];
        $password = $request['password'];
        $c_password = $request['c_password'];

        if ($password == $c_password) {
            $wholesaler = Wholesaler::where('email', $email)->where('verification_token', $reset_token)->first();
            if ($wholesaler) {
                $wholesaler->password = bcrypt($password);
                $wholesaler->save();
                $user_type = 'wholesaler';
                return view('surface.account.password_changed', compact('user_type'));
            } else {
                return redirect('/wholesaler/forget-password') > with('error', 'Wholesaler not found! Try again!');
            }
        } else {
            return redirect()->back()->with('error', 'Passwords not same! Ensure both passwords are same.');
        }
    }





    public function completeWholeSalerProfile()
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $wholesaler = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->first();
        return view('surface.account.wholesaler_profile_complete', compact('wholesaler'));
    }





    public function wholesalerProfileSetup(Request $request)
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $wholesaler = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->first();

        $company_name = $request['company_name'];
        $businessType = $request['businessType'];
        $industryFocus = $request['industryFocus'];
        $country = $request['country'];
        $category = $request['category'];

        $wholesaler->company_name = $company_name;
        $wholesaler->business_type = $businessType;
        $wholesaler->industry_focus = $industryFocus;
        $wholesaler->country = $country;
        $wholesaler->category = $category;
        $wholesaler->save();

        return redirect('/')->with('success', 'Profile Setup Successful!');
    }





    public function logoutWholeSaler(Request $request)
    {
        Auth::guard('wholesaler')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/wholesaler/login')->with('success', 'Logged out successfully!');
    }






    public function wholesalerSignInPage()
    {
        if (Auth::guard('wholesaler')->check()) {
            return redirect('/');
        } else {
            $user_type = 'wholesaler';
            return view('surface.account.signin', compact('user_type'));
        }
    }




    public function wholesalerSignInVerification(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];
        $wholesaler = Wholesaler::where('email', $email)->first();
        $creation_with = $wholesaler->creation_with;
        if ($wholesaler) {
            if (password_verify($password, $wholesaler->password)) {
                if ($wholesaler->status == 3) {
                    return redirect()->back()->with('error', 'Your account has been restricted. Please contact support for more information.')->with('email', $email)->with('password', $password);
                } elseif ($wholesaler->status == 0) {
                    return redirect()->back()->with('error', 'Your account is not yet activated. Please check your email for the activation link.')->with('email', $email)->with('password', $password);
                } else {
                    Auth::guard('wholesaler')->login($wholesaler);
                    return redirect('/')->with('success', 'Logged In Successful!');
                }
            } else {
                return redirect()->back()->with('error_password', 'Password did not match')->with('email', $email)->with('password', $password);
            }
        } else {
            if ($creation_with == 'google') {
                return redirect('/wholesaler/login')->with('error', 'You already have an account with this email: ' . $email . ' with Google. Try logging with Google!');
            } elseif ($creation_with == 'kakao') {
                return redirect('/wholesaler/login')->with('error', 'You already have an account with this email: ' . $email . ' with Kakao. Try logging with Kakao Talk!');
            } else {
                return redirect()->back()->with('error_email', 'Email did not match')->with('email', $email)->with('password', $password);
            }
        }
    }

















    public function wholesalerProfile($page_type)
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $wholesaler = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->first();
        $page_type = $page_type;
        return view('wholesalers.profile', compact('wholesaler_uid', 'wholesaler', 'page_type'));
    }



    public function changeProfilePicture(Request $request)
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $wholesaler = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->first();

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads/wholesalers/profile_images/' . $filename;
            $file->move(public_path('uploads/wholesalers/profile_images/'), $filename);

            if ($wholesaler->profile_picture && file_exists(public_path($wholesaler->profile_picture))) {
                unlink(public_path($wholesaler->profile_picture));
            }

            $wholesaler->profile_picture = $filePath;
            $wholesaler->save();
        }

        return redirect()->back()->with('success', 'Profile picture successfully updated.');
    }






    public function changeEmailAddress(Request $request)
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        // $update = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->update([
        //     'email' => $request['email_addr']
        // ]);
        return redirect()->back()->with('success', 'Email address successfully changed.');
    }



    public function changeAccountPassword(Request $request)
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $old_pwd = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->password();
        $current_password = $request['current_password'];
        $password = $request['password'];
        $password_confirmation = $request['password_confirmation'];

        if (password_verify($current_password, $old_pwd)) {
            if ($password == $password_confirmation) {
                // $update = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->update([
                //     'password' => bcrypt($request['password'])
                // ]);
                return redirect()->back()->with('success', 'Account login password successfully changed.');
            } else {
                return redirect()->back()->with('error', 'Ensure new password matches with confirm password!');
            }
        } else {
            return redirect()->back()->with('error', 'Current Password Did Not Match!');
        }
    }


    public function changeLanguageChoice(Request $request)
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $update = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->update([
            'language' => $request['language']
        ]);
        return redirect()->back()->with('success', 'Your default language has been changed successfully');
    }





    public function deleteAccount(Request $request)
    {
        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        // $delete = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->delete();
        Auth::guard('wholesaler')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/wholesaler/account-deleted');
    }




    public function deletedAccountPage()
    {
        return view('surface.account.account_deleted');
    }



    //
}
