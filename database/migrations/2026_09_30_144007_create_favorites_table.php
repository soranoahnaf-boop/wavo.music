<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('song_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            // 1 user tidak bisa favorite lagu yang sama lebih dari 1 kali
            $table->unique(['user_id', 'song_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};

