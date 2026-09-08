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
    Schema::create('game_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
        $table->foreignId('game_level_id')->constrained('game_levels')->onDelete('cascade');
        $table->integer('stars_earned')->default(0);
        $table->boolean('is_completed')->default(false);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_progress');
    }
};
