<?php

namespace Techaxion\UserAccess\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Techaxion\UserAccess\Models\ModuleAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Techaxion\UserAccess\Models\UserRoleMapping;
use Techaxion\UserAccess\Models\Roles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Techaxion\UserAccess\Models\RolesAction;
use File;
class SettingController extends Controller
{
    private $useraccessData;

    public function __construct(){ 
        $this->useraccessData = USERACCESS_CONTENT;
    }
    public function menujson(){
        // This function is used to get the menu json for the user access
        $user_id = Auth::id();
        $userRoleMapping = new UserRoleMapping();
        $role_id = $userRoleMapping->getUserRole($user_id);
        if(!isset($role_id[0])){
            return response()->json([]);
        }else{
            $role_id = $role_id[0];
        }
        $menu_type = Roles::where('id', $role_id)->pluck('interface_access')->toArray();
        $menu_type = implode(",",$menu_type );
        $menu_list = $this->menuSP("All",$menu_type, $role_id, $user_id, '1,0');
        return response()->json($menu_list);
    }
    public function menuSP($access_type,$menu_type, $role_id, $user_id,$menu_status){
        $menu_list = \DB::select("CALL Menu(?, ?, ?, ?, ?)", [$access_type, $menu_type, $role_id, $user_id,$menu_status]);
        $menu_list = json_decode(json_encode($menu_list), true);
        $menu_list = $this->groupByKey($menu_list, 'menu_sequence');
        return $menu_list;
    }
    public function groupByKey($array, $groupKey){
        $arr = array();
        foreach ($array as $key => $item) {
            $arr[$item[$groupKey]][$key] = $item;
        }
        ksort($arr, SORT_NUMERIC);

        return $arr;
    }
    public function menumigrate(){
        $allroutes = collect(Route::getRoutes())->map(function ($route) {
            $excludeAction = ["Closure"];
            $excludePrefix = ["useraccess/"];
            $excludeModules = ["", "Profile","Register","Login","Password","Verification","Logout","Confirm Password"];
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
            if(!in_array($route->getActionName(), $excludeAction) 
                && !in_array($route->getPrefix(), $excludePrefix) 
                && !in_array($module_label, $excludeModules)
                ){
                return [
                    'uri'        => $route->uri(),                   // e.g. 'users/{id}'
                    'methods'    => $route->methods(),               // e.g. ['GET', 'HEAD']
                    'name'       => $route->getName(),               // e.g. 'users.show'
                    'action'     => $route->getActionName(),         // e.g. 'App\Http\Controllers\UserController@show'
                    'middleware' => $route->gatherMiddleware(),      // e.g. ['web', 'auth']
                    'prefix'     => $route->getPrefix(),             // e.g. 'admin'
                    'domain'     => $route->getDomain(),   // e.g. null or domain name
                    'module'     => $module_label,
                ];
            }
        })->toArray() ;
        $allroutes = array_values(array_filter($allroutes));
        // echo "<pre>"; print_r($allroutes); exit;
        $insertModuleActions = [];
        $maxSequence = ModuleAction::max('menu_sequence') ?? 0;
        $menu_sequence_i = $maxSequence + 1;
        $allroutes_grpby_module = [];

        foreach ($allroutes as $item) {
            $module = $item['module'];
            $allroutes_grpby_module[$module][] = $item;
        }
        foreach ($allroutes_grpby_module as $modulekey => $allroute) {
            foreach ($allroute as $prefixkey => $route) {
                if (strpos($route['action'], '@') !== false) {
                    list($controller, $method) = explode('@', $route['action']);
                } else {
                    $controller = $route['action'];
                    $method = '';
                }
                $prefix = trim($route['prefix'], '/');
                $filters = isset($route['middleware']) ? implode(",", $route['middleware']) : "";
                $extra_option = ["filters" => $filters, "route_name" => $route['name'], "prefix" => $prefix];
                $methods = array_values(array_filter($route['methods'], function ($method){
                    return strtolower($method) !== 'head';
                }));
                $menu_status = in_array('GET',$methods) ? '1' : '0';
                $existModuleActions = ModuleAction::where('controller', ltrim($controller, '\\'))->where('method', $method)->first();
                $name = "";
                if(isset($route['name']) && $route['name'] != ""){
                    $name = ltrim(ucwords(str_replace('.', ' ', $route['name'])));
                }else{
                    $name = ltrim(ucwords(str_replace('/', ' ', $route['uri'])));
                }
                $module_label = $route['module'];
                if(!$existModuleActions){

                    $insertModuleActions[] = [
                        'name' => $name,
                        'controller' => ltrim($controller, '\\'),
                        'method' => $method,
                        'action' => "/" . preg_replace('/^' . preg_quote($prefix, '/') . '\//', '', $route['uri']),
                        'route_type' => count($methods) > 0 ? strtolower(implode(",", $methods)) : '',
                        'menu_type' => "1,2",
                        'menu_label' => ucwords(str_replace('admin', '', str_replace('index', '', str_replace('.', ' ', $route['name']))) ),
                        'menu_status' => $menu_status,
                        'menu_sequence' => $menu_sequence_i,
                        'menu_order' => $prefixkey,
                        'menu_icon' => 'fa fa-tools me-2',
                        'module_label' => $module_label,
                        'extra_options' => json_encode($extra_option, true),
                        'status' => 1
                    ];
                }else{
                    $updateData = [
                        'name' => $name,
                        'action' => "/" . preg_replace('/^' . preg_quote($prefix, '/') . '\//', '', $route['uri']),
                        'route_type' => count($methods) > 0 ? strtolower(implode(",", $methods)) : '',
                        'menu_type' => "1,2",
                        'menu_label' => ucwords(str_replace('admin', '', str_replace('index', '', str_replace('.', ' ', $route['name']))) ),
                        'menu_status' => $menu_status,
                        'menu_sequence' => $menu_sequence_i,
                        'menu_order' => $prefixkey,
                        'menu_icon' => 'fa fa-tools me-2',
                        'module_label' => $module_label,
                        'extra_options' => json_encode($extra_option, true),
                        'status' => 1
                    ];
                    ModuleAction::where('id',$existModuleActions->id)->update($updateData);
                }
            }
            $menu_sequence_i++;
        }
        // die;
        if(count($insertModuleActions) > 0 ){
            ModuleAction::insert($insertModuleActions);
        }

        $allActions = ModuleAction::pluck('id')->toArray();
        $insertrolaactions = [];
        $content = $this->useraccessData;
        $layoutPath = $content['superadmin_userid'];
        $superadmin_userid = $content['superadmin_userid'];
        $userRoleMapping = new UserRoleMapping();
        $role_id = $userRoleMapping->getUserRole($superadmin_userid)[0];

        foreach ($allActions as $actionId) {
            $existUserRoleMapping = RolesAction::where('action_id', $actionId)->where('role_id',$role_id)->first();
            if(!$existUserRoleMapping){
                $insertrolaactions[] = [
                    'role_id' => $role_id,
                    'action_id' => $actionId
                ];
            }
        }
        if(count($insertrolaactions) > 0 ){
            RolesAction::insert($insertrolaactions);
        } 
        
        $pluginPath = dirname(__DIR__, 1). '/configuration.json';  
        $useraccess_content = file_get_contents($pluginPath);
        $useraccess_content = json_decode($useraccess_content, true);
        $useraccess_content['menu_migrated'] = 'yes';
        $useraccess_content = json_encode($useraccess_content);
        File::put($pluginPath, $useraccess_content);
        
        return redirect('/useraccess/setting')->with('success', 'Menu migrated successfully.');
    }
    public function useraccessmenujson(){
        $user_id = Auth::id();
        $userRoleMapping = new UserRoleMapping();
        $role_id = $userRoleMapping->getUserRole($user_id);
        if(!isset($role_id[0])){
            return json_encode([]);
        }else{
            $role_id = $role_id[0];
        }
        $menu_type = Roles::where('id', $role_id)->pluck('interface_access')->toArray();
        $menu_type = implode(",",$menu_type );

        $menu_list = $this->menuSP("All",$menu_type, $role_id, $user_id, "1");
        foreach ($menu_list as $parentKey => &$parentArray) {
            foreach ($parentArray as $childKey => $childArray) {
                if (preg_match('/\{[^}]+\}/', $childArray['action'])) {
                    unset($parentArray[$childKey]);
                }
            }
            if (empty($parentArray)) {
                unset($menu_list[$parentKey]);
            }
        }
        unset($parentArray); // Break reference
        return json_encode($menu_list);
    }
    public function useraccessindex(){
        $content =  USERACCESS_CONTENT;
        $frontendView = $content['frontend'] == 'tailwind' ? 'useraccess::tailwind.index' : 'useraccess::bootstrap.index';
        return view($frontendView, compact('content'));
    }
    public function updatesetting(Request $request){
        $inputData = [
            'user_table' => $request->user_table,
            'layout_path' => $request->layout_path,
            'yield_container' => $request->yield_container
        ];
        $validator = Validator::make( $inputData, [
            'user_table' => 'required',
            'layout_path' => 'required',
            'yield_container' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/useraccess/setting')
                ->withErrors($validator)
                ->withInput();
        }else{
            $content = $this->useraccessData;
            $userId = $content['superadmin_userid'];
            $menu_migrated = $content['menu_migrated'];
            $frontend = $content['frontend'];
           
            $configContent = json_encode([ 
                "layout_path" => base_path("$request->layout_path"),
                "layout_file" => str_replace(".blade.php","",basename("$request->layout_path")),
                "yield_container" => "$request->yield_container",
                "user_table" => "$request->user_table",
                "superadmin_userid" => "$userId",
                "menu_migrated" => "$menu_migrated",
                "frontend" => "$frontend"
            ]); 
            $pluginPath = dirname(__DIR__, 1). '/configuration.json';  
            File::put($pluginPath, $configContent);
            return redirect('/useraccess/setting')->with('success', 'Configuration saved to configuration.json');
        }
        return view('useraccess::index');
    }
}
