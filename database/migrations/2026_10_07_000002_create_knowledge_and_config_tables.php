<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_entries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->text('summary')->nullable();
            $table->string('category', 30)->default('general')->index();
            $table->json('keywords')->nullable();
            $table->string('language', 5)->default('sw')->index();
            $table->string('source')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('prompt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category', 30)->default('general');
            $table->string('language', 5)->default('sw');
            $table->text('template');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'language', 'is_active']);
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('reference_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 30)->index();
            $table->string('phone', 60)->nullable();
            $table->string('alt_phone', 60)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('description_sw')->nullable();
            $table->text('description_en')->nullable();
            $table->boolean('is_emergency')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(100);
            $table->timestamps();
        });

        Schema::create('local_resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category', 30)->default('other');
            $table->string('region', 40)->nullable();
            $table->string('district', 40)->nullable();
            $table->string('ward', 60)->nullable();
            $table->text('location')->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('email')->nullable();
            $table->string('source')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['region', 'district']);
            $table->index(['category', 'region']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_resources');
        Schema::dropIfExists('reference_contacts');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('prompt_templates');
        Schema::dropIfExists('knowledge_entries');
    }
};
