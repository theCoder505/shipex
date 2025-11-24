<?php

namespace App\Providers;

use App\Models\WebsiteInformation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Share website information with all views
        $brandname = WebsiteInformation::where('id', 1)->value('brandname');
        $brandlogo = asset('storage/' . WebsiteInformation::where('id', 1)->value('brandlogo'));
        $website_icon = asset('storage/' . WebsiteInformation::where('id', 1)->value('website_icon'));
        $contact_mail = WebsiteInformation::where('id', 1)->value('contact_mail');
        $business_registration_number = WebsiteInformation::where('id', 1)->value('business_registration_number');
        $business_address = WebsiteInformation::where('id', 1)->value('business_address');
        $open_dys = WebsiteInformation::where('id', 1)->value('open_dys');
        $open_time = WebsiteInformation::where('id', 1)->value('open_time');

        View::share('brandname', $brandname);
        View::share('brandlogo', $brandlogo);
        View::share('website_icon', $website_icon);
        View::share('contact_mail', $contact_mail);
        View::share('business_registration_number', $business_registration_number);
        View::share('business_address', $business_address);
        View::share('open_dys', $open_dys);
        View::share('open_time', $open_time);
    }
}