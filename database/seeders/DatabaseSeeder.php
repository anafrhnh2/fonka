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
          $this->call([
        GameLevelSeeder::class,
        ]);

        // GAME ACTIVITY
     
        
    }
}
