<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('product_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('discount_amt', 10, 2)->nullable();
            $table->integer('gst_per')->nullable();
            $table->decimal('gst_amt', 10, 2)->nullable();
            $table->decimal('total_amt', 10, 2)->nullable();
            $table->decimal('actual_amt', 10, 2)->nullable();
            $table->timestamp('order_date')->nullable()->useCurrent();
            $table->timestamp('delivery_date')->nullable();
            $table->string('status')->nullable();
            $table->integer('followup_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
