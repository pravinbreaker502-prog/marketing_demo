<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assign_trainers_toschool', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable();
            $table->integer('trainer_id')->nullable();
            $table->integer('no_of_teachers')->nullable();
            $table->timestamp('assigned_from')->nullable();
            $table->timestamp('assigned_end')->nullable();
            $table->timestamp('started_from')->nullable();
            $table->timestamp('training_end')->nullable();
            $table->string('process_status')->nullable();
            $table->longText('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assign_trainers_toschool');
    }
};
