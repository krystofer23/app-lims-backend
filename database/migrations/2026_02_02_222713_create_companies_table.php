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
            $table->string('ruc')->nullable();
            $table->string('name')->nullable();
            $table->string('direction')->nullable();
            $table->string('activity')->nullable();
            $table->string('category')->nullable();
            
            $table->boolean('state')->default(true);

            // Inventario
            $table->boolean('is_supplier')->default(false);
            $table->boolean('is_partner')->default(false);

            $table->text('observations')->nullable();

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
