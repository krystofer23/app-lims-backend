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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('ruc')->nullable();
            $table->string('company_name')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('economic_activity')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('ubigeo')->nullable();
            $table->boolean('is_supplier')->default(false);
            $table->boolean('is_partner')->default(false);
            $table->string('category')->nullable();
            $table->date('billing_start_date')->nullable();
            $table->integer('billing_cut_off_period')->nullable();
            $table->integer('purchase_order_submission_limit')->nullable();
            $table->integer('credit_days')->nullable();
            $table->text('observations')->nullable(); 
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
        Schema::dropIfExists('companies');
    }
};
