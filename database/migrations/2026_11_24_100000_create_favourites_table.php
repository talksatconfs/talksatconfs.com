<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Talks/events/conferences live on the domain connection, so there is no
        // foreign key to them; favouritable_type uses the app's morph map.
        Schema::create('favourites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('favouritable_type', 32);
            $table->unsignedBigInteger('favouritable_id');
            $table->timestamps();

            $table->unique(['user_id', 'favouritable_type', 'favouritable_id'], 'favourites_unique');
            $table->index(['favouritable_type', 'favouritable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favourites');
    }
};
