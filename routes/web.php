<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChildRegistrationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\GameController;

// LANDING PAGE
Route::get('/', function () {return view('welcome');});

// 1. REGISTER 
Route::get('/onboarding/child', [OnboardingController::class, 'showAssessment'])->name('onboarding.child');
Route::post('/onboarding/child', [OnboardingController::class, 'storeAssessment'])->name('onboarding.child.store');

// 2. AFTER LOGIN IT DIRECTED TO DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/child/{id}/select', [ChildrenController::class, 'selectChild'])->name('child.select');

// 3. GAME MAIN MENU
Route::middleware(['auth'])->group(function () {
    
    // Route to show the Game Menu
    Route::get('/play', [GameController::class, 'index'])->name('games.index');
    Route::get('/play/{gameType}/levels', [GameController::class, 'showLevels'])->name('games.levels');
    // Show Activities (NEW - The list of games inside a level)
    Route::get('/play/{gameType}/level/{levelNumber}', [GameController::class, 'showActivities'])->name('games.activities');
    // Play Specific Activity 
    Route::get('/play/activity/{activityId}', [GameController::class, 'play'])->name('games.play');

});

// AUTH MIDDLEWARE AFTER LOGIN
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
