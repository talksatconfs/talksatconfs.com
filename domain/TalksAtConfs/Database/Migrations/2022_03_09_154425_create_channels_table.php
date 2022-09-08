<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('source');
            $table->string('key');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('custom_url')->nullable();

            $table->unique(['source', 'key']);

            $table->timestamps();
        });

        Schema::create('channel_video', function (Blueprint $table) {
            $table->foreignId('channel_id');
            $table->foreignId('video_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
        Schema::dropIfExists('channel_video');
    }
};
