<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameProgress;
use App\Models\Child; 
use App\Models\Achievement;
use App\Models\ChildAchievement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; 

class GameProgressController extends Controller
{
   public function saveProgress(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'child_id' => 'required|integer',
            'game_level_id' => 'required|integer',
            'stars_earned' => 'required|integer',
            'score' => 'required|integer', 
            'is_completed' => 'boolean',
            'mistakes' => 'nullable|array' // Ensure mistakes is validated as an array
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {

                // 1. Fetch existing progress to check for high score
                $existingProgress = \App\Models\GameProgress::where('child_id', $validated['child_id'])                                               ->where('game_level_id', $validated['game_level_id'])
                                                ->first();

               \App\Models\GameProgress::updateOrCreate(
                    [
                        'child_id' => $validated['child_id'],
                        'game_level_id' => $validated['game_level_id']
                    ],
                    [
                        'stars_earned' => $validated['stars_earned'], 
                        'score' => $validated['score'], 
                        'is_completed' => $validated['is_completed'] ?? true
                    ]
                );

                // 2. Update child total points and level
                $child = \App\Models\Child::find($validated['child_id']);

                if ($child) {
                    // Recalculate total points
                    $child->total_point = \App\Models\GameProgress::where('child_id', $child->id)->sum('score');

                    if ($child->current_level <= $validated['game_level_id']) {
                        $child->current_level = $validated['game_level_id'] + 1;
                    }


                if ($request->has('mistakes') && is_array($request->mistakes)) {
                    foreach ($request->mistakes as $mistakeKey => $count) {
                        
                        $parts = explode('_', $mistakeKey);
                        
                        // Pastikan formatnya betul sebelum simpan (A_B)
                        if (count($parts) === 2) {
                            $targetItem = $parts[0]; // Nilai 'A'
                            $wrongItem = $parts[1];  // Nilai 'B'

                            $weakness = \App\Models\ChildWeakness::firstOrNew([
                                'child_id' => $child->id,
                                'game_level_id' => $validated['game_level_id'],
                                'the_question' => $targetItem, // Menyimpan Soalan
                                'item_name' => $wrongItem,     // Menyimpan Jawapan Salah
                            ]);

                            $weakness->wrong_count = ($weakness->wrong_count ?? 0) + (int)$count;
                            $weakness->category = 'Phonics'; 
                            
                            $weakness->save();
                        }
                    }
                }

                    $child->save();
                }

                // 4. Update achievements
                if (method_exists($this, 'updateAchievements')) {
                    $this->updateAchievements($validated['child_id']);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Progress & Analysis Saved!'
            ]);

        } catch (\Exception $e) {
            // This will print the actual error in storage/logs/laravel.log
            Log::error("Game Save Error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                // Return the actual error message while debugging so you can see it in the browser console
                'message' => 'Error: ' . $e->getMessage() 
            ], 500);
        }
    }

        private function updateAchievements($childId)
    {
        // kira progress budak
        $completedLevels = DB::table('game_progress')
            ->where('child_id', $childId)
            ->where('is_completed', 1)
            ->count();

        $totalStars = DB::table('game_progress')
            ->where('child_id', $childId)
            ->sum('stars_earned');

        $totalScore = DB::table('game_progress')
            ->where('child_id', $childId)
            ->sum('score');

        $readingCompleted = DB::table('reading_progresses')
            ->where('child_id', $childId)
            ->where('progress_percentage', 100)
            ->count();

        $achievements = Achievement::all();

        foreach ($achievements as $achievement) {

            $progress = 0;

            switch ($achievement->rule_type) {

                case 'level_complete':
                    $levelDone = DB::table('game_progress')
                        ->where('child_id', $childId)
                        ->where('game_level_id', $achievement->required_value)
                        ->where('is_completed', 1)
                        ->exists();

                    $readingDone = DB::table('reading_progresses')
                        ->where('child_id', $childId)
                        ->where('reading_module_id', $achievement->required_value)
                        ->where('progress_percentage', 100)
                        ->exists();

                    $progress = ($levelDone && $readingDone) ? 1 : 0;
                    break;


                case 'stars':
                    $progress = $totalStars;
                    break;

                case 'score':
                    $progress = $totalScore;
                    break;

               case 'reading':
                $readingDone = DB::table('reading_progresses')
                    ->where('child_id', $childId)
                    ->where('reading_module_id', $achievement->reading_module_id)
                    ->where('progress_percentage', 100)
                    ->exists();

                $progress = $readingDone ? 1 : 0;
                break;
            }

           $record = ChildAchievement::firstOrCreate([
            'child_id' => $childId,
            'achievement_id' => $achievement->id
        ]);

        $record->progress = $progress;

       if ($progress >= $achievement->required_value) {

            $record->is_unlocked = 1;

        }

        $record->save();
        }
    }

       
}