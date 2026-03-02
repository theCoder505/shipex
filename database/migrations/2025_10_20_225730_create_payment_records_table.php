<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer_uid');

            // ── PayPal ───────────────────────────────────────────────────────
            $table->string('paypal_payment_id')->nullable();
            $table->string('paypal_payer_id')->nullable();
            $table->string('paypal_order_id')->nullable();
            $table->string('paypal_transaction_id')->nullable();
            $table->text('paypal_response')->nullable();

            // ── TOSS ─────────────────────────────────────────────────────────
            $table->string('toss_payment_key')->nullable();
            $table->string('toss_order_id')->nullable();
            $table->string('toss_transaction_id')->nullable();
            $table->text('toss_response')->nullable();

            // ── Common ───────────────────────────────────────────────────────
            $table->string('package_type');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('usd');
            $table->string('payment_status');
            $table->string('coupon_code')->nullable();
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('payment_method')->default('paypal'); // paypal | toss | coupon
            $table->timestamp('payment_date')->useCurrent();
            $table->timestamp('subscription_end_date')->nullable();
            $table->timestamps();

            // ── Indexes ──────────────────────────────────────────────────────
            $table->index('manufacturer_uid');
            $table->index('payment_status');
            $table->index('toss_payment_key');
            $table->index('toss_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_records');
    }
};