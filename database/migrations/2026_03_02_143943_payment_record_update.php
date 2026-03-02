<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->string('toss_payment_key')->nullable()->after('paypal_response');
            $table->string('toss_order_id')->nullable()->after('toss_payment_key');
            $table->string('toss_transaction_id')->nullable()->after('toss_order_id');
            $table->string('toss_response')->nullable()->after('toss_transaction_id');
            $table->string('stripe_payment_id')->nullable()->after('toss_response');
            $table->string('stripe_session_id')->nullable()->after('stripe_payment_id');
            $table->json('stripe_response')->nullable()->after('stripe_session_id');
        });
    }



    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropColumn(['toss_payment_key', 'toss_order_id', 'toss_transaction_id', 'toss_response', 'stripe_payment_id', 'stripe_session_id', 'stripe_response']);
        });
    }
};
