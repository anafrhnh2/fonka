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
        return view('games.index');
    }

    public function menu($activityId)
    {
        $activity = GameActivity::findOrFail($activityId);

        $gameData = json_decode($activity->content);

        if (view()->exists('games.' . $activity->type)) {
            return view('games.' . $activity->type, compact('activity', 'gameData'));
        }

        return abort(404, "Game file not found.");
    }

    public function showLevels()
    {
        $childId = session('active_child_id');

        if (!$childId) {
            return redirect()->route('dashboard')
                ->with('error', 'Please select a child profile first.');
        }

        $child = Child::findOrFail($childId);

        $levels = GameLevel::orderBy('level_number')->get();
        $totalLevels = $levels->count();

        $progress = GameProgress::where('child_id', $childId)
            ->whereIn('game_level_id', $levels->pluck('id'))
            ->get()
            ->keyBy('game_level_id');

        
        $currentLevel = $child->current_level ?? 1;

        return view('games.levels', compact(
            'levels',
            'progress',
            'currentLevel',
            'totalLevels'
        ));
    }

    public function showActivities($levelNumber)
    {
        $childId = session('active_child_id');

        if (!$childId) {
            return redirect()->route('dashboard')
                ->with('error', 'Sila pilih profil anak terlebih dahulu.');
        }

        $child = Child::findOrFail($childId);

        $level = GameLevel::where('level_number', $levelNumber)
            ->firstOrFail();

        $activities = GameActivity::where('game_level_id', $level->id)
            ->where('min_age', '<=', $child->age)
            ->get();

        $viewName = 'games.level' . $levelNumber;

        if (view()->exists($viewName)) {
            return view($viewName, compact('level', 'activities', 'child'));
        }

        return abort(404, "Tahap permainan belum disediakan.");
    }
}
