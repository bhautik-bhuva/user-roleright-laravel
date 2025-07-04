<?php

namespace Techaxion\UserAccess\Routes;

use Illuminate\Support\Facades\Route;
use Techaxion\UserAccess\Controllers\RoleController;
use Techaxion\UserAccess\Controllers\RightController;
use Illuminate\Support\Facades\Session;

$connection = env('DB_CONNECTION');
$dbConn = \DB::connection($connection);

$dataAdmin = $dbConn->table('module_action')->where('status',1)->whereIn("menu_type",["Admin","Admin Backend"])->get()->toArray();
$dataAdmin = json_decode(json_encode($dataAdmin), true);

foreach ($dataAdmin as $key => $value) {
    $controller = $value['controller'];
    $method = $value['method']!=''?$value['method']:'index';
    $request_method = explode(",",$value['route_type']);
    $filter = json_decode($value['extra_options'],1)['filters']??'';
    $filter = $filter != '' ? explode(",",str_replace(" ","",$filter)) : [];

    $action = $value['action'];
    $removeAfter = "/{";

    $pos = strpos($action, $removeAfter);
    $result = $action;
    if ($pos !== false) {
        $result = substr($action, 0, $pos);
    }

    if (count($request_method) > 1) {
        Route::match($request_method,$value['action'], [$controller, $method])->middleware($filter)->name(str_replace("/",".",ltrim($result,"/")));
    }else{
        $request_method = $request_method[0];
        Route::$request_method($value['action'], [$controller, $method])->middleware($filter)->name(str_replace("/",".",ltrim($result,"/")));
    }
}

unset($dataAdmin, $value, $key);