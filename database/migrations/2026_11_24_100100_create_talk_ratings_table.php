<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talk_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('talk_id');
            $table->unsignedTinyInteger('rating');
            $table->timestamps();

            $table->unique(['user_id', 'talk_id']);
            $table->index('talk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talk_ratings');
    }
};
