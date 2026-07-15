<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->nullable();
            // 0-fullday, 1-halfday, 2-permission, 3-holding interval
            $table->enum('leave_type', ['0', '1', '2', '3'])->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->string('leave_reason')->nullable();
            $table->string('reject_reason')->nullable();
            // 0-pending, 1-accepted, 2-rejected, 3-cancelled
            $table->enum('status', ['0', '1', '2', '3'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
