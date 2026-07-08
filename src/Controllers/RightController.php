<?php

namespace Techaxion\UserAccess\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
class RightController extends Controller
{
    public function userlist(Request $request)
    {
        $users = User::all();
        return view('useraccess::right.userlist', compact('users'));
    }

    public function registeredModules(Request $request)
    {
        // Your logic to load registered modules
        return response()->json(['message' => 'Registered modules loaded']);
    }
}
