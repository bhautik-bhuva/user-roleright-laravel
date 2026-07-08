<?php

namespace Techaxion\UserAccess\Routes;

use Illuminate\Support\Facades\Route;
use Techaxion\UserAccess\Models\Roles;
use Techaxion\UserAccess\Models\UserRoleMapping;
use Techaxion\UserAccess\Models\AccessFor;
use Illuminate\Support\Facades\DB;
use Techaxion\UserAccess\Controllers\MenuController;
use Illuminate\Routing\RouteCollection;

$connection = env('DB_CONNECTION');
$dbConn = \DB::connection($connection);

$user_id = session('admin_user_id') ?? '';
// dd($user_id);
$roleid = 0;
$menu_typeArray = [];
if ($user_id) {
    $userRoleMapping = new UserRoleMapping();
    $role_id = $userRoleMapping->getUserRole($user_id);
    if($role_id){
        $menu_typeArray = Roles::where('id', $role_id[0])->pluck('access_for')->toArray();
        $roleid = $role_id[0];
    }
}

// p($menu_typeArray);
$dataAdmin = $dbConn->table('module_action')->select('module_action.*')->where('module_action.status',1)
    ->where(function ($query) use ($user_id, $roleid) {
        $query->whereIn('module_action.id', function ($q) use ($roleid) {
            $q->select('action_id')->from('role_action')->where('role_id', $roleid);
        })
        ->orWhereIn('module_action.id', function ($q) use ($user_id, $roleid) {
            $q->select('action_id')->from('right_action')->where('user_id', $user_id)->where('role_id', $roleid);
        });
    })
    ->where(function ($query) use ($menu_typeArray) {
        foreach ($menu_typeArray as $type) {
            $query->orWhereRaw('FIND_IN_SET(?, menu_type)', [$type]);
        }
    })
    ->get()->toArray();

    // dd($dataAdmin->toSql(), $dataAdmin->getBindings());
// var_dump($dataAdmin);die;
$dataAdmin = json_decode(json_encode($dataAdmin), true);
// echo "<pre>";print_r($dataAdmin ); die;
$urlArr = [];
foreach ($dataAdmin as $key => $value) {
    $controller = $value['controller'];
    $method = $value['method']!=''?$value['method']:'index';
    $request_method = explode(",",$value['route_type']);
    $extra_options = json_decode($value['extra_options'],1);
    $filter = $extra_options['filters'] ?? '';
    $filter = $filter != '' ? explode(",",str_replace(" ","",$filter)) : [];
    $route_name = $extra_options['route_name'] ?? '';
    $prefix= $extra_options['prefix'] ?? '';

    $action = $value['action'];
    $removeAfter = "/{";

    $pos = strpos($action, $removeAfter);
    $result = $action;
    if ($pos !== false) {
        $result = substr($action, 0, $pos);
    }
    $result1 = str_replace("/",".",ltrim($result,"/"));
    if (count($request_method) > 1) {
        Route::prefix($prefix)->match($request_method,$value['action'], [$controller, $method])->middleware($filter)->name($route_name);
        $urlArr[] = $route_name;
    }else{
        $request_method = $request_method[0];
        Route::prefix($prefix)->$request_method($value['action'], [$controller, $method])->middleware($filter)->name($route_name);
        $urlArr[] = $route_name;
    }
}
// echo "<pre>urlArr  == ";print_r($urlArr );
//     die;
unset($dataAdmin, $value, $key);

app()->booted(function () use ($urlArr) { 
    $content = USERACCESS_CONTENT;
    $menu_migrated = $content['menu_migrated'];
    if ($menu_migrated == "yes") {
        $routes = Route::getRoutes()->getRoutes();   
        $newCollection = new \Illuminate\Routing\RouteCollection();
        $excludeModules = ["", "Profile","Register","Login","Password","Verification","Logout","Confirm Password"];
        foreach ($routes as $route) {
            $module_label  = "";
            $routenames = [];
            if($route->getName() !== null || $route->getName() != ""){
                $routenames = explode(".", $route->getName());
                if(count($routenames) > 0){
                    if( $routenames[0] == "admin" && count($routenames) > 1){
                        $module_label = str_replace('-', ' ', $routenames[1]);
                    }else{
                        $module_label = str_replace('-', ' ', $routenames[0]);
                    }
                }
            }else{
                $module_label = str_replace('-', ' ', $route->uri());
            }
            $module_label = ltrim(ucwords(str_replace('_', ' ', $module_label)));
            if (in_array($route->getName(), $urlArr) 
                || $route->uri() == "login" 
                || $route->getActionName() == "Closure" 
                || in_array($module_label, $excludeModules)
            ) {
                $newCollection->add($route); // keep only matching
            }
        }
        app('router')->setRoutes($newCollection);
    }

    // $dynamicUris = collect(Route::getRoutes())->map( function ($route) { return $route->uri(); } )->toArray();
    // dd($dynamicUris);
});

// Route::fallback(function () {
//     return view('errors.403');
// })->name("fallback");
