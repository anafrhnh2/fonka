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
        Schema::table('child_weaknesses', function (Blueprint $table) {
            $table->foreignId('game_level_id')->nullable()->after('child_id')->constrained()->onDelete('cascade');
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
