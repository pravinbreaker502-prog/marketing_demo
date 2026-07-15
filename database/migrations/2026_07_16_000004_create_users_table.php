<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id'); // Auto-incrementing unsigned integer primary key 1
            $table->integer('user_id'); // User ID
            $table->string('username', 255); // Username with maximum length of 255 characters
            $table->string('password', 255); // Password with maximum length of 255 characters
            $table->string('user_type', 255); // User type with maximum length of 255 characters
            $table->longText('fcm_token')->nullable(); // FCM token (Firebase Cloud Messaging token)
            $table->timestamp('created_at')->useCurrent(); // Current timestamp as default for created_at
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // Current timestamp as default and on update for updated_at
            $table->timestamp('deleted_at')->nullable(); // Soft delete column to store deletion timestamp
        });
        
        DB::table('users')->insert([
            'user_id' => 0,
            'username' => 'admin',
            'password' => md5('admin'), // You should hash passwords for security
            'user_type' => 'marketing_admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
