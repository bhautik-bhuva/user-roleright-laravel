<?php

namespace Techaxion\UserAccess\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Techaxion\UserAccess\Models\Roles;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Techaxion\UserAccess\Models\ModuleAction;
use Techaxion\UserAccess\Models\RightAction;
use Techaxion\UserAccess\Models\UserRoleMapping;
class UserController extends Controller
{   
    private $useraccessData;
    public function __construct(){
        $this->useraccessData = USERACCESS_CONTENT;
    }
    public function list(Request $request)
    {
        $users = User::all();
        $roles = Roles::all();
        $userRoleMappingData = UserRoleMapping::all();
        $rolesArr = [];
        foreach ($roles as $role) {
            $rolesArr[$role->id] = $role->name;
        }
        $userRoleMappingArr = [];
        foreach ($userRoleMappingData as $userRoleMapping) {
            $userRoleMappingArr[$userRoleMapping->user_id] = $userRoleMapping->role_id;
        }
        foreach ($users as $user) {
            $role_id = $userRoleMappingArr[$user->id] ?? "";
            $user->role = $rolesArr[$role_id] ?? "-";
        }
        // p($users);
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.user.index' : 'useraccess::bootstrap.user.index';
        return view($frontendView, compact('users'));
    }
    public function create(){
        $roles = Roles::all()->toArray();
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.user.create' : 'useraccess::bootstrap.user.create';
        return view($frontendView, compact('roles'));
    }
    public function store(Request $request){
        $inputData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'role' => $request->role,
        ];
        $validator = Validator::make( $inputData, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/useraccess/user/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $user_id = User::insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'role' => $request->role
            ]);
            UserRoleMapping::insert([
                'user_id' => $user_id,
                'role_id' => $request->role
            ]);

            return redirect('/useraccess/user/list')->with('success', 'User created successfully.');
        }
    }
    public function edit( User $user){
        $moduleAction = new ModuleAction();
        $allRoutes = $moduleAction->getAllActions();
        $allroles = Roles::all()->toArray();
        $roleActions = $userRightActions = [];
        $roles = new Roles();
        $role_id = UserRoleMapping::where('user_id',$user->id)->pluck('role_id')->toArray();
        if($role_id && count($role_id) > 0){
            $role_id = $role_id[0];
            $roleActions = $roles->getRoleActions($role_id);

            $userRoleMapping = new UserRoleMapping();
            $userRightActions = $userRoleMapping->getUserRightActions($user->id, $role_id) ;
        }
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.user.edit' : 'useraccess::bootstrap.user.edit';
        return view($frontendView, compact( 'user','allRoutes','allroles','roleActions','userRightActions','role_id' ));
    }
    public function update( Request $request,User $user){
        // p($request->all());
        $rightAction = new RightAction();
        $rightAction->deleteRightActions($user->id ,$request['role']);

        $actions = $request['selNodes'] ?? [];
        if(count($actions) > 0){
            $insertactions = [];
            foreach ($actions as $key => $action) {
                $insertactions[] = [
                    'user_id' => $user->id,'role_id' => $request['role'], 'action_id' => $action
                ];
            }
            RightAction::insert($insertactions);
        }
        $existingMenu = UserRoleMapping::where('user_id', $user->id)
            ->where('role_id', $request['role'])
            ->first();

        if (!$existingMenu) {
            UserRoleMapping::where('user_id', $user->id)->delete();
            UserRoleMapping::insert([
                'user_id' => $user->id,
                'role_id' => $request['role']
            ]);
            User::where('id',$user->id)->update(['updated_at' => now()]);
        }
        return redirect('/useraccess/user/edit/'.$user->id)->with('success', 'User updated successfully.');

    }
    public function delete(User $user){
        $user->delete();
        return redirect('/useraccess/user/list')->with('success', 'User deleted successfully.');
    }
}
