<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCredentials extends Controller
{





    public function adminLoginPage()
    {
        $data = [];
        return view('surface.admin_login');
    }





    public function verifyAndLogin(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];
        $userAgent = $request->header('User-Agent');
        $browser = $this->getBrowser($userAgent);
        $device = $this->getDevice($userAgent);
        $ip = $request->ip();
        $location = $this->getLocation($ip);

        $admin = Admin::where('id', 1)->first();
        if ($email == $admin->email) {
            if (password_verify($password, $admin->password)) {
                $admin->last_activity = now();
                $admin->last_login_ip = $ip;
                $admin->last_login_device = $device;
                $admin->last_login_browser = $browser;
                $admin->last_login_location = $location;
                $admin->save();
                
                Auth::guard('admin')->login($admin);
                return redirect('/admin/dashboard');
            } else {
                return redirect()->back()->with('error', 'Passwords did not match!')->with('email', $email)->with('password', $password);
            }
        } else {
            return redirect()->back()->with('error', 'Admin email did not match!')->with('email', $email)->with('password', $password);
        }
    }





    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login')->with('success', 'Logged out successfully!');
    }










    // Helper methods to extract browser and device info
    protected function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        } elseif (strpos($userAgent, 'Edg') !== false) {
            return 'Microsoft Edge';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Apple Safari';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            return 'Opera';
        } else {
            return 'Unknown Browser';
        }
    }






    protected function getDevice($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            return 'iPhone';
        } elseif (strpos($userAgent, 'iPad') !== false) {
            return 'iPad';
        } elseif (strpos($userAgent, 'Windows') !== false) {
            return 'Windows PC';
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            return 'Macintosh';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'Linux PC';
        } else {
            return 'Unknown Device';
        }
    }



    // Helper method to get location from IP (using free API)
    protected function getLocation($ip)
    {
        try {
            // For localhost/testing
            if ($ip === '127.0.0.1') {
                return 'Localhost';
            }

            // Use ipinfo.io API (free tier available)
            $response = file_get_contents("http://ipinfo.io/{$ip}/json");
            $data = json_decode($response);

            $location = [];
            if (isset($data->city)) $location[] = $data->city;
            if (isset($data->region)) $location[] = $data->region;
            if (isset($data->country)) $location[] = $data->country;

            return implode(', ', $location) ?: 'Unknown Location';
        } catch (\Exception $e) {
            return 'Unknown Location';
        }
    }

    //
}
