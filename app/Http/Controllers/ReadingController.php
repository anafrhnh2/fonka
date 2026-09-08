<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\ReadingModule;
use App\Models\ReadingProgress;
use App\Models\Child;

class ReadingController extends Controller
{
   
    public function index()
    {
        $modules = \App\Models\ReadingModule::orderBy('module_number', 'asc')->get();
        
        // 1. Dapatkan ID pelajar yang sedang aktif
        $childId = session('active_child_id');
        
        // 2. Dapatkan semua progress pelajar ini dan susun ikut reading_module_id
        $progress = [];
        if ($childId) {
            $progress = \App\Models\ReadingProgress::where('child_id', $childId)
                        ->get()
                        ->keyBy('reading_module_id');
        }

        // 3. Hantar $progress ke fail Blade
        return view('reading.index', compact('modules', 'progress'));
    }

 
    public function learn($id)
    {
        $module = clone \App\Models\ReadingModule::find($id) ?? (object)['id' => $id];
        
        $childId = session('active_child_id');
        $currentProgress = 0;

        if ($childId) {
            $progressRecord = \App\Models\ReadingProgress::where('child_id', $childId)
                                ->where('reading_module_id', $id)
                                ->first();
            if ($progressRecord) {
                $currentProgress = $progressRecord->progress_percentage >= 100 ? 0 : $progressRecord->progress_percentage;
            }
        }

        // Penghalaan berdasarkan ID Modul
        if ($id == 1) { return view('reading.read1', compact('module', 'currentProgress')); } 
        elseif ($id == 2) { return view('reading.read2', compact('module', 'currentProgress')); }
        elseif ($id == 3) { return view('reading.read3', compact('module', 'currentProgress')); }
        elseif ($id == 4) { return view('reading.read4', compact('module', 'currentProgress')); }
        elseif ($id == 5) { return view('reading.read5', compact('module', 'currentProgress')); }
        elseif ($id == 6) { return view('reading.read6', compact('module', 'currentProgress')); }
        elseif ($id == 7) { return view('reading.read7', compact('module', 'currentProgress')); }
        elseif ($id == 8) { return view('reading.read8', compact('module', 'currentProgress')); }
        elseif ($id == 9) { return view('reading.read9', compact('module', 'currentProgress')); }
        elseif ($id == 10) { return view('reading.read10', compact('module', 'currentProgress')); }
        elseif ($id == 11) { return view('reading.read11', compact('module', 'currentProgress')); }
        elseif ($id == 12) { return view('reading.read12', compact('module', 'currentProgress')); }
        elseif ($id == 13) { return view('reading.read13', compact('module', 'currentProgress')); }

        return redirect()->route('reading.index')->with('error', 'Modul belum tersedia.');
    }


    public function saveProgress(Request $request)
    {
        $validated = $request->validate([
            'child_id'           => 'required|integer',
            'reading_module_id'  => 'required|integer',
            'progress_percentage'=> 'required|numeric|min:0|max:100',
            'score'              => 'required|integer|min:0',
            'is_completed'       => 'required|boolean',
        ]);

        // Pengiraan jumlah bintang secara dinamik untuk jadual reading_progresses (Maksimum skor vokal: 170)
        $starsEarned = 1;
        if ($validated['score'] >= 140) {
            $starsEarned = 3;
        } elseif ($validated['score'] >= 80) {
            $starsEarned = 2;
        }

        $progress = ReadingProgress::updateOrCreate(
            [
                'child_id' => $validated['child_id'],
                'reading_module_id' => $validated['reading_module_id'],
            ],
            [
                'progress_percentage' => $validated['progress_percentage'],
                'is_completed' => $validated['is_completed'],
                'score' => $validated['score'],
                'stars_earned' => $starsEarned, // Disimpan sekali mengikut keperluan database baharu
            ]
        );
        
        $child = \App\Models\Child::find($validated['child_id']);
        if ($child) {
            // Ambil jumlah skor daripada aktiviti bermain (game_progress)
            $gameScoreSum = DB::table('game_progress')->where('child_id', $child->id)->sum('score');
            
            // Ambil jumlah skor daripada aktiviti membaca (reading_progresses)
            $readingScoreSum = DB::table('reading_progresses')->where('child_id', $child->id)->sum('score');
            
            // Jumlahkan kedua-duanya sekali ke dalam lajur total_point anak
            $child->total_point = $gameScoreSum + $readingScoreSum;
            $child->save();
        }
        
        // 3. Kemas kini data pencapaian (Achievements)
        $completedModulesCount = DB::table('reading_progresses')
            ->where('child_id', $validated['child_id'])
            ->where('is_completed', 1)
            ->distinct('reading_module_id')
            ->count('reading_module_id');
            
        $juaraMembaca = \App\Models\Achievement::find(5); 

        if ($juaraMembaca) {
            $childAchievement = \App\Models\ChildAchievement::firstOrNew([
                'child_id' => $validated['child_id'],
                'achievement_id' => $juaraMembaca->id
            ]);
            $childAchievement->progress = min($completedModulesCount, $juaraMembaca->required_value);
            if ($childAchievement->progress >= $juaraMembaca->required_value) {
                $childAchievement->is_unlocked = 1;
            }

            $childAchievement->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress, Total Points, dan Achievement berjaya diselaraskan.',
            'data'    => $progress
        ]);
    }
}