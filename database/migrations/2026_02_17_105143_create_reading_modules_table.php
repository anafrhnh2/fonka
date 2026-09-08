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
    Schema::create('reading_modules', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // e.g., "Asas Vokal"
        $table->string('description')->nullable();
        $table->string('icon')->default('📖');
        $table->integer('sequence'); // 1, 2, 3 to order them
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_modules');
    }
};
