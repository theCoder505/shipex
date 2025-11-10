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
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer_uid')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('otp')->nullable();
            $table->string('verification_link')->nullable();
            $table->string('status')->default(0);

            $table->string('company_name_en')->nullable();
            $table->string('company_name_ko')->nullable();
            $table->text('company_address_en')->nullable();
            $table->text('company_address_ko')->nullable();
            $table->integer('year_established')->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->string('website')->nullable();
            $table->text('business_introduction')->nullable();
            $table->string('company_logo')->nullable();

            $table->string('contact_name')->nullable();
            $table->string('contact_position')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            $table->string('business_type')->nullable();
            $table->string('industry_category')->nullable();
            $table->string('business_registration_number')->nullable();
            $table->string('business_registration_license')->nullable();
            $table->enum('export_experience', ['yes', 'no'])->nullable();
            $table->integer('export_years')->nullable();

            $table->string('main_product_category')->nullable();
            $table->text('products')->nullable();
            $table->integer('production_capacity')->nullable();
            $table->string('production_capacity_unit')->nullable();
            $table->integer('moq')->nullable();
            $table->text('certifications')->nullable();
            $table->enum('has_patents', ['yes', 'no'])->nullable();
            $table->text('patents')->nullable();


            $table->enum('has_qms', ['yes', 'no'])->nullable();
            $table->enum('factory_audit_available', ['yes', 'no'])->nullable();
            $table->text('standards')->nullable();
            $table->text('factory_pictures')->nullable();
            $table->string('catalogue')->nullable();


            $table->boolean('agree_terms')->default(false);
            $table->boolean('consent_background_check')->default(false);
            $table->string('digital_signature');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturers');
    }
};
