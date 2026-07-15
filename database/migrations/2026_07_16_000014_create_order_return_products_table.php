<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_return_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->integer('gst_per')->nullable();
            $table->decimal('gst_amount', 10, 2)->nullable();
            $table->decimal('actual_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('compensated_amount', 10, 2)->nullable();
            $table->decimal('returned_amount', 10, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_return_products');
    }
};
