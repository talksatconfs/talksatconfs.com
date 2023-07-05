<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('channel_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->dropColumn('duration');
            $table->dropColumn('published_at');
            $table->dropColumn('channel_id');
        });
    }
};
