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
        \App\Models\SystemSetting::updateOrCreate(
            ['key' => 'web_chat_limit'],
            ['value' => '4']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\SystemSetting::where('key', 'web_chat_limit')->delete();
    }
};
