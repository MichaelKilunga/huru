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
        Schema::table('community_posts', function (Blueprint $table) {
            $table->foreignId('parent_id')->after('community_thread_id')->nullable()->constrained('community_posts')->onDelete('cascade');
        });

        Schema::create('community_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // like, thumbs_up, thumbs_down
            $table->timestamps();
            $table->unique(['community_post_id', 'user_id', 'type']);
        });

        Schema::table('community_members', function (Blueprint $table) {
            $table->timestamp('last_read_at')->after('role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('community_posts', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::dropIfExists('community_reactions');

        Schema::table('community_members', function (Blueprint $table) {
            $table->dropColumn('last_read_at');
        });
    }
};
