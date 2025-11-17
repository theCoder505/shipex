<?php

namespace App\Http\Controllers\menufacturer;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MenufacturerCredentialsController extends Controller
{






    public function manufacturerSignUpPages()
    {
        if (Auth::guard('manufacturer')->check()) {
            return redirect('/');
        } else {
            return view('surface.account.manufacturer_signup');
        }
    }









    public function verifySignUp(Request $request)
    {
        $manufacturer_uid = 'ManuFacturer_' . rand(111111, 999999);
        $email = $request['email'];
        $password = $request['password'];
        $otp = rand(111111, 999999);
        $manufacturer = Manufacturer::where('email', $email)->first();

        $data = [
            'email' => $email,
            'otp' => $otp,
        ];

        if ($manufacturer->creation_with == 'google') {
            return response()->json([
                'type' => 'error',
                'message' => 'You already have an account with this email: ' . $email . ' with Google. Try logging with Google!',
            ]);
        } elseif ($manufacturer->creation_with == 'kakao') {
            return response()->json([
                'type' => 'error',
                'message' => 'You already have an account with this email: ' . $email . ' with Kakao. Try logging with Kakao Talk!',
            ]);
        }

        Mail::send('mail.manufacturer_signup_otp', $data, function ($message) use ($email) {
            $message->to($email)->subject("Your OTP Code for Sign-Up");
        });

        if ($manufacturer) {
            $manufacturer = Manufacturer::where('email', $email)->update([
                'password' => bcrypt($password),
                'otp' => $otp,
            ]);
        } else {
            $manufacturer = Manufacturer::create([
                'manufacturer_uid' => $manufacturer_uid,
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
        $manufacturer = Manufacturer::where('email', $email)->where('otp', $otp)->first();

        if ($manufacturer) {
            $manufacturer->status = 1;
            $manufacturer->save();
            Auth::guard('manufacturer')->login($manufacturer);

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














    public function completeApplication(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $profile_data = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $step = $request->query('step', 1);

        return view('surface.account.menufacturer_profile_complete', compact('profile_data', 'step'));
    }







    public function completeApplicationSubmit(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        // Get all request data
        $data = $request->except(['_token']);

        // Handle single file uploads
        $this->handleSingleFileUpload($request, $data, 'company_logo', 'company_logo/', $manufacturer);
        $this->handleSingleFileUpload($request, $data, 'business_registration_license', 'business_license/', $manufacturer);
        $this->handleSingleFileUpload($request, $data, 'catalogue', 'catalogue/', $manufacturer);

        // Handle products with images
        if ($request->has('products')) {
            $data['products'] = $this->handleProductsUpload($request, $manufacturer);
        }

        // Handle certifications with documents
        if ($request->has('certifications')) {
            $data['certifications'] = $this->handleCertificationsUpload($request, $manufacturer);
        }

        // Handle patents with documents
        if ($request->has('patents')) {
            $data['patents'] = $this->handlePatentsUpload($request, $manufacturer);
        }

        // Handle factory pictures with images
        if ($request->has('factory_pictures')) {
            $data['factory_pictures'] = $this->handleFactoryPicturesUpload($request, $manufacturer);
        }

        // Handle standards array
        $data['standards'] = $request->has('standards') ? $request->standards : [];

        // Handle boolean fields
        $data['agree_terms'] = $request->has('agree_terms');
        $data['consent_background_check'] = $request->has('consent_background_check');

        // Convert numeric fields to integers
        $data['year_established'] = isset($data['year_established']) ? intval($data['year_established']) : null;
        $data['number_of_employees'] = isset($data['number_of_employees']) ? intval($data['number_of_employees']) : null;
        $data['export_years'] = isset($data['export_years']) ? intval($data['export_years']) : null;
        $data['production_capacity'] = isset($data['production_capacity']) ? intval($data['production_capacity']) : null;
        $data['moq'] = isset($data['moq']) ? intval($data['moq']) : null;

        // Update manufacturer
        $manufacturer->update($data);

        return redirect('/manufacturer/application-successful')->with('success', 'Profile Updated Successfully!');
    }

    /**
     * Handle single file upload with old file preservation
     */
    private function handleSingleFileUpload(Request $request, &$data, $fieldName, $uploadPath, $manufacturer)
    {
        if ($request->hasFile($fieldName)) {
            // Delete old file if exists
            if ($manufacturer->$fieldName && file_exists(public_path($manufacturer->$fieldName))) {
                unlink(public_path($manufacturer->$fieldName));
            }

            $file = $request->file($fieldName);
            $extension = $file->getClientOriginalExtension();
            $filename = $fieldName . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $file->move(public_path($uploadPath), $filename);
            $data[$fieldName] = $uploadPath . $filename;
        } else {
            // Keep old file if exists
            if ($manufacturer->$fieldName) {
                $data[$fieldName] = $manufacturer->$fieldName;
            }
        }
    }

    /**
     * Handle products upload with old data preservation
     */
    private function handleProductsUpload(Request $request, $manufacturer)
    {
        $oldProducts = $manufacturer->products ?? [];
        $products = [];

        foreach ($request->products as $index => $productData) {
            $product = [
                'name' => $productData['name'] ?? ''
            ];

            // Check if new image is uploaded
            if ($request->hasFile("products.{$index}.image")) {
                // Delete old image if exists
                if (isset($oldProducts[$index]['image']) && file_exists(public_path($oldProducts[$index]['image']))) {
                    unlink(public_path($oldProducts[$index]['image']));
                }

                $file = $request->file("products.{$index}.image");
                $extension = $file->getClientOriginalExtension();
                $filename = 'product_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'products/';
                $file->move(public_path($path), $filename);
                $product['image'] = $path . $filename;
            } else {
                // Keep old image if exists
                if (isset($oldProducts[$index]['image'])) {
                    $product['image'] = $oldProducts[$index]['image'];
                }
            }

            $products[] = $product;
        }

        return $products;
    }

    /**
     * Handle certifications upload with old data preservation
     */
    private function handleCertificationsUpload(Request $request, $manufacturer)
    {
        $oldCertifications = $manufacturer->certifications ?? [];
        $certifications = [];

        foreach ($request->certifications as $index => $certificationData) {
            $certification = [
                'name' => $certificationData['name'] ?? ''
            ];

            // Check if new document is uploaded
            if ($request->hasFile("certifications.{$index}.document")) {
                // Delete old document if exists
                if (isset($oldCertifications[$index]['document']) && file_exists(public_path($oldCertifications[$index]['document']))) {
                    unlink(public_path($oldCertifications[$index]['document']));
                }

                $file = $request->file("certifications.{$index}.document");
                $extension = $file->getClientOriginalExtension();
                $filename = 'certification_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'certifications/';
                $file->move(public_path($path), $filename);
                $certification['document'] = $path . $filename;
            } else {
                // Keep old document if exists
                if (isset($oldCertifications[$index]['document'])) {
                    $certification['document'] = $oldCertifications[$index]['document'];
                }
            }

            $certifications[] = $certification;
        }

        return $certifications;
    }

    /**
     * Handle patents upload with old data preservation
     */
    private function handlePatentsUpload(Request $request, $manufacturer)
    {
        $oldPatents = $manufacturer->patents ?? [];
        $patents = [];

        foreach ($request->patents as $index => $patentData) {
            $patent = [
                'description' => $patentData['description'] ?? ''
            ];

            // Check if new document is uploaded
            if ($request->hasFile("patents.{$index}.document")) {
                // Delete old document if exists
                if (isset($oldPatents[$index]['document']) && file_exists(public_path($oldPatents[$index]['document']))) {
                    unlink(public_path($oldPatents[$index]['document']));
                }

                $file = $request->file("patents.{$index}.document");
                $extension = $file->getClientOriginalExtension();
                $filename = 'patent_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'patents/';
                $file->move(public_path($path), $filename);
                $patent['document'] = $path . $filename;
            } else {
                // Keep old document if exists
                if (isset($oldPatents[$index]['document'])) {
                    $patent['document'] = $oldPatents[$index]['document'];
                }
            }

            $patents[] = $patent;
        }

        return $patents;
    }

    /**
     * Handle factory pictures upload with old data preservation
     */
    private function handleFactoryPicturesUpload(Request $request, $manufacturer)
    {
        $oldFactoryPictures = $manufacturer->factory_pictures ?? [];
        $factoryPictures = [];

        foreach ($request->factory_pictures as $index => $pictureData) {
            $picture = [
                'title' => $pictureData['title'] ?? ''
            ];

            // Check if new image is uploaded
            if ($request->hasFile("factory_pictures.{$index}.image")) {
                // Delete old image if exists
                if (isset($oldFactoryPictures[$index]['image']) && file_exists(public_path($oldFactoryPictures[$index]['image']))) {
                    unlink(public_path($oldFactoryPictures[$index]['image']));
                }

                $file = $request->file("factory_pictures.{$index}.image");
                $extension = $file->getClientOriginalExtension();
                $filename = 'factory_picture_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'factory_pictures/';
                $file->move(public_path($path), $filename);
                $picture['image'] = $path . $filename;
            } else {
                // Keep old image if exists
                if (isset($oldFactoryPictures[$index]['image'])) {
                    $picture['image'] = $oldFactoryPictures[$index]['image'];
                }
            }

            $factoryPictures[] = $picture;
        }

        return $factoryPictures;
    }




    public function applicationSuccessful()
    {
        return view('surface.account.manufacturer_app_success');
    }





    public function logoutManufacturer(Request $request)
    {
        Auth::guard('manufacturer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/manufacturer/login')->with('success', 'Logged out successfully!');
    }





    public function changeEmailAddress(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $update = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->update([
            'email' => $request['email_addr']
        ]);
        return redirect()->back()->with('success', 'Email address successfully changed.');
    }



    public function changeAccountPassword(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $update = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->update([
            'password' => bcrypt($request['password'])
        ]);
        return redirect()->back()->with('success', 'Account login password successfully changed.');
    }


    public function changeLanguageChoice(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $update = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->update([
            'language' => $request['language']
        ]);
        return redirect()->back()->with('success', 'Your default language has been changed successfully');
    }





    public function deleteAccount(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $delete = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->delete();
        Auth::guard('manufacturer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/manufacturer/account-deleted');
    }




    public function deletedAccountPage()
    {
        return view('surface.account.account_deleted');
    }







    public function manufacturerSigninPage()
    {
        if (Auth::guard('manufacturer')->check()) {
            return redirect('/');
        } else {
            $user_type = 'manufacturer';
            return view('surface.account.signin', compact('user_type'));
        }
    }








    public function manufacturerSignInVerfication(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];
        $manufacturer = Manufacturer::where('email', $email)->first();
        $creation_with = $manufacturer->creation_with ?? null;
        if ($manufacturer) {
            if (password_verify($password, $manufacturer->password)) {
                Auth::guard('manufacturer')->login($manufacturer);
                return redirect('/')->with('success', 'Logged In Successful!');
            } else {
                return redirect()->back()->with('error_password', 'Password did not match')->with('email', $email)->with('password', $password);
            }
        } else {
            if ($creation_with == 'google') {
                return redirect('/manufacturer/login')->with('error', 'You already have an account with this email: ' . $email . ' with Google. Try logging with Google!');
            } elseif ($creation_with == 'kakao') {
                return redirect('/manufacturer/login')->with('error', 'You already have an account with this email: ' . $email . ' with Kakao. Try logging with Kakao Talk!');
            } else {
                return redirect()->back()->with('error_email', 'Email did not match')->with('email', $email)->with('password', $password);
            }
        }
    }












    public function manufacturerForgetPwd()
    {
        $user_type = 'manufacturer';
        return view('surface.account.forget_pwd', compact('user_type'));
    }




    public function manufacturerForgetPwdRequest(Request $request)
    {
        $email = $request->input('email');
        $manufacturer = Manufacturer::where('email', $email)->first();

        if ($manufacturer) {
            $verification_link = Str::random(60);
            $manufacturer->verification_link = $verification_link;
            $manufacturer->save();

            $data = [
                'brandname' => config('app.name'),
                'company_name' => $manufacturer->company_name_en,
                'verification_link' => url('/manufacturer/verify-reset-password/' . $verification_link),
            ];

            Mail::send('mail.pass_retrive_link', $data, function ($message) use ($email) {
                $message->to($email)->subject("Reset Your Password");
            });

            return redirect('/manufacturer/reset-link-sent')
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
        $manufacturer = Manufacturer::where('verification_link', $reset_token)->first();
        if ($manufacturer) {
            $user_email = $manufacturer->email;
            $user_type = 'manufacturer';
            return view('surface.account.reset_password', compact('user_email', 'user_type', 'reset_token'));
        } else {
            return redirect('/manufacturer/forget-password')->with('error', 'Token Invalid! Try again!');
        }
    }



    public function resetPassword(Request $request)
    {
        $email = $request['email'];
        $reset_token = $request['reset_token'];
        $password = $request['password'];
        $c_password = $request['c_password'];

        if ($password == $c_password) {
            $manufacturer = Manufacturer::where('email', $email)->where('verification_link', $reset_token)->first();
            if ($manufacturer) {
                $manufacturer->password = bcrypt($password);
                $manufacturer->save();
                $user_type = 'manufacturer';
                return view('surface.account.password_changed', compact('user_type'));
            } else {
                return redirect('/manufacturer/forget-password') > with('error', 'manufacturer not found! Try again!');
            }
        } else {
            return redirect()->back()->with('error', 'Passwords not same! Ensure both passwords are same.');
        }
    }












    //
}
