<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'brandname',
        'brandlogo',
        'website_icon',
        'currency',
        'PAYPAL_CLIENT_ID',
        'PAYPAL_SECRET',
        'PAYPAL_MODE',

        'google_client_id',
        'google_client_secret',
        'kakao_client_id',
        'kakao_client_secret',

        'monthly_fee_amount',
        'half_yearly_fee_amount',
        'yearly_fee_amount',
        'exchange_rate',

        'open_dys',
        'open_time',
        'contact_mail',
        'contact_phone',
        'fb_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'business_registration_number',
        'business_address',
        'short_desc_about_brand',

        'terms_conditions',
        'privacy_policy',
    ];

    protected $casts = [
        'stripe_amount' => 'decimal:2',
    ];
}
