<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_uid',

        // PayPal
        'paypal_payment_id',
        'paypal_payer_id',
        'paypal_order_id',
        'paypal_transaction_id',
        'paypal_response',

        // TOSS
        'toss_payment_key',
        'toss_order_id',
        'toss_transaction_id',
        'toss_response',

        // Stripe
        'stripe_payment_id',
        'stripe_session_id',
        'stripe_response',

        // Common
        'package_type',
        'amount',
        'currency',
        'payment_status',
        'coupon_code',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'payment_method',
        'payment_date',
        'subscription_end_date',
    ];

    protected $casts = [
        'amount'                => 'decimal:2',
        'paypal_response'       => 'array',
        'toss_response'         => 'array',
        'stripe_response'       => 'array',
        'payment_date'          => 'datetime',
        'subscription_end_date' => 'datetime',
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_uid', 'manufacturer_uid');
    }

    public function scopeActive($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function getFormattedAmountAttribute(): string
    {
        $symbol = match (strtolower($this->currency)) {
            'usd'   => '$',
            'krw'   => '₩',
            'eur'   => '€',
            default => strtoupper($this->currency) . ' ',
        };

        $formatted = $this->currency === 'krw'
            ? number_format($this->amount, 0)
            : number_format($this->amount, 2);

        return $symbol . $formatted;
    }

    public function getIsSuccessfulAttribute(): bool
    {
        return in_array($this->payment_status, ['completed', 'active', 'trialing']);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'paypal' => 'PayPal',
            'toss'   => 'TOSS',
            'stripe' => 'Stripe',
            'coupon' => 'Coupon',
            default  => ucfirst($this->payment_method),
        };
    }
}