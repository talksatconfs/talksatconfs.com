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
        Schema::create('links', function (Blueprint $table) {
            $table->id();

            $table->string('link')->unique();

            $table->timestamps();
        });

        Schema::create('linkables', function (Blueprint $table) {
            $table->foreignId('link_id')->constrained()->cascadeOnDelete();

            $table->morphs('linkable');

            $table->unique(['link_id', 'linkable_id', 'linkable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('links');
        Schema::drop('linkables');
    }
};
