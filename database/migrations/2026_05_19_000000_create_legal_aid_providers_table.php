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
        Schema::create('legal_aid_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->text('location')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('language', 5)->default('sw');
            $table->timestamps();

            // Add index for faster location queries
            $table->index(['region', 'district']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_aid_providers');
    }
};
