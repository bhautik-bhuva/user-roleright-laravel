<?php

use Illuminate\Support\Facades\Route;

$connection = env('DB_CONNECTION');
$dbConn = \DB::connection($connection);

$dataAdmin = $dbConn->table('module_action')->where('status',1)->whereIn("menu_type",["Admin","Admin Backend"])->get()->toArray();

foreach ($dataAdmin as $key => $value) {
    $controller = app()->make($value['controller']);
    $method = $value['method']!=''?$value['method']:'index';
    $request_method = explode(",",$value['route_type']);
    $filter = json_decode($value['extra_options'],1);
    if (count($request_method) > 1) {
        Route::match($request_method,$value['action'], [$controller, $method])->middleware($filter);
    }else{
        $request_method = $request_method[0];
        Route::$request_method($value['action'], [$controller, $method])->middleware($filter);
    }
}
unset($dataAdmin,$value,$key);

// Under Maintenance
// $dataMaintenance = $dbConn->table('module_action')->where('status',2)->get()->toArray();
// foreach ($dataMaintenance as $key => $value) {
// 	$request_method = explode(",",$value['route_type']);
// 	$filter = json_decode($value['extra_options'],1);
// 	if (count($request_method) > 1) {
// 		Route::match($request_method,$value['action'], [CommanController::class, 'set503'])->middleware($filter);
// 	}else{
// 		$request_method = $request_method[0];
// 		Route::$request_method($value['action'], [CommanController::class, 'set503'])->middleware($filter);
// 	}
// }
// unset($dataMaintenance,$value,$key);
