<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('level_education')->nullable();
            $table->string('profession')->nullable();
            $table->string('direction')->nullable();
            $table->string('ubigeo')->nullable();
            $table->string('college_code')->nullable();
            $table->string('tuition_type')->nullable();
            $table->string('rne')->nullable();
            $table->boolean('is_professional')->default(false);
            $table->boolean('is_technical')->default(false);
            $table->boolean('is_validator')->default(false);
            $table->boolean('is_partner')->default(false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
