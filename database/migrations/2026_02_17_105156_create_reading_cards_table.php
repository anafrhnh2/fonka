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
    Schema::create('reading_cards', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reading_module_id')->constrained()->onDelete('cascade');
        
        $table->string('content');      // The text to display (e.g., "a", "ba", "Buku")
        $table->string('phonetic')->nullable(); // Pronunciation helper (e.g., "aaa")
        $table->string('image_url')->nullable(); // Optional image
        $table->string('audio_url')->nullable(); // Path to correct pronunciation audio
        $table->integer('sequence');    // Order inside the module
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_cards');
    }
};
