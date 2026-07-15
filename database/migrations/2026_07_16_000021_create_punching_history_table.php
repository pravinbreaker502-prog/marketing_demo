<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('punching_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('in_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('punch_type')->nullable()->default('0');
            $table->string('faceimage_path')->nullable();
            $table->string('odoimage_path')->nullable();
            $table->string('odometer_km', 11)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('punching_history');
    }
};
