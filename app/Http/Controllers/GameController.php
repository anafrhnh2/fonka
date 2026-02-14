<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\GameLevel;
use App\Models\GameProgress;
use App\Models\GameActivity;
class GameController extends Controller
{
  
    public function index()
    {
        //  resources/views/games/index.blade.php
        return view('games.index');
    }


    public function showLevels($gameType)
    {
        $childId = session('active_child_id'); 
        
        if (!$childId) {
            return redirect()->route('dashboard')->with('error', 'Please select a child profile first.');
        }

        $levels = GameLevel::where('game_type', $gameType)
                        ->orderBy('level_number')
                        ->get();

        $totalLevels = $levels->count(); 

        $progress = GameProgress::where('child_id', $childId)
                                ->whereIn('game_level_id', $levels->pluck('id'))
                                ->get()
                                ->keyBy('game_level_id');
        $currentLevel = 1;
        foreach ($levels as $level) {
            if ($progress->has($level->id) && $progress[$level->id]->is_completed) {
                $currentLevel = $level->level_number + 1;
            }
        }

        return view('games.levels', compact('gameType', 'levels', 'progress', 'currentLevel', 'totalLevels'));
    }


    public function showActivities($gameType, $levelNumber)
    {
        // 1. Get Current Child
        $childId = session('active_child_id');
        $child = Child::findOrFail($childId);

        // 2. Get the Level ID
        $level = GameLevel::where('game_type', $gameType)
                        ->where('level_number', $levelNumber)
                        ->firstOrFail();

        // 3. Fetch Activities FILTERED by Child's Age
        // We show games where the min_age is less than or equal to the child's age
        $activities = GameActivity::where('game_level_id', $level->id)
                                ->where('min_age', '<=', $child->age)
                                ->get();

        return view('games.activities', compact('gameType', 'level', 'activities', 'child'));
    }






}