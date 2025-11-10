<?php

namespace App\Http\Middleware;

use App\Models\Wholesaler;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WholesalerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('wholesaler')->check()) {
            return redirect('/wholesaler/login')->with('error', 'Login First To Continue As A Wholesaler!');
        }

        $wholesaler_uid = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $check_status = Wholesaler::where('wholesaler_uid', $wholesaler_uid)->value('status');
        if ($check_status == 0) {
            return redirect('/wholesaler/signup')->with('error', 'Your account is not verified yet. Verify First!');
        }elseif ($check_status == 3) {
            // Auth::guard('wholesaler')->logout();
            return redirect('/wholesaler/login')->with('error', 'Your account has been restricted. Please contact support for more information.');
        }else{
            Wholesaler::where('wholesaler_uid', $wholesaler_uid)->update(['last_active_time' => now()]);
        }
        return $next($request);
    }
}
