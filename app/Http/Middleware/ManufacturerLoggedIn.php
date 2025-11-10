<?php

namespace App\Http\Middleware;

use App\Models\Manufacturer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class ManufacturerLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('manufacturer')->check()) {
            return redirect('/manufacturer/login')->with('error', 'Login First To Continue As A Manufacturer!');
        }

        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        
        if (!$manufacturer) {
            Auth::guard('manufacturer')->logout();
            return redirect('/manufacturer/login')->with('error', 'Manufacturer account not found!');
        }

        $check_status = $manufacturer->status;
        if ($check_status == 0) {
            return redirect('/manufacturer/signup')->with('error', 'Your account is not verified yet. Verify First!');
        }

        if ($manufacturer->subscription == 1 && $manufacturer->subscription_end_date) {
            $subscriptionEndDate = Carbon::parse($manufacturer->subscription_end_date);
            
            if (Carbon::now()->greaterThan($subscriptionEndDate)) {
                return redirect('/manufacturer/packages')->with('error', 'Your subscription has expired. Please subscribe again to continue using our services.');
            }
        }

        $manufacturer->update(['last_active_time' => now()]);
        return $next($request);
    }
}