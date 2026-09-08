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
        Schema::create('game_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_level_id')->constrained('game_levels')->onDelete('cascade');
            $table->string('title');
            $table->string('type'); 
            $table->integer('min_age')->default(5);
            $table->json('content')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_activities');
    }
};
