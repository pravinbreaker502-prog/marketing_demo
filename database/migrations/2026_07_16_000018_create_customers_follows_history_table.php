<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers_follows_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->json('sample_ids')->nullable();
            $table->json('order_ids')->nullable();
            $table->string('in_time', 10)->nullable();
            $table->string('out_time', 10)->nullable();
            // 0-pending, 1-interested, 2-not interested
            $table->enum('accept_status', ['0', '1', '2'])->nullable();
            $table->timestamp('followup_date')->nullable();
            // 0-pending, 1-yes, 2-no
            $table->enum('training', ['0', '1', '2'])->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers_follows_history');
    }
};
