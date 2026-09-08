<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Achievement;
use App\Models\ChildAchievement;
use Illuminate\Support\Facades\DB;

class AchievementController extends Controller
{
    public function index()
    {
        $childId = session('active_child_id');
        $child = Child::findOrFail($childId);

        // 1. SYNC PROGRESS SEBELUM PAPARAN
        $this->syncReadingAchievements($childId);
        $this->syncGameAchievements($childId); // <-- FUNGSI BARU DITAMBAH DI SINI

        $gameStats = DB::table('game_progress')
            ->where('child_id', $childId)
            ->selectRaw('SUM(score) as total_score, SUM(stars_earned) as total_stars')
            ->first();

        $gameScoreRecord = DB::table('children')
        ->where('id', $childId) // Make sure this is 'id' if that's the primary key
        ->first();

        $totalScore = $gameScoreRecord ? $gameScoreRecord->total_point : 0;
        $totalStars = $gameStats->total_stars ?? 0;

        $badges = Achievement::leftJoin('child_achievements', function ($join) use ($childId) {
            $join->on('achievements.id', '=', 'child_achievements.achievement_id')
                ->where('child_achievements.child_id', '=', $childId);
        })
        ->select(
            'achievements.*',
            DB::raw('COALESCE(child_achievements.progress, 0) as progress'),
            DB::raw('COALESCE(child_achievements.is_unlocked, 0) as unlocked'),
            DB::raw('COALESCE(child_achievements.reward_claimed, 0) as reward_claimed')
        )
        ->get()
        ->map(function ($badge) {
            return [
                'id' => $badge->id,   
                'title' => $badge->title,
                'description' => $badge->description,
                'icon' => $badge->icon,
                'bg' => $badge->bg_color,
                'border' => 'border-4 ' . $badge->border_color,
                'progress' => $badge->progress,
                'required' => $badge->required_value,
                'reward' => $badge->reward ?? 200,
                'unlocked' => $badge->unlocked,
                'reward_claimed' => $badge->reward_claimed ?? 0
            ];
        });

        return view('achievement.index', compact('child', 'totalScore', 'totalStars', 'badges'));
    }

    public function claim($id)
    {
        $childId = session('active_child_id');

        $record = ChildAchievement::where('child_id', $childId)
            ->where('achievement_id', $id)
            ->firstOrFail();

        if ($record->is_unlocked && !$record->reward_claimed) {
            $child = Child::find($childId);
            $child->total_point += 200; // ATAU guna $record->reward jika dinamik
            $child->save();

            $record->reward_claimed = 1;
            $record->save();
        }

        return back();
    }

    /**
     * Helper Function: Update Progress Juara Membaca
     */
    private function syncReadingAchievements($childId)
    {
        // Kira berapa modul berbeza yang telah disiapkan sepenuhnya
        $completedModulesCount = DB::table('reading_progresses')
            ->where('child_id', $childId)
            ->where('is_completed', 1)
            ->distinct('reading_module_id')
            ->count('reading_module_id');

        // Dapatkan achievement berjenis 'reading' (Contoh: Juara Membaca)
        $readingAchievements = Achievement::where('rule_type', 'reading')->get();

        foreach ($readingAchievements as $achievement) {
            $childAchievement = ChildAchievement::firstOrNew([
                'child_id' => $childId,
                'achievement_id' => $achievement->id
            ]);

            $progress = min($completedModulesCount, $achievement->required_value);
            
            $childAchievement->progress = $progress;

            if ($progress >= $achievement->required_value && !$childAchievement->is_unlocked) {
                $childAchievement->is_unlocked = 1;
            }

            $childAchievement->save();
        }
    }

    /**
     * Helper Function: Update Progress Game (Game Master, Master Huruf, dll)
     */
    private function syncGameAchievements($childId)
    {
        // Kira berapa level game berbeza yang telah disiapkan sepenuhnya
        $completedGamesCount = DB::table('game_progress')
            ->where('child_id', $childId)
            ->where('is_completed', 1)
            ->distinct('game_level_id')
            ->count('game_level_id');

        // Dapatkan achievement berjenis 'level_complete'
        $gameAchievements = Achievement::where('rule_type', 'level_complete')->get();

        foreach ($gameAchievements as $achievement) {
            // Cari rekod sedia ada, atau cipta rekod baharu jika belum wujud
            $childAchievement = ChildAchievement::firstOrNew([
                'child_id' => $childId,
                'achievement_id' => $achievement->id
            ]);

            // Elakkan progress melebihi required_value
            $progress = min($completedGamesCount, $achievement->required_value);
            
            $childAchievement->progress = $progress;

            // Semak dan unlock jika syarat cukup
            if ($progress >= $achievement->required_value && !$childAchievement->is_unlocked) {
                $childAchievement->is_unlocked = 1;
            }

            $childAchievement->save();
        }
    }
}