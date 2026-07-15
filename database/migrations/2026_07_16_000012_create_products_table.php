<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('standard')->nullable();
            $table->json('category_id')->nullable();
            $table->string('product')->nullable();
            $table->string('page_slug')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('product_purprice', 10, 2)->nullable();
            $table->decimal('actual_price', 10, 2)->nullable();
            $table->integer('discount')->nullable();
            $table->decimal('discount_amt', 10, 2)->nullable();
            $table->decimal('sell_price', 10, 2)->nullable();
            $table->integer('gst')->nullable();
            $table->decimal('gst_amt', 10, 2)->nullable();
            $table->integer('sort_order')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
