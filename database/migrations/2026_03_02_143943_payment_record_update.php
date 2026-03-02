<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->string('stripe_payment_id')->nullable()->after('toss_response');
            $table->string('stripe_session_id')->nullable()->after('stripe_payment_id');
            $table->json('stripe_response')->nullable()->after('stripe_session_id');
        });
    }



    public function down(): void
    {
        Schema::table('payment_records', function (Blueprint $table) {
            $table->dropColumn(['stripe_payment_id', 'stripe_session_id', 'stripe_response']);
        });
    }
};
