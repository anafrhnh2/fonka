<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\GameProgress;
use App\Models\ChildWeakness;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
 
    // ------------------
    // DASHBOARD
    // ------------------
   public function index()
{
    $children = Child::where('parent_id', Auth::id())->get();
    $childId = session('active_child_id') ?? ($children->first()->id ?? null);

    if (!$childId) {
        return redirect()->route('parents.child-profile')->with('info', 'Please add a child profile first.');
    }

    $child = Child::find($childId);
    $gameProgress = \App\Models\GameProgress::where('child_id', $childId)->get();

    $skillMapping = [
        'Letter Recognition' => [1, 4], 
        'Phonics Sounds'     => [2, 3, 5, 7], 
        'Word Blending'      => [6, 8, 9, 10, 11, 12, 13, 14, 15, 18], 
        'Sentence Reading'   => [16, 17, 19, 20] 
    ];

    $skillPercentages = [];
    foreach ($skillMapping as $skillName => $levels) {
        $starsEarned = $gameProgress->whereIn('game_level_id', $levels)->sum('stars_earned');
        $maxStars = count($levels) * 3; 
        $skillPercentages[$skillName] = $maxStars > 0 ? round(($starsEarned / $maxStars) * 100) : 0;
    }

    $overallProgress = count($skillPercentages) > 0 ? round(array_sum($skillPercentages) / count($skillPercentages)) : 0;
    $completedLessonsCount = $gameProgress->where('stars_earned', '>', 0)->count();
    $recentActivities = \App\Models\GameProgress::where('child_id', $childId)->orderBy('updated_at', 'desc')->take(5)->get();

    $days = collect();
    $chartData = collect();
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $days->push($date->format('D'));
        $chartData->push(\App\Models\GameProgress::where('child_id', $childId)->whereDate('updated_at', $date)->count());
    }

    return view('parents.index', compact(
        'child', 
        'children',
        'skillPercentages', 
        'overallProgress', 
        'completedLessonsCount', 
        'recentActivities',
        'days',
        'chartData'
    ));
}

    public function setChildActive($id)
{
    $child = Child::where('id', $id)->where('parent_id', Auth::id())->firstOrFail();

    session(['active_child_id' => $child->id]);

    return redirect()->back()->with('success', "Switched to {$child->name}'s profile.");
}

    // ------------------
    // CHILD PROFILE MANAGEMENT
    // ------------------
    public function create()
    {
        return view('parents.child-create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'age' => 'required',
        ]);

        $starterLevel = $this->calculateLevel($request);

        $child = Child::create([
            'parent_id' => Auth::id(), 
            'name' => $request->name,
            'age' => $request->age,
            'avatar_id' => 1,
            'current_level' => $starterLevel,
            'total_point' => 0,
        ]);

        session(['active_child_id' => $child->id]);

        return redirect()->route('parents.child-profile')
            ->with('success', 'Child profile created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:20',
        ]);

        // 2. Find the child and ensure they belong to the logged-in parent (Security)
        $child = Child::where('id', $id)
                      ->where('parent_id', Auth::id())
                      ->firstOrFail();

        // 3. Update the database record
        $child->update([
            'name' => $request->name,
            'age' => $request->age,
        ]);

        // 4. Redirect back with the SweetAlert success message you already have set up!
        return redirect()->back()->with('success', 'Child information updated successfully!');
    }

    private function calculateLevel(Request $request)
    {
        // Jika anak SUDAH BOLEH membaca perkataan pendek sendiri (Q4 = Yes)
        // Mereka patut terus lompat ke Tahap 15 (Digraf) atau 16 (Ayat Mudah)
        if ($request->reads_words === 'yes') {
            return 15; 
        }

        //  Jika belum boleh baca, tapi BOLEH gabung bunyi (b-a = ba) (Q3 = Yes)
        // Mereka patut mula di Tahap 8 (Kereta Api Perkataan - Bina perkataan mudah)
        if ($request->can_blend === 'yes') {
            return 8;
        }

        //  Jika belum boleh gabung, tapi TAHU bunyi asas (S = /sss/) (Q2 = Yes)
        // Mereka patut mula di Tahap 5 (Bina Suku Kata KV)
        if ($request->knows_sounds === 'yes') {
            return 5;
        }

        // Jika tak tahu bunyi asas, tapi TIDAK keliru huruf (b & d) (Q1 = No)
        // Mereka tak perlukan Tahap 1 yang terlalu asas, boleh mula di Tahap 3 (Memadankan Bunyi Vokal)
        if ($request->confuses_letters === 'no') {
            return 3;
        }

        // Jika semua jawapan di atas adalah sebaliknya (Keliru huruf & tak tahu asas)
        // Mereka wajib mula dari kosong iaitu Tahap 1 (Jejak Huruf)
        return 1;
    }
    
    public function destroy($id)
    {
        $child = Child::findOrFail($id);
        $child->delete();

        if (session('active_child_id') == $id) {
            session()->forget('active_child_id'); 
        }

        return redirect()->route('parents.child-profile')->with('success', 'Child profile deleted successfully.');
    }

    // ------------------
    // OVERVIEW
    // ------------------
    public function dashboard()
    {
        $childId = session('active_child_id');
        
        if (!$childId) {
            return redirect()->route('parents.index')->with('error', 'Please select a child profile first.');
        }

        $child = Child::find($childId);
        $gameProgress = GameProgress::where('child_id', $childId)->get();

        $skillMapping = [
            'Letter Recognition' => [1, 4], 
            'Phonics Sounds'     => [2, 3, 5, 7], 
            'Word Blending'      => [6, 8, 9, 10, 11, 12, 13, 14, 15, 18], 
            'Sentence Reading'   => [16, 17, 19, 20] 
        ];

        $skillPercentages = [];

        foreach ($skillMapping as $skillName => $levels) {
            $starsEarned = 0;
            $maxStars = count($levels) * 3; 

            foreach ($levels as $levelId) {
                $record = $gameProgress->where('game_level_id', $levelId)->first();
                if ($record) {
                    $starsEarned += $record->stars_earned;
                }
            }

            if ($maxStars > 0) {
                $percentage = round(($starsEarned / $maxStars) * 100);
            } else {
                $percentage = 0;
            }

            $skillPercentages[$skillName] = $percentage;
        }

        return view('parents.index', compact('child', 'skillPercentages'));
    }

    public function resetProgress($id)
    {
        $child = Child::where('id', $id)
                      ->where('parent_id', Auth::id())
                      ->firstOrFail();

        \App\Models\GameProgress::where('child_id', $child->id)->delete();

        \App\Models\ReadingProgress::where('child_id', $child->id)->delete();

        $child->update([
            'current_level' => 1
        ]);

        return redirect()->back()->with('success', "{$child->name}'s progress has been reset, but points were saved!");
    }

    public function childProfile()
        {
            $children = Child::where('parent_id', Auth::id())->get();
            $childId = session('active_child_id');
            $completedLevels = GameProgress::where('child_id', $childId)->where('is_completed', 1)->count();

            $child = null;
            if ($childId) {
                $child = Child::find($childId);
            }

            if (!$child) {
                $child = $children->first();
                
                if ($child) {
                    session(['active_child_id' => $child->id]);
                } else {
                    session()->forget('active_child_id');
                }
            }

            if (!$child) {
                return redirect()->route('parents.create')->with('info', 'Please add a child profile first to view settings.');
            }

            return view('parents.child-profile', compact('child', 'children', 'completedLevels'));
        }

    // ------------------
    // LEARNING PROGRESS
    // ------------------
     public function learningProgress()
        {
            $children = Child::where('parent_id', Auth::id())->get();

            $childId = session('active_child_id');
            $child = $childId 
                ? Child::find($childId) 
                : $children->first();

            if (!$child) {
                return view('parents.progress', compact('children'));
            }

            $levels = \App\Models\GameLevel::orderBy('level_number', 'asc')->get();

            $progress = \App\Models\GameProgress::where('child_id', $child->id)
                ->get()
                ->keyBy('game_level_id');

            $readingModules = \App\Models\ReadingModule::orderBy('module_number', 'asc')->get();

            $readProgresses = \App\Models\ReadingProgress::where('child_id', $child->id)
                ->get()
                ->keyBy('reading_module_id');

            $weaknesses = \App\Models\ChildWeakness::where('child_id', $child->id)
                ->orderBy('wrong_count', 'desc')
                ->get();

            return view('parents.progress', compact('child', 'children', 'levels', 'progress', 'readingModules', 'readProgresses', 'weaknesses'));
        }

    public function report()
    {
        $children = Child::where('parent_id', Auth::id())->get();

        $childId = session('active_child_id');
        $child = $childId 
            ? Child::find($childId) 
            : $children->first();

        if (!$child) {
            return view('parents.report', compact('children'));
        }

        $progress = GameProgress::where('child_id', $child->id)->get();

        $totalLevelsPlayed = $progress->count();
        $completedLevels = $progress->where('stars_earned', '>', 0)->count();
        $totalStars = $progress->sum('stars_earned');
        $totalScore = $progress->sum('score');

        $completedReadingModules = \App\Models\ReadingProgress::where('child_id', $child->id)
            ->where('is_completed', 1)
            ->count();

        $recentActivities = GameProgress::where('child_id', $child->id)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('parents.report', compact(
            'child',
            'children',
            'totalLevelsPlayed',
            'completedLevels',
            'totalStars',
            'totalScore',
            'recentActivities',
            'completedReadingModules',
        ));
    }

    public function achievement()
    {
        $children = Child::where('parent_id', Auth::id())->get();

        $childId = session('active_child_id');
        $child = $childId 
            ? Child::find($childId) 
            : $children->first();

        if (!$child) {
            return redirect()->route('parents.create')->with('info', 'Please add a child profile first.');
        }

        $progress = \App\Models\GameProgress::where('child_id', $child->id)->get();
        $totalStars = $progress->sum('stars_earned');
        $totalPoints = $child->total_point ?? 0; 
        $unlockedBadges = 5; 
        $totalBadges = 15;

        return view('parents.achievement', compact(
            'child', 
            'children', 
            'totalStars', 
            'totalPoints', 
            'unlockedBadges', 
            'totalBadges'
        ));
    }

    public function account()
    {
        return view('parents.account');
    }
}