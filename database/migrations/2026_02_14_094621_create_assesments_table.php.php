<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();

            // Link to the specific Child
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');

            // The Answers (Based on Dyslexia Indicators)
            // Using boolean (true = yes, false = no) is easiest for logic
            $table->boolean('confuses_letters')->default(false); // e.g. confuses b/d
            $table->boolean('knows_basic_sounds')->default(false); // e.g. letter sounds
            $table->boolean('can_rhyme')->default(false); // e.g. cat/hat
            $table->boolean('can_read_simple_words')->default(false); // e.g. dog/sun

            // The Calculated Result
            // We store this here so we know WHY they were placed in this level
            $table->integer('suggested_start_level'); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
