<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('game_levels', function (Blueprint $table) {
        $table->id();
        $table->string('game_type'); // e.g., 'letter-recognition', 'syllables'
        $table->integer('level_number'); // 1, 2, 3...
        $table->string('name')->nullable(); // e.g., 'Level 1: Vowels'
        $table->integer('max_stars')->default(3);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_levels');
    }
};
