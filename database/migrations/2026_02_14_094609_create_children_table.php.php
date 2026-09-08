<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();

            // Link to Parent (User)
            // 'constrained' automatically looks for 'users' table based on conventions, 
            // but we specify 'users' just to be safe.
            $table->foreignId('parent_id')->constrained('users')->onDelete('cascade');

            // Child Profile Info
            $table->string('name');
            $table->integer('age');
            $table->string('avatar')->default('default_avatar.png'); // Path to image

            // Game Progress Tracking
            $table->integer('current_level')->default(1); // Starts at Level 1
            $table->integer('stars')->default(0); // Total stars earned
            $table->boolean('assessment_completed')->default(false); // Has taken the quiz?

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
