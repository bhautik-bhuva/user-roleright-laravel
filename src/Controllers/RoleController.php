<?php

namespace Techaxion\UserAccess\Controllers;

use App\Http\Controllers\Controller;
use Techaxion\UserAccess\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Techaxion\UserAccess\Models\ModuleAction;

class RoleController extends Controller
{
    public function add(Request $request)
    {
        $moduleAction = new ModuleAction();
        $dataAdmin = $moduleAction->getAllActions();
        return view('useraccess::roles.create', ['allRoutes' => $dataAdmin]);
    }

    public function edit(Request $request, $id)
    {
        // Your logic to edit a role
        return response()->json(['message' => 'Role edited successfully']);
    }
    
    public function list(Request $request)
    {
        $roles = Roles::get()->all();
        return view('useraccess::roles.index', ['roles' => $roles]);
        // return response()->json(['message' => 'Roles listed successfully']);
    }

    public function datatable(Request $request)
    {
        // Your logic to get roles for datatable
        return response()->json(['message' => 'Roles for datatable']);
    }
}