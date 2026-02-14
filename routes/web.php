<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChildRegistrationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;

// LANDING PAGE
Route::get('/', function () {return view('welcome');});

// 1. REGISTER 
Route::get('/onboarding/child', [OnboardingController::class, 'showAssessment'])->name('onboarding.child');
Route::post('/onboarding/child', [OnboardingController::class, 'storeAssessment'])->name('onboarding.child.store');

// 2. AFTER LOGIN IT DIRECTED TO DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// AUTH MIDDLEWARE AFTER LOGIN
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
