<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GameLevel;

class GameLevelSeeder extends Seeder
{
    public function run()
    {
        $gameTypes = ['letter-recognition', 'syllables', 'reading', 'listening'];

        foreach ($gameTypes as $type) {
            // Create 10 levels for each game type
            for ($i = 1; $i <= 10; $i++) {
                GameLevel::create([
                    'game_type' => $type,
                    'level_number' => $i,
                    'name' => 'Tahap ' . $i,
                ]);
            }
        }
    }
}