<?php

namespace Techaxion\UserAccess\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Techaxion\UserAccess\Models\Roles;
use Techaxion\UserAccess\Models\ModuleAction;
use Techaxion\UserAccess\Models\InterfaceAccess;
use Techaxion\UserAccess\Models\RolesAction;

class RoleController extends Controller{
    private $useraccessData;
    public function __construct(){
       $this->useraccessData = USERACCESS_CONTENT;
    }
    public function list(){
        $rolesList = Roles::get();
        foreach ($rolesList as $role) {
            $role->interface_access = $role->interface_access_names;
        }
        $roles = $rolesList->toArray();
 
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.roles.index' : 'useraccess::bootstrap.roles.index';
        return view($frontendView, ['roles' => $roles]);
    }
    public function create(){
        $moduleAction = new ModuleAction();
        $allRoutes = $moduleAction->getAllActions();
        $accessFor = InterfaceAccess::get()->toArray();
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.roles.create' : 'useraccess::bootstrap.roles.create';
        return view($frontendView, compact('allRoutes','accessFor'));
    }
    public function store(Request $request){
        $interface_access = $request->interface_access;
        if (is_array($interface_access)) {
            $interface_access = implode(',', $interface_access);
        }
        $inputData = [
            'name' => $request->name,
            'access' => $request->access,
            'interface_access' => $interface_access,
            'description' => $request->description
        ];
        $validator = Validator::make( $inputData, [
            'name' => 'required',
            'access' => 'required',
            'interface_access' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/useraccess/role/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $role_id = Roles::insertGetId($inputData);
            $actions = $request['selNodes'] ?? [];
            // echo "<pre>"; print_r(count($actions)); die;
            if(count($actions) > 0){
                $insertactions = [];
                foreach ($actions as $key => $action) {
                    $insertactions[] = [
                        'role_id' => $role_id, 'action_id' => $action
                    ];
                }
                RolesAction::insert($insertactions) ;
            }
            return redirect('/useraccess/role/list')->with('success', 'Role created successfully.');
        }
    }
    public function edit(Roles $role){
        $moduleAction = new ModuleAction();
        $allRoutes = $moduleAction->getAllActions();
        $accessFor = InterfaceAccess::get()->toArray();
        $roleActions = $role->getRoleActions($role->id);
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.roles.edit' : 'useraccess::bootstrap.roles.edit';
        return view($frontendView, compact('role','allRoutes','accessFor','roleActions'));
    }

    public function permissions(Request $request){
        $accessForId = $request->query('interface_access');
        
        if (!$accessForId) {
            return response()->json(['data' => [], 'message' => 'Interface access is required'], 422);
        }

        $query = ModuleAction::where('status', 1);
        if (is_array($accessForId)) {
            $query->where(function($q) use ($accessForId) {
                foreach ($accessForId as $id) {
                    if ($id) {
                        $q->orWhereRaw('FIND_IN_SET(?, menu_type)', [$id]);
                    }
                }
            });
        } else {
            $ids = explode(',', $accessForId);
            $query->where(function($q) use ($ids) {
                foreach ($ids as $id) {
                    if ($id) {
                        $q->orWhereRaw('FIND_IN_SET(?, menu_type)', [$id]);
                    }
                }
            });
        }

        $actions = $query->orderBy('menu_sequence', 'ASC')
            ->orderBy('menu_order', 'ASC')
            ->get(['id', 'module_label', 'menu_label', 'action', 'menu_status', 'extra_options', 'menu_type'])
            ->toArray();

        $grouped = [];
        foreach ($actions as $action) {
            $grouped[$action['module_label']][] = $action;
        }

        return response()->json(['data' => $grouped]);
    }

    public function update(Request $request,Roles $role){
        $interface_access = $request->interface_access;
        if (is_array($interface_access)) {
            $interface_access = implode(',', $interface_access);
        }
        $inputData = [
            'name' => $request->name,
            'access' => $request->access,
            'interface_access' => $interface_access,
            'description' => $request->description
        ];
        $validator = Validator::make( $inputData, [
            'name' => 'required',
            'access' => 'required',
            'interface_access' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/useraccess/role/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $rolesAction = new RolesAction();
            $rolesAction->deleteRoleActions($role->id);
            $inputData['updated_at'] = now();
            $role_id = Roles::where('id',$role->id)->update($inputData);
            $actions = $request['selNodes'] ?? [];
            if(count($actions) > 0){
                $insertactions = [];
                foreach ($actions as $key => $action) {
                    $insertactions[] = [
                        'role_id' => $role->id, 'action_id' => $action
                    ];
                }
                RolesAction::insert($insertactions);
            }
            return redirect('/useraccess/role/edit/'.$role->id)->with('success', 'Role updated successfully.');
        }
    }
}
