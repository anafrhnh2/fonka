<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('child_weaknesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_level_id')->nullable()->after('child_id')->constrained()->onDelete('cascade');
            $table->string('item_name'); // e.g., "A", "Ba", "Ca"
            $table->string('category')->default('Phonics');
            $table->integer('wrong_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('child_weaknesses', function (Blueprint $table) {
        $table->dropForeign(['game_level_id']);
        $table->dropColumn('game_level_id');
    });
    }
};
