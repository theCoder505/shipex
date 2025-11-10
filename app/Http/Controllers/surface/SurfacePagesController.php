<?php

namespace App\Http\Controllers\surface;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Manufacturer;
use App\Models\Reviews;
use App\Models\WebsiteInformation;
use App\Models\Wholesaler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Review;
use Carbon\Carbon;

class SurfacePagesController extends Controller
{



    public function testEmail()
    {
        $email = 'programmer.emad7867@gmail.com';
        $otp = rand(111111, 999999);

        $data = [
            'email' => $email,
            'otp' => $otp,
        ];

        Mail::send('mail.wholesaler_signup_otp', $data, function ($message) use ($email) {
            $message->to($email)->subject("Your OTP Code for Sign-Up");
        });

        return 'Sent!';
    }



    public function indexPage()
    {
        $show_type = 'all';

        if (Auth::guard('wholesaler')->check()) {
            $wholesaler_id = Auth::guard('wholesaler')->user()->wholesaler_uid;
            $wholesaler = Wholesaler::where('wholesaler_uid', $wholesaler_id)->first();
            $categories = $wholesaler->category ?? [];
            $check_status = Wholesaler::where('wholesaler_uid', $wholesaler_id)->value('status');

            if ($check_status == 3) {
                Auth::guard('wholesaler')->logout();
                return redirect('/wholesaler/login')->with('error', 'Your account has been restricted. Please contact support for more information.');
            }

            // If wholesaler has categories, prioritize related manufacturers but show all
            if (!empty($categories) && is_array($categories)) {
                // Get manufacturers that match the categories (preferred ones) with active subscription
                $preferredManufacturers = Manufacturer::where(function ($query) use ($categories) {
                    foreach ($categories as $category) {
                        $query->orWhere('main_product_category', 'LIKE', "%{$category}%")
                            ->orWhere('industry_category', 'LIKE', "%{$category}%");
                    }
                })
                    ->where('status', 5)
                    ->where('subscription', 1)
                    ->where('subscription_end_date', '>', Carbon::now())
                    ->orderBy('rating', 'DESC')
                    ->get();

                // Get all other manufacturers that don't match the categories with active subscription
                $otherManufacturers = Manufacturer::whereNotIn('id', $preferredManufacturers->pluck('id'))
                    ->where('status', 5)
                    ->where('subscription', 1)
                    ->where('subscription_end_date', '>', Carbon::now())
                    ->orderBy('rating', 'DESC')
                    ->get();

                // Combine: preferred manufacturers first, then all others
                $manufacturers = $preferredManufacturers->merge($otherManufacturers);
                $tot_menufacturers = Manufacturer::where('status', 5)
                    ->where('subscription', 1)
                    ->where('subscription_end_date', '>', Carbon::now())
                    ->count();
            } else {
                // If no categories, show all manufacturers with active subscription
                $manufacturers = Manufacturer::where('status', 5)
                    ->where('subscription', 1)
                    ->where('subscription_end_date', '>', Carbon::now())
                    ->orderBy('rating', 'DESC')
                    ->get();
                $tot_menufacturers = $manufacturers->count();
            }
        } else {
            // For non-logged in users, show all manufacturers with active subscription
            $manufacturers = Manufacturer::where('status', 5)
                ->where('subscription', 1)
                ->where('subscription_end_date', '>', Carbon::now())
                ->orderBy('rating', 'DESC')
                ->get();
            $tot_menufacturers = $manufacturers->count();
        }

        return view('surface.index', compact('manufacturers', 'tot_menufacturers', 'show_type'));
    }







    public function filterManufacturers(Request $request)
    {
        try {
            // Start with base query for active subscription manufacturers
            $query = Manufacturer::where('status', 5)
                ->where('subscription', 1)
                ->where('subscription_end_date', '>', Carbon::now());

            // Only apply filters if they are explicitly set and not empty/default values
            $hasActiveFilters = false;

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('company_name_en', 'LIKE', "%{$search}%")
                        ->orWhere('company_name_ko', 'LIKE', "%{$search}%")
                        ->orWhere('business_introduction', 'LIKE', "%{$search}%")
                        ->orWhere('main_product_category', 'LIKE', "%{$search}%")
                        ->orWhere('industry_category', 'LIKE', "%{$search}%")
                        ->orWhere('contact_name', 'LIKE', "%{$search}%");
                });
                $hasActiveFilters = true;
            }

            // Product type tabs - only apply if not 'all'
            if ($request->filled('product_type') && $request->product_type !== 'all') {
                switch ($request->product_type) {
                    case 'new':
                        $query->where('year_established', '>=', date('Y') - 2);
                        $hasActiveFilters = true;
                        break;
                    case 'refurbished':
                        $query->where(function ($q) {
                            $q->where('business_type', 'like', '%Refurbished%')
                                ->orWhere('industry_category', 'like', '%Refurbished%')
                                ->orWhere('main_product_category', 'like', '%Refurbished%');
                        });
                        $hasActiveFilters = true;
                        break;
                }
            }

            // Filter by product categories - only if categories are selected
            if ($request->has('categories') && is_array($request->categories) && !empty($request->categories)) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->categories as $category) {
                        $q->orWhere('industry_category', 'LIKE', "%{$category}%")
                            ->orWhere('main_product_category', 'LIKE', "%{$category}%");
                    }
                });
                $hasActiveFilters = true;
            }

            // Filter by business types - only if business types are selected
            if ($request->has('business_types') && is_array($request->business_types) && !empty($request->business_types)) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->business_types as $businessType) {
                        $q->orWhere('business_type', 'LIKE', "%{$businessType}%");
                    }
                });
                $hasActiveFilters = true;
            }

            // Filter by certifications/standards - only if certifications are selected
            if ($request->has('certifications') && is_array($request->certifications) && !empty($request->certifications)) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->certifications as $cert) {
                        $q->orWhereJsonContains('standards', $cert);
                    }
                });
                $hasActiveFilters = true;
            }

            // Filter by location (country) - only if country is selected
            if ($request->filled('country') && $request->country !== '') {
                $query->where(function ($q) use ($request) {
                    $q->where('company_address_en', 'LIKE', "%{$request->country}%")
                        ->orWhere('company_address_ko', 'LIKE', "%{$request->country}%");
                });
                $hasActiveFilters = true;
            }

            // Filter by MOQ - only if MOQ is greater than 0
            if ($request->filled('moq') && is_numeric($request->moq) && $request->moq > 0) {
                $query->where('moq', '<=', (int)$request->moq);
                $hasActiveFilters = true;
            }

            // Filter verified only - only if explicitly checked
            if ($request->boolean('verified_only')) {
                $query->where('status', 5)
                    ->where('subscription', 1)
                    ->where('subscription_end_date', '>', Carbon::now());
                $hasActiveFilters = true;
            }

            // If no active filters, return all manufacturers with active subscription
            if (!$hasActiveFilters) {
                $manufacturers = Manufacturer::where('status', 5)
                    ->where('subscription', 1)
                    ->where('subscription_end_date', '>', Carbon::now())
                    ->orderBy('rating', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            } else {
                // Apply the query with active subscription filter
                $manufacturers = $query->orderBy('rating', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->get();
            }

            // Transform the data for frontend
            $transformedManufacturers = $manufacturers->map(function ($manufacturer) {
                return [
                    'id' => $manufacturer->id,
                    'manufacturer_uid' => $manufacturer->manufacturer_uid,
                    'company_name_en' => $manufacturer->company_name_en,
                    'company_name_ko' => $manufacturer->company_name_ko,
                    'company_address_en' => $manufacturer->company_address_en,
                    'company_address_ko' => $manufacturer->company_address_ko,
                    'year_established' => $manufacturer->year_established,
                    'business_introduction' => $manufacturer->business_introduction,
                    'company_logo' => $manufacturer->company_logo,
                    'industry_category' => $manufacturer->industry_category,
                    'main_product_category' => $manufacturer->main_product_category,
                    'business_type' => $manufacturer->business_type,
                    'status' => $manufacturer->status,
                    'subscription' => $manufacturer->subscription,
                    'subscription_end_date' => $manufacturer->subscription_end_date,
                    'rating' => $manufacturer->rating,
                    'total_ratings' => $manufacturer->total_ratings,
                    'moq' => $manufacturer->moq,
                    'standards' => $manufacturer->standards,
                    'factory_pictures' => $manufacturer->factory_pictures,
                    'products' => $manufacturer->products,
                    'created_at' => $manufacturer->created_at,
                    'updated_at' => $manufacturer->updated_at,
                ];
            });

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'manufacturers' => $transformedManufacturers,
                    'count' => $manufacturers->count(),
                    'has_active_filters' => $hasActiveFilters,
                    'filters_applied' => $this->getAppliedFilters($request)
                ]);
            }

            return view('surface.index', [
                'manufacturers' => $manufacturers,
                'tot_menufacturers' => $manufacturers->count(),
                'show_type' => $request->product_type ?? 'all'
            ]);
        } catch (\Exception $e) {
            Log::error('Filter manufacturers error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while filtering manufacturers.',
                    'manufacturers' => [],
                    'count' => 0
                ], 500);
            }

            // Fallback to all manufacturers with active subscription on error
            $manufacturers = Manufacturer::where('status', 5)
                ->where('subscription', 1)
                ->where('subscription_end_date', '>', Carbon::now())
                ->orderBy('rating', 'DESC')
                ->get();

            return view('surface.index', [
                'manufacturers' => $manufacturers,
                'tot_menufacturers' => $manufacturers->count(),
                'show_type' => 'all'
            ]);
        }
    }






    public function manufacturersPage()
    {
        $manufacturers = Manufacturer::where('status', 5)
            ->where('subscription', 1)
            ->where('subscription_end_date', '>', Carbon::now())
            ->orderBy('rating', 'DESC')
            ->get();

        $tot_menufacturers = $manufacturers->count();
        $show_type = 'all';

        return view('surface.manufacturers', compact('manufacturers', 'tot_menufacturers', 'show_type'));
    }














    
    public function helpPage()
    {
        $faqs = FAQ::orderBy('id', 'DESC')->get();
        return view('surface.help', compact('faqs'));
    }


    public function PrivacyPolicyPage()
    {
        $page_content = WebsiteInformation::where('id', 1)->value('privacy_policy');
        $updated_at = WebsiteInformation::where('id', 1)->value('updated_at');
        return view('surface.privacy_policy', compact('page_content', 'updated_at'));
    }


    public function TermsOfUsePage()
    {
        $page_content = WebsiteInformation::where('id', 1)->value('terms_conditions');
        $updated_at = WebsiteInformation::where('id', 1)->value('updated_at');
        return view('surface.terms_of_use', compact('page_content', 'updated_at'));
    }

    public function ContactUs()
    {
        $page_content = [];
        return view('surface.terms_of_use', compact('page_content'));
    }



    public function accountSelection()
    {
        return view('surface.account.selection');
    }



    public function searchMenufacturer(Request $request)
    {
        $query = $request['search_query'];
        $results = [1, 2, 3];
        return $results;
    }




    public function specManufacturer($manufacturer_name, $manufacturer_uid)
    {
        $spec_manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $manufacturers = Manufacturer::orderBy('rating', 'DESC')->where('manufacturer_uid', '!=', $manufacturer_uid)->where('status', 5)->where('subscription', 1)->limit(10)->get();
        $tot_menufacturers = Manufacturer::orderBy('rating', 'DESC')->count();
        $show_type = 'limited';
        $wholesalers = Wholesaler::all();

        $products = $spec_manufacturer->products ?? [];
        $certificates = $spec_manufacturer->certifications ?? [];
        $reviews = Reviews::where('manufacturer_uid', $manufacturer_uid)->where('status', 1)->get();

        if (Auth::guard('wholesaler')->check()) {
            $wholesaler_id = Auth::guard('wholesaler')->user()->wholesaler_uid;
            $check_status = Wholesaler::where('wholesaler_uid', $wholesaler_id)->value('status');
            if ($check_status == 3) {
                Auth::guard('wholesaler')->logout();
                return redirect('/wholesaler/login')->with('error', 'Your account has been restricted. Please contact support for more information.');
            }
        }

        return view('surface.specific_menufacturer', compact(
            'manufacturer_name',
            'manufacturer_uid',
            'spec_manufacturer',
            'manufacturers',
            'tot_menufacturers',
            'products',
            'certificates',
            'reviews',
            'show_type',
            'wholesalers'
        ));
    }



    public function reviewManufacturer(Request $request)
    {
        $wholesaler_id = Auth::guard('wholesaler')->user()->wholesaler_uid;
        $manufacturer_uid = $request['manufacturer_uid'];
        $rating = $request['rating'];
        $review_comment = $request['review_text'];

        $check_existing_review = Reviews::where('wholesaler_uid', $wholesaler_id)
            ->where('manufacturer_uid', $manufacturer_uid)
            ->first();

        if ($check_existing_review) {
            $check_existing_review->rating = $rating;
            $check_existing_review->review_text = $review_comment;
            $check_existing_review->save();
        } else {
            Reviews::create([
                'wholesaler_uid' => $wholesaler_id,
                'manufacturer_uid' => $manufacturer_uid,
                'rating' => $rating,
                'review_text' => $review_comment,
            ]);
        }

        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        $total_reviews = Reviews::where('manufacturer_uid', $manufacturer_uid)->count();
        $final_rating = 0;
        if ($total_reviews > 0) {
            $total_rating = Reviews::where('manufacturer_uid', $manufacturer_uid)->sum('rating');
            $final_rating = round($total_rating / $total_reviews, 1);
        }

        $manufacturer->total_ratings = $total_reviews;
        $manufacturer->rating = $final_rating;
        $manufacturer->save();

        return redirect()->back()->with('success', 'You Have Reviewed This Manufacturer Successfully!');
    }



















    //
}
