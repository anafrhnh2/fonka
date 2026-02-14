<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OnboardingController extends Controller
{
    // Show assessment form
    public function showAssessment()
    {
        return view('onboarding.child'); 
    }

    // Store assessment in session and calculate starter level
    public function storeAssessment(Request $request)
    {
        $starterLevel = $this->calculateLevel($request);

        session([
            'child_data' => [
                'name' => $request->name,
                'age' => $request->age,
                'gender' => $request->gender,
                'starter_level' => $starterLevel,
            ]
        ]);

        return redirect()->route('register');
    }
   
    // Example scoring method
    private function calculateLevel(Request $request)
    {
        $score = 0;

        if ($request->confuses_letters == 0) $score++;
        if ($request->knows_basic_sounds == 1) $score++;
        if ($request->can_rhyme == 1) $score++;
        if ($request->can_read_simple_words == 1) $score++;

        if ($score <= 1) return 1;
        if ($score == 2) return 2;
        return 3;

        //  // 4️⃣ Save Assessment
        // Assessment::create([
        //     'child_id' => $child->id,
        //     'confuses_letters' => $request->confuses_letters,
        //     'knows_basic_sounds' => $request->knows_basic_sounds,
        //     'can_rhyme' => $request->can_rhyme,
        //     'can_read_simple_words' => $request->can_read_simple_words,
        //     'suggested_start_level' => $level,
        // ]);

        // // 5️⃣ Update Child
        // $child->update([
        //     'current_level' => $level,
        //     'assessment_completed' => true,
        // ]);
    }
}
