<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\GameLevel;
use App\Models\GameActivity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // GAME ACTIVITY
        $level1 = GameLevel::where('game_type', 'letter-recognition')
                   ->where('level_number', 1)
                   ->first();

        if ($level1) {
            // Game for 5 year olds
            GameActivity::create([
                'game_level_id' => $level1->id,
                'title' => 'Pop the Balloon (Mudah)',
                'type' => 'game',
                'min_age' => 5, // Shows for everyone 5+
            ]);

            // Game for 7 year olds (Harder)
            GameActivity::create([
                'game_level_id' => $level1->id,
                'title' => 'Word Puzzle (Sukar)',
                'type' => 'quiz',
                'min_age' => 7, // Only shows if child is 7+
            ]);
        }
    }
}
