<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OnboardingController extends Controller
{
    public function showAssessment()
    {
        return view('onboarding.child'); 
    }

    public function storeAssessment(Request $request)
    {
        $starterLevel = $this->calculateLevel($request);

        session([
            'child_data' => [
                'name' => $request->name,
                'age' => $request->age,
                'avatar_id' => 1,
                'starter_level' => $starterLevel,
            ]
        ]);

        return redirect()->route('register');
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
}