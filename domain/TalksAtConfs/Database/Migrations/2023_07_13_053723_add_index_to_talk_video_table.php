<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('talk_video', function (Blueprint $table) {
            $table->index('talk_id');
            $table->index('video_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talk_video', function (Blueprint $table) {
            $table->dropIndex('talk_id');
            $table->dropIndex('video_id');
        });
    }
};
