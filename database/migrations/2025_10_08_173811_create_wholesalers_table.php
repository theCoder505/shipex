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
        Schema::create('wholesalers', function (Blueprint $table) {
            $table->id();
            $table->string('wholesaler_uid');
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('otp');
            $table->string('status')->default(0);
            $table->string('company_name')->nullable();
            $table->string('business_type')->nullable();
            $table->string('industry_focus')->nullable();
            $table->string('country')->nullable();
            $table->text('category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesalers');
    }
};
