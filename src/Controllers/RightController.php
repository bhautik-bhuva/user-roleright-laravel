<?php

namespace Techaxion\UserAccess\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RightController extends Controller
{
    public function listRight(Request $request)
    {
        // Your logic to list rights
        return response()->json(['message' => 'List of rights']);
    }

    public function registeredModules(Request $request)
    {
        // Your logic to load registered modules
        return response()->json(['message' => 'Registered modules loaded']);
    }
}