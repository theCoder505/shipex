<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_code',
        'monthly_fee_amount',
        'half_yearly_fee_amount',
        'yearly_fee_amount',
        'type',
        'discount_amount',
        'discount_percentage',
    ];
}
