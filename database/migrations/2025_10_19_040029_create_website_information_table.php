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
        Schema::create('website_information', function (Blueprint $table) {
            $table->id();
            $table->string('brandname');
            $table->string('brandlogo');
            $table->string('website_icon');
            $table->string('currency');
            $table->string('stripe_amount');
            $table->string('stripe_client_id');
            $table->string('stripe_secret_key');
            $table->string('subscription_key');

            $table->string('open_dys');
            $table->string('open_time');
            $table->string('address');
            $table->string('contact_mail');
            $table->string('contact_phone');
            $table->string('fb_url');
            $table->string('instagram_url');
            $table->string('twitter_url');
            $table->string('linkedin_url');
            $table->text('short_desc_about_brand');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_information');
    }
};
