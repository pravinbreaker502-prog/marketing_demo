<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_name')->nullable();
            $table->string('page_slug')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('employee_email')->nullable();
            $table->bigInteger('employee_mobile')->nullable();
            $table->string('employee_address')->nullable();
            $table->string('employee_profile')->nullable();
            $table->string('employee_dob')->nullable();
            $table->string('employee_blood', 11)->nullable();
            $table->string('employee_adhaar_doc')->nullable();
            $table->string('employee_qualification_doc')->nullable();
            $table->string('employee_experience')->nullable();
            $table->string('employee_resume')->nullable();
            $table->string('employee_passbook_doc')->nullable();
            $table->string('employee_pan_doc')->nullable();
            $table->string('employee_type')->nullable();
            $table->string('vehichle_type')->nullable();
            $table->string('vehichle_license')->nullable();
            $table->string('vehichle_insurance')->nullable();
            $table->string('vehichle_name')->nullable();
            $table->string('vehichle_regno')->nullable();
            $table->string('employee_zone_country')->nullable();
            $table->string('employee_zone_state')->nullable();
            $table->string('employee_zone_city')->nullable();
            $table->string('employee_zone_pincode')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('app_token', 50)->nullable();
            $table->longText('fcm_token')->nullable();
            $table->string('device_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
