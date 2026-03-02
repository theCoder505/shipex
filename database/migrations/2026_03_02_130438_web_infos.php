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
        Schema::table('website_information', function (Blueprint $table) {
            $table->string('stripe_client_id')->nullable()->after('TOSS_SECRET_KEY');
            $table->string('stripe_secret_key')->nullable()->after('stripe_client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('website_information', function (Blueprint $table) {
            $table->dropColumn(['stripe_client_id', 'stripe_secret_key']);
        });
    }
};
