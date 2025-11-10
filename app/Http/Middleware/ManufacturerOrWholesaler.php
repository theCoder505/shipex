<?php

namespace App\Http\Middleware;

use App\Models\Manufacturer;
use App\Models\Wholesaler;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManufacturerOrWholesaler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('manufacturer')->check() || Auth::guard('wholesaler')->check()) {
            if (Auth::guard('manufacturer')->check()) {
                $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
                Manufacturer::where('manufacturer_uid', $manufacturer_uid)->update(['last_active_time' => now()]);
            }else{
                $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
                Wholesaler::where('wholesaler_uid', $wholesaler_uid)->update(['last_active_time' => now()]);
            }
            return $next($request);
        }else{
            return redirect('/manufacturer/login');
        }
    }
}
