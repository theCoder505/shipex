<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_records', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer_uid');
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_invoice_id')->nullable();
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('package_type');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('usd');
            $table->string('payment_status');
            $table->string('coupon_code')->nullable();
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('payment_method')->default('stripe');
            $table->text('stripe_response')->nullable();
            $table->timestamp('payment_date')->useCurrent();
            $table->timestamp('next_billing_date')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('manufacturer_uid');
            $table->index('stripe_customer_id');
            $table->index('stripe_subscription_id');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_records');
    }
};
