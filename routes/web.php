<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ChildRegistrationController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameProgressController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;

// LANDING PAGE
Route::get('/', function () {return view('welcome');});
Route::get('/about-dys', [DashboardController::class, 'about'])->name('about-dys.index');
Route::get('/about-us', [DashboardController::class, 'aboutUs'])->name('about-us.index');


Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ms'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

// REGISTER 
Route::get('/onboarding/child', [OnboardingController::class, 'showAssessment'])->name('onboarding.child');
Route::post('/onboarding/child', [OnboardingController::class, 'storeAssessment'])->name('onboarding.child.store');

//  AFTER LOGIN IT DIRECTED TO DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/child/{id}/select', [ChildrenController::class, 'selectChild'])->name('child.select');

// PARENT DASHBOARD
Route::prefix('parents')->name('parents.')->group(function () {
    Route::get('/', [ParentController::class, 'index'])->name('index'); 
    Route::get('/set-child/{id}', [ParentController::class, 'setChildActive'])->name('set_child'); 
    Route::get('/child-profile', [ParentController::class, 'childProfile'])->name('child-profile');
    Route::put('/child/{id}', [ParentController::class, 'update'])->name('child.update');
    Route::delete('/child/{id}', [ParentController::class, 'destroy'])->name('child.destroy');
    Route::post('/child/{id}/reset', [ParentController::class, 'resetProgress'])->name('child.reset');
    Route::get('/learning-progress', [ParentController::class, 'learningProgress'])->name('progress');
    Route::get('/report', [ParentController::class, 'report'])->name('report');
    Route::get('/achievement', [ParentController::class, 'achievement'])->name('achievement');
    Route::get('/account', [ParentController::class, 'account'])->name('account');
    Route::get('/create', [ParentController::class, 'create'])->name('create');
    Route::post('/store', [ParentController::class, 'store'])->name('store');
    
});

// CHILD AVATAR
Route::get('/pilih-avatar', [ChildrenController::class, 'editAvatar'])->name('avatar.edit');
Route::post('/simpan-avatar', [ChildrenController::class, 'updateAvatar'])->name('avatar.update');
Route::post('/beli-avatar', [ChildrenController::class, 'buyAvatar'])->name('avatar.purchase');

// GAME MAIN MENU
Route::middleware(['auth'])->group(function () {

    // GAME MAIN MENU
    Route::get('/menu', [GameController::class, 'index'])->name('games.index');
    // SHOW ALL LEVELS
    Route::get('/menu/levels', [GameController::class, 'showLevels'])->name('games.levels');
    // SHOW ACTIVITIES INSIDE A LEVEL
    Route::get('/menu/levels/{levelNumber}', [GameController::class, 'showActivities'])->name('games.activities');
    // PLAY SPECIFIC ACTIVITY
    Route::get('/menu/activity/{activityId}', [GameController::class, 'menu'])->name('games.play');
    // READING CONTENT
    Route::prefix('reading')->name('reading.')->middleware(['auth'])->group(function () {
    Route::get('/', [ReadingController::class, 'index'])->name('index');
    Route::get('/{module}', [ReadingController::class, 'learn'])->name('learn');
    Route::post('/save-progress', [ReadingController::class, 'saveProgress'])->name('save_progress');
    
    });

    // READ
    
    Route::post('/reading/save-progress', [ReadingController::class, 'saveProgress'])->name('reading.save_progress');
    // LEADERBOARD
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
    // PENCAPAIAN
    Route::get('/pencapaian', [AchievementController::class, 'index'])->name('achievement.index');
    Route::post('/achievement/claim/{id}', [AchievementController::class, 'claim'])->name('achievement.claim');

    // SAVE GAME PROGRESS
    Route::post('/game/save-progress', [GameProgressController::class, 'saveProgress']) ->name('game.save');
    });


    // AUTH MIDDLEWARE AFTER LOGIN
    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // ADMIN
    Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manage-users');
    Route::delete('/admin/manage-users/{id}', [AdminController::class, 'destroyUser'])->name('admin.manage-users.destroy');

    Route::get('/admin/manage-game-levels', [AdminController::class, 'manageGameLevels'])->name('admin.manage-game-levels');
    Route::put('/admin/manage-game-levels/{id}', [AdminController::class, 'updateGameLevel'])->name('admin.manage-game-levels.update');

    Route::get('/admin/manage-reading', [AdminController::class, 'manageReadingModules'])->name('admin.manage-reading');
    Route::put('/admin/reading-modules/update/{id}', [AdminController::class, 'updateReadingModule'])->name('admin.reading-modules.update');

    Route::get('/admin/manage-avatars', [AdminController::class, 'manageAvatars'])->name('admin.manage-avatars');
    Route::post('/admin/avatars/store', [AdminController::class, 'storeAvatar'])->name('admin.avatars.store');
    Route::patch('/admin/avatars/toggle/{id}', [AdminController::class, 'toggleAvatarStatus'])->name('admin.avatars.toggle');
    Route::delete('/admin/avatars/delete/{id}', [AdminController::class, 'destroyAvatar'])->name('admin.avatars.destroy');
    });



require __DIR__.'/auth.php';
