<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_uid',

        // PayPal fields (REPLACE stripe fields)
        'paypal_payment_id',
        'paypal_payer_id',
        'paypal_order_id',
        'paypal_transaction_id',

        'package_type',
        'amount',
        'currency',
        'payment_status',
        'coupon_code',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'payment_method', // Will be 'paypal'
        'paypal_response',
        'payment_date',
        'subscription_end_date', // CHANGED from next_billing_date
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paypal_response' => 'array', // CHANGED from stripe_response
        'payment_date' => 'datetime',
        'subscription_end_date' => 'datetime', // CHANGED
    ];

    /**
     * Relationship with manufacturer
     */
    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_uid', 'manufacturer_uid');
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'active');
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }

    /**
     * Check if payment is successful
     */
    public function getIsSuccessfulAttribute()
    {
        return in_array($this->payment_status, ['active', 'trialing']);
    }
}
