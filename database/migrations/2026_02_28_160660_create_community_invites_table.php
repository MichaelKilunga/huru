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
        Schema::create('community_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_thread_id')->constrained()->onDelete('cascade');
            $table->foreignId('inviter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('invitee_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('email')->nullable(); // For inviting non-users
            $table->string('token')->unique();
            $table->string('status')->default('pending'); // pending, accepted, expired
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_invites');
    }
};
