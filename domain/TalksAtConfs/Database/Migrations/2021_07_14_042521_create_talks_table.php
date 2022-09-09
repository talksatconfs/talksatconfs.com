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
        Schema::create('talks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');

            $table->string('title');
            $table->string('slug')->index();
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->dateTime('talk_date')->nullable();

            $table->foreignId('event_id');
            $table->unique(['event_id', 'talk_date', 'slug']);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('talks');
    }
};
