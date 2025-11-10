<?php

namespace App\Http\Controllers\menufacturer;

use App\Http\Controllers\Controller;
use App\Models\CouponCode;
use App\Models\Manufacturer;
use App\Models\WebsiteInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManufacturerPagesController extends Controller
{






    public function manufacturerProfilePage($page_type)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $profile_data = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        return view('manufacturer.profile', compact('profile_data', 'page_type'));
    }





















    //
}
