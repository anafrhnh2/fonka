<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GameLevel;

class GameLevelSeeder extends Seeder
{
    public function run()
    {
        // Create 10 levels
        for ($i = 1; $i <= 10; $i++) {
            GameLevel::create([
                'level_number' => $i,
                'name' => 'Tahap ' . $i,
                'max_stars' => 3,
            ]);
        }
    }
}
