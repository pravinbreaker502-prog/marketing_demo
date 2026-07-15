<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products_categories', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing unsigned integer primary key
            $table->string('category_name', 255)->nullable(); // Category name with maximum length of 255 characters
            $table->timestamp('created_at')->useCurrent(); // Current timestamp as default for created_at
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // Current timestamp as default and on update for updated_at
            $table->timestamp('deleted_at')->nullable(); // Soft delete column to store deletion timestamp
        });
    
        DB::table('products_categories')->insert([
            [
                'category_name' => 'None',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Common',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Platinum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_name' => 'Gold',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_categories');
    }
};
