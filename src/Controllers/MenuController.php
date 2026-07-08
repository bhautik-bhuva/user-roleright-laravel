<?php

namespace Techaxion\UserAccess\Controllers;
use App\Http\Controllers\Controller;
use Techaxion\UserAccess\Models\ModuleAction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;
use Techaxion\UserAccess\Models\AccessFor;
use Illuminate\Support\Facades\Auth;
use Techaxion\UserAccess\Models\UserRoleMapping;
use Techaxion\UserAccess\Models\Roles;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Techaxion\UserAccess\Models\RolesAction;

class MenuController extends Controller{
    private $useraccessData;
    public function __construct(){ 
        $this->useraccessData = USERACCESS_CONTENT;
    }
    public function list(){
        $menus = ModuleAction::all()->filter(function ($item) {
            return !Str::contains($item->controller, 'Techaxion\\UserAccess') ;
        })->map(function ($menu) {
            $accessIds = explode(',', $menu->menu_type);
            $menu->access_types = AccessFor::whereIn('id', $accessIds)->pluck('name')->toArray();
            return $menu;
        });

        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.menu.index' : 'useraccess::bootstrap.menu.index';
        return view($frontendView, compact('menus') );
    }
    public function create(Request $request){
        $data['controllers'] = $this->controllersName();
		$data['menuOrder'] = ModuleAction::where('menu_status','1')->orderBy('menu_sequence', 'ASC')->get()->toArray();
        $data['accessFor'] = AccessFor::all()->toArray();
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.menu.create' : 'useraccess::bootstrap.menu.create';
        return view($frontendView, compact('data'));
    }
    public function store(Request $request){
        $inputData = [
            'name' => $request->name,
            'controller' => $request->controller,
            'method' => $request->method,
            'action' => $request->action,
            'route_name' => $request->route_name,
            'prefix' => $request->prefix,
            'route_type' => isset($request->route_type) ? implode(",",$request->route_type) : '',
            'menu_type' => $request->menu_type,
            'menu_label' => $request->menu_label,
            'menu_status' => $request->menu_status,
            'menu_sequence' => $request->menu_sequence,
            'menu_order' => $request->menu_order,
            'menu_icon' => $request->menu_icon,
            'module_label' => $request->module_label,
            // 'extra_options' => isset($request->extra_options) ? json_encode(["filters"=> implode(",",$request->extra_options) ], true) : "",
            'status' => $request->status
        ];
        $validator = Validator::make( $inputData, [
            'name' => 'required',
            'controller' => 'required',
            'method' => 'required',
            'action' => 'required',
            'route_name' => 'required',
            'prefix' => 'nullable|regex:/^[a-zA-Z0-9-_]+$/|',
            'route_type' => 'required',
            'menu_type' => 'required',
            'menu_label' => 'required',
            'menu_status' => 'required',
            'menu_sequence' =>'required|numeric',
            'menu_order' =>'required|numeric',
            'menu_icon'   => 'required',
            'module_label' => 'required',
            'status' => 'required',
            // 'extra_options' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/useraccess/menu/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            // Check if the menu already exists
            $existingMenu = ModuleAction::where('controller', $request->controller)
                ->where('method', $request->method)
                ->first();

            if ($existingMenu) {
                return redirect('/useraccess/menu/create')
                    ->with('route_error','The route with selected controller and method is already exists.' )
                    ->withInput();
            }

            $route_name = isset($request->route_name) ?  $request->route_name : '';
            $prefix = isset($request->prefix) ?  $request->prefix : '';
            $filters = isset($request->extra_options) ? implode(",",$request->extra_options) : "";

            $extra_option = ["filters" => $filters,"route_name" => $route_name,"prefix" => $prefix] ;
            $requestData = [
                'name' => $request->name,
                'controller' => $request->controller,
                'method' => $request->method,
                'action' => $request->action,
                'route_type' => isset($request->route_type) ? implode(",",$request->route_type) : '',
                'menu_type' => $request->menu_type,
                'menu_label' => $request->menu_label,
                'menu_status' => $request->menu_status,
                'menu_sequence' => $request->menu_sequence,
                'menu_order' => $request->menu_order,
                'menu_icon' => $request->menu_icon,
                'module_label' => $request->module_label,
                'extra_options' => json_encode($extra_option, true) ,
                'status' => $request->status
            ];
            $requestData['menu_type'] = implode(",", $request->menu_type);

            ModuleAction::create($requestData);
            return redirect('/useraccess/menu/list')->with('success', 'Menu created successfully.');
        }
    }
    public function edit( ModuleAction $moduleAction){
        $data['controllers'] = $this->controllersName();
        $data['menuOrder'] = ModuleAction::where('menu_status','1')->orderBy('menu_sequence', 'ASC')->get()->toArray();
        $class = $moduleAction->controller;
		// $classObj = new $class();
        $reflectionClass = new \ReflectionClass($class);

		$Ignore = array("__construct","__destruct",'middleware', 'getMiddleware', 'callAction', '__call', 'authorize', 'authorizeForUser','parseAbilityAndArguments','normalizeGuessedAbilityName','authorizeResource','resourceAbilityMap','resourceMethodsWithoutModels','validateWith','validate','validateWithBag','getValidationFactory');
		// $methods = array_diff(get_class_methods($classObj), $Ignore);
        $methods = array_diff(
            array_map(fn($m) => $m->getName(), $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE)),
            $Ignore
        );
		$methodWithNumArgus = array();
		foreach ($methods as $key => $value) {
			// $reflection = new \ReflectionMethod($class, $value);
            $reflection = $reflectionClass->getMethod($value);
			$parameters = $reflection->getParameters();

			$methodWithNumArgus[$key][$value]['num'] = count($parameters);
			foreach ($parameters as $parameter) {
				$parameterName = $parameter->getName();
				$parameterType = $parameter->getType();

				if ($parameterType !== null) {
					$parameterTypeName = $parameterType->getName();
					$methodWithNumArgus[$key][$value]['argus'][] = '$'.$parameterName;
				} else {
					$methodWithNumArgus[$key][$value]['argus'][] = '$'.$parameterName;
				}
			}
		}
		$edit_methods = array_values($methodWithNumArgus);
        $accessFor = AccessFor::all()->toArray();
        $frontendView = $this->useraccessData['frontend'] == 'tailwind' ? 'useraccess::tailwind.menu.edit' : 'useraccess::bootstrap.menu.edit';
        return view($frontendView, compact('data','moduleAction','edit_methods','accessFor'));
    }
    public function update( Request $request,ModuleAction $moduleAction){

        $inputData = [
            'name' => $request->name,
            'controller' => $request->controller,
            'method' => $request->method,
            'action' => $request->action,
            'route_name' => $request->route_name,
            'prefix' => $request->prefix,
            'route_type' => isset($request->route_type) ? implode(",",$request->route_type) : '',
            'menu_type' => $request->menu_type,
            'menu_label' => $request->menu_label,
            'menu_status' => $request->menu_status,
            'menu_sequence' => $request->menu_sequence,
            'menu_order' => $request->menu_order,
            'menu_icon' => $request->menu_icon,
            'module_label' => $request->module_label,
            // 'extra_options' => isset($request->extra_options) ? json_encode(["filters"=> implode(",",$request->extra_options) ], true) : "",
            'status' => $request->status
        ];
        $validator = Validator::make( $inputData, [
            'name' => 'required',
            'controller' => 'required',
            'method' => 'required',
            'action' => 'required',
            'route_name' => 'required',
            'prefix' => 'nullable|regex:/^[a-zA-Z0-9-_]+$/|',
            'route_type' => 'required',
            'menu_type' => 'required',
            'menu_label' => 'required',
            'menu_status' => 'required',
            'menu_sequence' =>'required|numeric',
            'menu_order' =>'required|numeric',
            'menu_icon'   => 'required',
            'module_label' => 'required',
            'status' => 'required',
            // 'extra_options' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect('/useraccess/menu/edit/'.$moduleAction->id )
                ->withErrors($validator)
                ->withInput();
        }else{
            // Check if the menu already exists
            // p($inputData);

            $existingMenu = ModuleAction::where('controller', $request->controller)
                ->where('method', $request->method)
                ->where('id', '!=', $moduleAction->id)
                ->first();

            if ($existingMenu) {
                return redirect('/useraccess/menu/edit/'.$moduleAction->id)
                    ->with('route_error','The route with selected controller and method is already exists.' )
                    ->withInput();
            }
            $route_name = isset($request->route_name) ?  $request->route_name : '';
            $prefix = isset($request->prefix) ?  $request->prefix : '';
            $filters = isset($request->extra_options) ? implode(",",$request->extra_options) : "";

            $extra_option = ["filters" => $filters,"route_name" => $route_name,"prefix" => $prefix] ;
            $requestData = [
                'name' => $request->name,
                'controller' => $request->controller,
                'method' => $request->method,
                'action' => $request->action,
                'route_type' => isset($request->route_type) ? implode(",",$request->route_type) : '',
                'menu_type' => $request->menu_type,
                'menu_label' => $request->menu_label,
                'menu_status' => $request->menu_status,
                'menu_sequence' => $request->menu_sequence,
                'menu_order' => $request->menu_order,
                'menu_icon' => $request->menu_icon,
                'module_label' => $request->module_label,
                'extra_options' => json_encode($extra_option, true) ,
                'status' => $request->status
            ];
            $requestData['menu_type'] = implode(",", $request->menu_type);

            ModuleAction::where('id',$moduleAction->id)->update($requestData);
            return redirect('/useraccess/menu/edit/'.$moduleAction->id)->with('success', 'Menu updated successfully.');
        }
    }
    public function delete(ModuleAction $moduleAction){
        // p($moduleAction);
        $moduleAction->delete();
        return redirect('/useraccess/menu/list')->with('success', 'Menu deleted successfully.');
    }

    // get all controllers name
    public function controllersName(){
		$Controllers = $this->getDirContents(app_path('Http') );
		$Controllers = array_diff($Controllers, array("BaseController", "Controller",'..', '.'));
		$Controllers = array_map(function($value){ return str_replace(".php","",$value); }, $Controllers);
        $baseNamespaces = ['App\Http\Controllers\Auth', 'App\Http\Controllers'];
        $rescontrollers = [];
        $allControllers = array_values($Controllers);
        foreach ($allControllers as $key => $value) {
            foreach ($baseNamespaces as $ns) {
                $fqcn = $ns . '\\' . $value;
                if (class_exists($fqcn)) {
                    $rescontrollers[$ns. "\\".$value] = $value;
                    break;
                }
            }
        }
		return $rescontrollers;
	}
    public function getDirContents($dir, &$results = array()) {
		$files = scandir($dir);
		foreach ($files as $key => $value) {
			$path = realpath($dir . DIRECTORY_SEPARATOR . $value);
			if (!is_dir($path)) {
				$path = str_replace([app_path('Http').DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR, app_path('Http').DIRECTORY_SEPARATOR.'Controllers', '.php'], "", $path);
				$results[] = ltrim(str_replace(DIRECTORY_SEPARATOR, "\\", $path), "\\");
				$path = str_replace([app_path('Http')."\Controllers\\", app_path('Http')."\Controllers", ".php"], "", $path);
				$results[] = ltrim(str_replace('/',"\\",$path),"\\");
			} else if ($value != "." && $value != "..") {
				$this->getDirContents($path, $results);
			}
		}

		return $results;
	}
    // AJAX : get all methods based on controllersName
    public function methodNames(Request $request){
        $className = $request['controller'];
        $class = $className;
		// $classObj = new $class();
        $reflectionClass = new \ReflectionClass($class);

		$Ignore = array("__construct","__destruct",'middleware', 'getMiddleware', 'callAction', '__call', 'authorize', 'authorizeForUser','parseAbilityAndArguments','normalizeGuessedAbilityName','authorizeResource','resourceAbilityMap','resourceMethodsWithoutModels','validateWith','validate','validateWithBag','getValidationFactory');
		// $methods = array_diff(get_class_methods($classObj), $Ignore);
        $methods = array_diff(
            array_map(fn($m) => $m->getName(), $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE)),
            $Ignore
        );

		$methodWithNumArgus = array();
		foreach ($methods as $key => $value) {
			// $reflection = new \ReflectionMethod($class, $value);
            $reflection = $reflectionClass->getMethod($value);
			$parameters = $reflection->getParameters();

			$methodWithNumArgus[$key][$value]['num'] = count($parameters);
			foreach ($parameters as $parameter) {
				$parameterName = $parameter->getName();
				$parameterType = $parameter->getType();

				if ($parameterType !== null) {
					$parameterTypeName = $parameterType->getName();
					$methodWithNumArgus[$key][$value]['argus'][] = '$'.$parameterName;
				} else {
					$methodWithNumArgus[$key][$value]['argus'][] = '$'.$parameterName;
				}
			}
		}
		$result = array_values($methodWithNumArgus);
		return json_encode($result);
	}
}
