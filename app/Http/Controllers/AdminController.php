<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use App\Models\ReadingModule;
use App\Models\GameLevel;
use App\Models\Avatar;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalChildren = Child::count();
        $totalReadModules = ReadingModule::count();
        $totalGameLevels = GameLevel::count();
        $recentUsers = User::withCount('children')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalChildren',
            'totalReadModules',
            'totalGameLevels',
            'recentUsers'
        ));
    }

    // ----------------------------
    // MANAGE USERS
    // ----------------------------
    public function manageUsers(Request $request)
    {
        $query = User::query();
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && !empty($request->role)) {
            if ($request->role === 'admin') {
                $query->where('email', 'like', '%admin%');
            } else {
                $query->where('email', 'not like', '%admin%');
            }
        }
        $users = $query->with('children')->withCount('children')->latest()->paginate(10);

        return view('admin.manage-users', compact('users'));
        }

        
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        Child::where('parent_id', $user->id)->delete();  
        $user->delete();

        return redirect()->back()->with('success', 'User account and all related children profiles deleted successfully.');
    }


    // ----------------------------
    // MANAGE GAME LEVELS
    // ----------------------------
    public function manageGameLevels(Request $request)
    {
        $query = GameLevel::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('level_number', $search);
            });
        }

        $gameLevels = $query->orderBy('level_number', 'asc')->paginate(10);

        return view('admin.manage-game-levels', compact('gameLevels'));
    }

    public function updateGameLevel(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $level = GameLevel::findOrFail($id);
        $level->name = $request->name;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = 'level' . $level->level_number . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images/games/thumbnail'), $imageName);
            $level->image = $imageName;
        }

        $level->save();

        return redirect()->route('admin.manage-game-levels')->with('success', 'Game Level cosmetic assets updated successfully!');
    }


    // ----------------------------
    // MANAGE READING MODULES
    // ----------------------------
    public function manageReadingModules(Request $request)
    {
        $readingModules = ReadingModule::orderBy('module_number', 'asc')->paginate(10);
        return view('admin.manage-reading', compact('readingModules'));
    }


    public function updateReadingModule(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $module = ReadingModule::findOrFail($id);
        $module->title = $request->title;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = 'read' . $module->module_number . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('images'), $imageName);
            $module->image = $imageName;
        }

        $module->save();

        return redirect()->route('admin.manage-reading')->with('success', 'Reading Module assets updated successfully!');
    }


    // ----------------------------
    // MANAGE AVATAR
    // ----------------------------
    public function manageAvatars()
    {
        $avatars = Avatar::latest()->paginate(15);
        return view('admin.manage-avatars', compact('avatars'));
    }

    public function storeAvatar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path('images/avatars'), $imageName);
            $dbPath = 'images/avatars/' . $imageName;
        }

        Avatar::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $dbPath,
            'is_active' => 1,
        ]);

        return redirect()->route('admin.manage-avatars')->with('success', 'New cute avatar added successfully!');
    }


    public function toggleAvatarStatus($id)
    {
        $avatar = Avatar::findOrFail($id);
        $avatar->is_active = !$avatar->is_active;
        $avatar->save();

        return redirect()->back()->with('success', 'Avatar status toggled successfully.');
    }

    public function updateAvatar(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $avatar = \App\Models\Avatar::findOrFail($id);
        $avatar->name = $request->name;
        $avatar->price = $request->price;

        if ($request->hasFile('image')) {
            if ($avatar->image && file_exists(public_path($avatar->image))) {
                unlink(public_path($avatar->image));
            }
            
            $imageFile = $request->file('image');
            $imageName = time() . '_' . $imageFile->getClientOriginalName();
            $imageFile->move(public_path('images/avatars'), $imageName);
            $avatar->image = 'images/avatars/' . $imageName;
        }

        $avatar->save();

        return redirect()->route('admin.manage-avatars')->with('success', 'Avatar configuration updated successfully!');
    }

    public function destroyAvatar($id)
    {
        $avatar = Avatar::findOrFail($id);

        if ($avatar->image && file_exists(public_path($avatar->image))) {
            unlink(public_path($avatar->image));
        }

        $avatar->delete();

        return redirect()->back()->with('success', 'Avatar has been removed from the store.');
    }


}