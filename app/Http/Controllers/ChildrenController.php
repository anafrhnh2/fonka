<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;
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
        $child = \App\Models\Child::where('id', $id)
            ->where('parent_id', \Illuminate\Support\Facades\Auth::id())
            ->firstOrFail();

        session(['active_child_id' => $child->id]);
        session(['active_child_name' => $child->name]); 

        return redirect()->route('games.index');
    }

   
}
