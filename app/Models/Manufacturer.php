<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Manufacturer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'manufacturer_uid',
        'name',
        'email',
        'password',
        'otp',
        'verification_link',
        'status',

        // Company Information
        'company_name_en',
        'company_name_ko',
        'company_address_en',
        'company_address_ko',
        'company_google_location',
        'year_established',
        'number_of_employees',
        'website',
        'business_introduction',
        'company_logo',

        // Contact Person
        'contact_name',
        'contact_position',
        'contact_email',
        'contact_phone',

        // Business Profile
        'business_type',
        'industry_category',
        'business_registration_number',
        'business_registration_license',
        'export_experience',
        'export_years',

        // Product Information
        'main_product_category',
        'products',
        'production_capacity',
        'production_capacity_unit',
        'moq',
        'certifications',
        'has_patents',
        'patents',

        // Trust & Verifications
        'has_qms',
        'factory_audit_available',
        'standards',
        'factory_pictures',
        'catalogue',

        // Declaration
        'agree_terms',
        'consent_background_check',
        'digital_signature',


        'language',

        // Subscription
        'subscription', // subscription status
        'subscription_type',
        'coupon_code',

        'paypal_payer_id',
        'paypal_payment_id',
        'subscription_start_date',
        'subscription_end_date',

        'total_ratings',
        'rating',
        'admin_comment',
        'last_active_time'
    ];

    protected $hidden = [
        'password',
        'otp',
        'verification_link',
    ];

    protected $casts = [
        'password' => 'hashed',
        'products' => 'array',
        'certifications' => 'array',
        'patents' => 'array',
        'standards' => 'array',
        'factory_pictures' => 'array',
        'agree_terms' => 'boolean',
        'consent_background_check' => 'boolean',
        'year_established' => 'integer',
        'number_of_employees' => 'integer',
        'export_years' => 'integer',
        'production_capacity' => 'integer',
        'moq' => 'integer',
    ];
}
