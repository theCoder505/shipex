<?php

namespace App\Http\Middleware;

use App\Models\Manufacturer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidManufacturerMiddleware
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
        $check_status = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->value('status');
        if ($check_status == 1 || $check_status == 0) {
            return redirect('/manufacturer/application')->with('warning', 'You are not yet aproved! To Subscribe, complete your application and get approved first.');
        }

        if ($check_status == 3) {
            return redirect('/manufacturer/application')->with('error', 'Please resubmit the application form with correct details, as your previous submission was rejected. For further inquiry, contact us.');
        }

        Manufacturer::where('manufacturer_uid', $manufacturer_uid)->update(['last_active_time' => now()]);
        return $next($request);
    }
}
