<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
   public function index()
    {
        $parentId = Auth::id();
        $children = Child::where('parent_id', $parentId)->get();

        return view('dashboard', compact('children'));
    }

   
}
