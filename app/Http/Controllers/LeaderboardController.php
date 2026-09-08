<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Child;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Ambil 10 pelajar terbaik berdasarkan jumlah mata (total_point)
        // Jika table anda guna nama lain, tukar 'total_point' ke nama kolum yang betul
        $topPlayers = Child::orderBy('total_point', 'desc')
                           ->take(10)
                           ->get();

        // Ambil ID anak yang sedang bermain untuk kita 'highlight' nama dia nanti
        $activeChildId = session('active_child_id');

        return view('leaderboard.index', compact('topPlayers', 'activeChildId'));
    }
}