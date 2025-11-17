<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\WebsiteInformation;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class SocialiteAuthConntroller extends Controller
{

    private function getGoogleCredentials()
    {
        return [
            'client_id' => WebsiteInformation::where('id', 1)->value('google_client_id'),
            'client_secret' => WebsiteInformation::where('id', 1)->value('google_client_secret'),
            'redirect' => '',
        ];
    }





    public function manufacturerGoogleSignUp()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/manufacturer/auth/google/callback');

            return Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->redirect();
        } catch (Exception $e) {
            return redirect('/manufacturer/signup')->with('error', 'Unable to connect to Google. Please try again.');
        }
    }






    public function manufacturerGoogleCallback()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/manufacturer/auth/google/callback');

            $user = Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->user();

            $name = $user->getName();
            $email = $user->getEmail();

            $find_user = Manufacturer::where('email', $email)->first();
            if ($find_user) {
                $user_creation_with = Manufacturer::where('email', $email)->value('creation_with');
                if ($user_creation_with == 'general') {
                    return redirect('/manufacturer/signup')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'kakao') {
                    return redirect('/manufacturer/signup')->with('error', 'You already have an account with this email: ' . $email . ' with Kakao. Try logging with Kakao Talk!');
                } else {
                    // varify if not allowed user!

                    $manufacturer = Manufacturer::where('email', $email)->first();
                    Auth::guard('manufacturer')->login($manufacturer);
                    return redirect('/')->with('success', 'Logged In Successful!');
                }
            } else {
                $manufacturer_uid = 'ManuFacturer_' . rand(111111, 999999);
                $manufacturer = Manufacturer::create([
                    'manufacturer_uid' => $manufacturer_uid,
                    'email' => $email,
                    'name' => $name,
                    'status' => 1,
                    'creation_with' => 'google',
                ]);

                Auth::guard('manufacturer')->login($manufacturer);
                return redirect('/manufacturer/application')->with('success', 'Complete your application now.');
            }
        } catch (Exception $e) {
            return redirect('/manufacturer/signup')->with('error', 'Google authentication failed. Please try again.');
        }
    }





    public function manufacturerGoogleLogin()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/manufacturer/auth/google-login-callback');

            return Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->redirect();
        } catch (Exception $e) {
            return redirect('/manufacturer/login')->with('error', 'Unable to connect to Google. Please try again.');
        }
    }





    public function manufacturerGoogleLoginCallback()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/manufacturer/auth/google-login-callback');

            $user = Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->user();

            $name = $user->getName();
            $email = $user->getEmail();

            $find_user = Manufacturer::where('email', $email)->first();
            if ($find_user) {
                $user_creation_with = Manufacturer::where('email', $email)->value('creation_with');
                if ($user_creation_with == 'general') {
                    return redirect('/manufacturer/login')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'kakao') {
                    return redirect('/manufacturer/login')->with('error', 'You already have an account with this email: ' . $email . ' with Kakao. Try logging with Kakao Talk!');
                } else {
                    // varify if not allowed user!
                    $manufacturer = Manufacturer::where('email', $email)->first();
                    Auth::guard('manufacturer')->login($manufacturer);
                    return redirect('/')->with('success', 'Logged In Successful!');
                }
            } else {
                $manufacturer_uid = 'ManuFacturer_' . rand(111111, 999999);
                $manufacturer = Manufacturer::create([
                    'manufacturer_uid' => $manufacturer_uid,
                    'email' => $email,
                    'name' => $name,
                    'status' => 1,
                    'creation_with' => 'google',
                ]);

                Auth::guard('manufacturer')->login($manufacturer);
                return redirect('/manufacturer/application')->with('success', 'Complete your application now.');
            }
        } catch (Exception $e) {
            return redirect('/manufacturer/login')->with('error', 'Google authentication failed. Please try again.');
        }
    }






































    public function wholesalerGoogleSignUp()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/wholesaler/auth/google/callback');

            return Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->redirect();
        } catch (Exception $e) {
            return redirect('/wholesaler/signup')->with('error', 'Unable to connect to Google. Please try again.');
        }
    }



    public function wholesalerGoogleCallback()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/wholesaler/auth/google/callback');

            $user = Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->user();

            $email = $user->getEmail();

            $wholesaler = Wholesaler::where('email', $email)->first();
            $user_creation_with = $wholesaler->creation_with ?? null;;
            if ($wholesaler) {
                if ($user_creation_with == 'general') {
                    return redirect('/wholesaler/signup')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'kakao') {
                    return redirect('/wholesaler/signup')->with('error', 'You already have an account with this email: ' . $email . ' with Kakao. Try logging with Kakao Talk!');
                } else {
                    if ($wholesaler->status == 3) {
                        return redirect('/wholesaler/signup')->with('error', 'Your account has been restricted. Please contact support for more information.');
                    } elseif ($wholesaler->status == 0) {
                        return redirect('/wholesaler/signup')->with('error', 'Your account is not yet activated. Please check your email for the activation link.');
                    } else {
                        Auth::guard('wholesaler')->login($wholesaler);
                        return redirect('/')->with('success', 'Logged In Successful!');
                    }
                }
            } else {
                $wholesaler_uid = 'WS_' . rand(11111111, 99999999);
                $wholesaler = Wholesaler::create([
                    'wholesaler_uid' => $wholesaler_uid,
                    'email' => $email,
                    'status' => 1,
                    'creation_with' => 'google',
                ]);

                Auth::guard('wholesaler')->login($wholesaler);
                return redirect('/wholesaler/profile-setup')->with('success', 'Logged In Successfully. Setup your profile now!');
            }
        } catch (Exception $e) {
            return redirect('/wholesaler/signup')->with('error', 'Google authentication failed. Please try again.');
        }
    }





    public function wholesalerGoogleLogin()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/wholesaler/auth/google-login-callback');

            return Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->redirect();
        } catch (Exception $e) {
            return redirect('/wholesaler/signup')->with('error', 'Unable to connect to Google. Please try again.');
        }
    }



    public function wholesalerGoogleLoginCallback()
    {
        try {
            $credentials = $this->getGoogleCredentials();
            $credentials['redirect'] = url('/wholesaler/auth/google-login-callback');

            $user = Socialite::buildProvider(
                \Laravel\Socialite\Two\GoogleProvider::class,
                $credentials
            )->user();

            $email = $user->getEmail();

            $wholesaler = Wholesaler::where('email', $email)->first();
            $user_creation_with = $wholesaler->creation_with ?? null;
            if ($wholesaler) {
                if ($user_creation_with == 'general') {
                    return redirect('/wholesaler/login')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'kakao') {
                    return redirect('/wholesaler/login')->with('error', 'You already have an account with this email: ' . $email . ' with Kakao. Try logging with Kakao Talk!');
                } else {
                    if ($wholesaler->status == 3) {
                        return redirect('/wholesaler/login')->with('error', 'Your account has been restricted. Please contact support for more information.');
                    } elseif ($wholesaler->status == 0) {
                        return redirect('/wholesaler/login')->with('error', 'Your account is not yet activated. Please check your email for the activation link.');
                    } else {
                        Auth::guard('wholesaler')->login($wholesaler);
                        return redirect('/')->with('success', 'Logged In Successful!');
                    }
                }
            } else {
                $wholesaler_uid = 'WS_' . rand(11111111, 99999999);
                $wholesaler = Wholesaler::create([
                    'wholesaler_uid' => $wholesaler_uid,
                    'email' => $email,
                    'status' => 1,
                    'creation_with' => 'google',
                ]);

                Auth::guard('wholesaler')->login($wholesaler);
                return redirect('/wholesaler/profile-setup')->with('success', 'Logged In Successfully. Setup your profile now!');
            }
        } catch (Exception $e) {
            return $e->getMessage();
            return redirect('/wholesaler/login')->with('error', 'Google authentication failed. Please try again.');
        }
    }
}
