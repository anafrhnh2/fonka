<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChildrenController extends Controller
{
    public function index()
    {
        $parentId = Auth::id();
        $children = Child::where('parent_id', $parentId)->get();

        return view('index', compact('children'));
    }

    public function selectChild($id)
    {
        $child = Child::where('id', $id)
            ->where('parent_id', Auth::id())
            ->firstOrFail();

        session(['active_child_id' => $child->id]);
        session(['active_child_name' => $child->name]); 

        return redirect()->route('games.index');
    }

    public function editAvatar()
    {
        $childId = session('active_child_id');
        $activeChild = Child::findOrFail($childId);

        // Fetch all active avatars from the database
        $allAvatars = DB::table('avatars')->where('is_active', 1)->get();

        // Fetch IDs of avatars the child has explicitly purchased
        $ownedAvatarIds = DB::table('child_avatars')
            ->where('child_id', $childId)
            ->pluck('avatar_id')
            ->toArray();

        $ownedAvatars = [];
        $shopAvatars = [];

        foreach ($allAvatars as $avatar) {
            $avatarData = [
                'id' => $avatar->id,
                'name' => $avatar->name,
                'path' => $avatar->image, 
                'price' => $avatar->price, 
            ];

            if ($avatar->price == 0 || in_array($avatar->id, $ownedAvatarIds)) {
                $ownedAvatars[] = $avatarData;
            } else {
                $shopAvatars[] = $avatarData;
            }
        }

        $currentAvatarId = $activeChild->avatar_id ?? 1;
        $userPoints = $activeChild->total_point ?? 0;

        return view('child.avatar', compact('ownedAvatars', 'shopAvatars', 'currentAvatarId', 'userPoints', 'activeChild'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'selected_avatar' => 'required|integer'
        ]);

        $childId = session('active_child_id');

        $avatarToEquip = DB::table('avatars')->where('id', $request->selected_avatar)->first();

        if (!$avatarToEquip) {
            return back()->with('error', 'Avatar tidak wujud!');
        }

        $owned = DB::table('child_avatars')
            ->where('child_id', $childId)
            ->where('avatar_id', $request->selected_avatar)
            ->exists();

        if (!$owned && $avatarToEquip->price > 0) {
            return back()->with('error', 'Avatar belum dibeli!');
        }

        Child::where('id', $childId)
            ->update(['avatar_id' => $request->selected_avatar]);

        return redirect()->route('games.index')
            ->with('success', 'Avatar berjaya ditukar!');
    }

    public function buyAvatar(Request $request)
    {
        $child = Child::find(session('active_child_id'));

        $avatar = DB::table('avatars')
            ->where('id', $request->avatar_id)
            ->first();

        if (!$avatar) {
            return back()->with('error', 'Avatar tidak wujud');
        }

        if ($child->total_point < $avatar->price) {
            return back()->with('error', 'Point tidak cukup!');
        }

        $alreadyOwned = DB::table('child_avatars')
            ->where('child_id', $child->id)
            ->where('avatar_id', $avatar->id)
            ->exists();

        if ($alreadyOwned || $avatar->price == 0) {
            return back()->with('error', 'Sudah dimiliki!');
        }

        $child->total_point -= $avatar->price;
        $child->save();

        DB::table('child_avatars')->insert([
            'child_id' => $child->id,
            'avatar_id' => $avatar->id,
            'purchased_at' => now()
        ]);

        return back()->with('success', 'Avatar berjaya dibeli!');
    }
}