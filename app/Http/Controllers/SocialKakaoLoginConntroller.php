<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Models\WebsiteInformation;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class SocialKakaoLoginConntroller extends Controller
{
    private function getKakaoCredentials()
    {
        return [
            'client_id' => WebsiteInformation::where('id', 1)->value('kakao_client_id'),
            'client_secret' => WebsiteInformation::where('id', 1)->value('kakao_client_secret'),
            'redirect' => '',
        ];
    }

    // ============================================
    // MANUFACTURER - KAKAO SIGNUP
    // ============================================

    public function manufacturerKakaoSignUp()
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $redirectUrl = url('/manufacturer/auth/kakao/callback');

            // Build Kakao OAuth URL manually
            $kakaoAuthUrl = 'https://kauth.kakao.com/oauth/authorize?' . http_build_query([
                'client_id' => $credentials['client_id'],
                'redirect_uri' => $redirectUrl,
                'response_type' => 'code',
            ]);

            return redirect($kakaoAuthUrl);
        } catch (Exception $e) {
            return redirect('/manufacturer/signup')->with('error', 'Unable to connect to Kakao. Please try again.');
        }
    }

    public function manufacturerKakaoCallback(Request $request)
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $code = $request->get('code');

            if (!$code) {
                return redirect('/manufacturer/signup')->with('error', 'Kakao authentication failed.');
            }

            // Exchange code for access token
            $tokenResponse = \Illuminate\Support\Facades\Http::asForm()->post('https://kauth.kakao.com/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $credentials['client_id'],
                'client_secret' => $credentials['client_secret'],
                'redirect_uri' => url('/manufacturer/auth/kakao/callback'),
                'code' => $code,
            ]);

            $tokenData = $tokenResponse->json();

            if (!isset($tokenData['access_token'])) {
                return redirect('/manufacturer/signup')->with('error', 'Failed to get Kakao access token.');
            }

            // Get user info
            $userResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenData['access_token'],
            ])->get('https://kapi.kakao.com/v2/user/me');

            $userData = $userResponse->json();

            $email = $userData['kakao_account']['email'] ?? null;
            $name = $userData['kakao_account']['profile']['nickname'] ?? 'Kakao User';

            // Validate email
            if (!$email) {
                return redirect('/manufacturer/signup')->with('error', 'Email is required. Please enable email permission in your Kakao account settings.');
            }

            $find_user = Manufacturer::where('email', $email)->first();
            
            if ($find_user) {
                $user_creation_with = Manufacturer::where('email', $email)->value('creation_with');
                if ($user_creation_with == 'general') {
                    return redirect('/manufacturer/signup')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'google') {
                    return redirect('/manufacturer/signup')->with('error', 'You already have an account with this email: ' . $email . ' with Google. Try logging with Google!');
                } else {
                    // Login existing Kakao user
                    $manufacturer = Manufacturer::where('email', $email)->first();
                    Auth::guard('manufacturer')->login($manufacturer);
                    return redirect('/')->with('success', 'Logged In Successful!');
                }
            } else {
                // Create new manufacturer
                $manufacturer_uid = 'ManuFacturer_' . rand(111111, 999999);
                $manufacturer = Manufacturer::create([
                    'manufacturer_uid' => $manufacturer_uid,
                    'email' => $email,
                    'name' => $name,
                    'status' => 1,
                    'creation_with' => 'kakao',
                ]);

                Auth::guard('manufacturer')->login($manufacturer);
                return redirect('/manufacturer/application')->with('success', 'Complete your application now.');
            }
        } catch (Exception $e) {
            return redirect('/manufacturer/signup')->with('error', 'Kakao authentication failed. Please try again.');
        }
    }

    // ============================================
    // MANUFACTURER - KAKAO LOGIN
    // ============================================

    public function manufacturerKakaoLogin()
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $redirectUrl = url('/manufacturer/auth/kakao-login-callback');

            // Build Kakao OAuth URL manually
            $kakaoAuthUrl = 'https://kauth.kakao.com/oauth/authorize?' . http_build_query([
                'client_id' => $credentials['client_id'],
                'redirect_uri' => $redirectUrl,
                'response_type' => 'code',
            ]);

            return redirect($kakaoAuthUrl);
        } catch (Exception $e) {
            return redirect('/manufacturer/login')->with('error', 'Unable to connect to Kakao. Please try again.');
        }
    }

    public function manufacturerKakaoLoginCallback(Request $request)
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $code = $request->get('code');

            if (!$code) {
                return redirect('/manufacturer/login')->with('error', 'Kakao authentication failed.');
            }

            // Exchange code for access token
            $tokenResponse = \Illuminate\Support\Facades\Http::asForm()->post('https://kauth.kakao.com/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $credentials['client_id'],
                'client_secret' => $credentials['client_secret'],
                'redirect_uri' => url('/manufacturer/auth/kakao-login-callback'),
                'code' => $code,
            ]);

            $tokenData = $tokenResponse->json();

            if (!isset($tokenData['access_token'])) {
                return redirect('/manufacturer/login')->with('error', 'Failed to get Kakao access token.');
            }

            // Get user info
            $userResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenData['access_token'],
            ])->get('https://kapi.kakao.com/v2/user/me');

            $userData = $userResponse->json();

            $email = $userData['kakao_account']['email'] ?? null;
            $name = $userData['kakao_account']['profile']['nickname'] ?? 'Kakao User';

            // Validate email
            if (!$email) {
                return redirect('/manufacturer/login')->with('error', 'Email is required. Please enable email permission in your Kakao account settings.');
            }

            $find_user = Manufacturer::where('email', $email)->first();
            
            if ($find_user) {
                $user_creation_with = Manufacturer::where('email', $email)->value('creation_with');
                if ($user_creation_with == 'general') {
                    return redirect('/manufacturer/login')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'google') {
                    return redirect('/manufacturer/login')->with('error', 'You already have an account with this email: ' . $email . ' with Google. Try logging with Google!');
                } else {
                    // Login existing Kakao user
                    $manufacturer = Manufacturer::where('email', $email)->first();
                    Auth::guard('manufacturer')->login($manufacturer);
                    return redirect('/')->with('success', 'Logged In Successful!');
                }
            } else {
                // Create new manufacturer if not exists
                $manufacturer_uid = 'ManuFacturer_' . rand(111111, 999999);
                $manufacturer = Manufacturer::create([
                    'manufacturer_uid' => $manufacturer_uid,
                    'email' => $email,
                    'name' => $name,
                    'status' => 1,
                    'creation_with' => 'kakao',
                ]);

                Auth::guard('manufacturer')->login($manufacturer);
                return redirect('/manufacturer/application')->with('success', 'Complete your application now.');
            }
        } catch (Exception $e) {
            return redirect('/manufacturer/login')->with('error', 'Kakao authentication failed. Please try again.');
        }
    }

    // ============================================
    // WHOLESALER - KAKAO SIGNUP
    // ============================================

    public function wholesalerKakaoSignUp()
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $redirectUrl = url('/wholesaler/auth/kakao/callback');

            // Build Kakao OAuth URL manually
            $kakaoAuthUrl = 'https://kauth.kakao.com/oauth/authorize?' . http_build_query([
                'client_id' => $credentials['client_id'],
                'redirect_uri' => $redirectUrl,
                'response_type' => 'code',
            ]);

            return redirect($kakaoAuthUrl);
        } catch (Exception $e) {
            return redirect('/wholesaler/signup')->with('error', 'Unable to connect to Kakao. Please try again.');
        }
    }

    public function wholesalerKakaoCallback(Request $request)
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $code = $request->get('code');

            if (!$code) {
                return redirect('/wholesaler/signup')->with('error', 'Kakao authentication failed.');
            }

            // Exchange code for access token
            $tokenResponse = \Illuminate\Support\Facades\Http::asForm()->post('https://kauth.kakao.com/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $credentials['client_id'],
                'client_secret' => $credentials['client_secret'],
                'redirect_uri' => url('/wholesaler/auth/kakao/callback'),
                'code' => $code,
            ]);

            $tokenData = $tokenResponse->json();

            if (!isset($tokenData['access_token'])) {
                return redirect('/wholesaler/signup')->with('error', 'Failed to get Kakao access token.');
            }

            // Get user info
            $userResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenData['access_token'],
            ])->get('https://kapi.kakao.com/v2/user/me');

            $userData = $userResponse->json();

            $email = $userData['kakao_account']['email'] ?? null;
            $name = $userData['kakao_account']['profile']['nickname'] ?? 'Kakao User';

            // Validate email
            if (!$email) {
                return redirect('/wholesaler/signup')->with('error', 'Email is required. Please enable email permission in your Kakao account settings.');
            }

            $wholesaler = Wholesaler::where('email', $email)->first();
            $user_creation_with = $wholesaler->creation_with ?? null;
            if ($wholesaler) {
                if ($user_creation_with == 'general') {
                    return redirect('/wholesaler/signup')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'google') {
                    return redirect('/wholesaler/signup')->with('error', 'You already have an account with this email: ' . $email . ' with Google. Try logging with Google!');
                } else {
                    // Check wholesaler status
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
                // Create new wholesaler
                $wholesaler_uid = 'WS_' . rand(11111111, 99999999);
                $wholesaler = Wholesaler::create([
                    'wholesaler_uid' => $wholesaler_uid,
                    'email' => $email,
                    'status' => 1,
                    'creation_with' => 'kakao',
                ]);

                Auth::guard('wholesaler')->login($wholesaler);
                return redirect('/wholesaler/profile-setup')->with('success', 'Logged In Successfully. Setup your profile now!');
            }
        } catch (Exception $e) {
            return redirect('/wholesaler/signup')->with('error', 'Kakao authentication failed. Please try again.');
        }
    }

    // ============================================
    // WHOLESALER - KAKAO LOGIN
    // ============================================

    public function wholesalerKakaoLogin()
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $redirectUrl = url('/wholesaler/auth/kakao-login-callback');

            // Build Kakao OAuth URL manually
            $kakaoAuthUrl = 'https://kauth.kakao.com/oauth/authorize?' . http_build_query([
                'client_id' => $credentials['client_id'],
                'redirect_uri' => $redirectUrl,
                'response_type' => 'code',
            ]);

            return redirect($kakaoAuthUrl);
        } catch (Exception $e) {
            return redirect('/wholesaler/login')->with('error', 'Unable to connect to Kakao. Please try again.');
        }
    }

    public function wholesalerKakaoLoginCallback(Request $request)
    {
        try {
            $credentials = $this->getKakaoCredentials();
            $code = $request->get('code');

            if (!$code) {
                return redirect('/wholesaler/login')->with('error', 'Kakao authentication failed.');
            }

            // Exchange code for access token
            $tokenResponse = \Illuminate\Support\Facades\Http::asForm()->post('https://kauth.kakao.com/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $credentials['client_id'],
                'client_secret' => $credentials['client_secret'],
                'redirect_uri' => url('/wholesaler/auth/kakao-login-callback'),
                'code' => $code,
            ]);

            $tokenData = $tokenResponse->json();

            if (!isset($tokenData['access_token'])) {
                return redirect('/wholesaler/login')->with('error', 'Failed to get Kakao access token.');
            }

            // Get user info
            $userResponse = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $tokenData['access_token'],
            ])->get('https://kapi.kakao.com/v2/user/me');

            $userData = $userResponse->json();

            $email = $userData['kakao_account']['email'] ?? null;
            $name = $userData['kakao_account']['profile']['nickname'] ?? 'Kakao User';

            // Validate email
            if (!$email) {
                return redirect('/wholesaler/login')->with('error', 'Email is required. Please enable email permission in your Kakao account settings.');
            }

            $wholesaler = Wholesaler::where('email', $email)->first();
            $user_creation_with = $wholesaler->creation_with ?? null;
            if ($wholesaler) {
                if ($user_creation_with == 'general') {
                    return redirect('/wholesaler/login')->with('error', 'You already have an account with this email: ' . $email . ' Try logging with your account credentials!');
                } elseif ($user_creation_with == 'google') {
                    return redirect('/wholesaler/login')->with('error', 'You already have an account with this email: ' . $email . ' with Google. Try logging with Google!');
                } else {
                    // Check wholesaler status
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
                // Create new wholesaler if not exists
                $wholesaler_uid = 'WS_' . rand(11111111, 99999999);
                $wholesaler = Wholesaler::create([
                    'wholesaler_uid' => $wholesaler_uid,
                    'email' => $email,
                    'status' => 1,
                    'creation_with' => 'kakao',
                ]);

                Auth::guard('wholesaler')->login($wholesaler);
                return redirect('/wholesaler/profile-setup')->with('success', 'Logged In Successfully. Setup your profile now!');
            }
        } catch (Exception $e) {
            return redirect('/wholesaler/login')->with('error', 'Kakao authentication failed. Please try again.');
        }
    }
}