<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();

            // Page info
            $table->string('page_name')->nullable();
            $table->string('page_url');

            // Visitor identity
            $table->string('visitor_ip_address');
            $table->string('session_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Device info
            $table->string('visitor_device')->nullable();   // mobile, tablet, desktop
            $table->string('visitor_os')->nullable();       // Windows, Android, iOS
            $table->string('visitor_browser')->nullable();  // Chrome, Firefox, etc.

            // Geo info
            $table->string('visitor_country')->nullable();
            $table->string('visitor_city')->nullable();

            // Traffic source
            $table->string('referrer_url')->nullable();

            // Flags
            $table->boolean('is_bot')->default(false);

            $table->timestamps();

            // Indexes for fast querying
            $table->index('visitor_ip_address');
            $table->index('visitor_country');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};