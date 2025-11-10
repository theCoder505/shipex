<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Wholesaler extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'wholesaler_uid',
        'email',
        'password',
        'otp',
        'status',
        'company_name',
        'business_type',
        'industry_focus',
        'country',
        'category',
        'verification_token',
        'language',
        'admin_comment',
        'profile_picture',
        'last_active_time'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'category' => 'array',
    ];
}
