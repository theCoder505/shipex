<?php

namespace App\Http\Middleware;

use App\Models\Manufacturer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JustManufacturer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        if (!Auth::guard('manufacturer')->check()) {
            return redirect('/manufacturer/login')->with('error', 'Login First To Continue As A Manufacturer!');
        }

        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        if (!$manufacturer) {
            Auth::guard('manufacturer')->logout();
            return redirect('/manufacturer/login')->with('error', 'Manufacturer account not found!');
        }

        return $next($request);
    }
}
