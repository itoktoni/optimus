<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


if (Cache::has('routing')) {
    $cache_query = Cache::get('routing')->where('system_action_api', 1);
    Route::middleware(['auth:sanctum'])->group(function () use ($cache_query) {
        foreach ($cache_query as $route) {
            $path = $route->system_action_path . '@' . $route->system_action_function;
            $code = $route->system_action_module.'/'.$route->system_action_function;
            
            if(in_array($route->system_action_function, ['get', 'update'])){
                $code = $code.'/{code}';
            }
            else if($route->system_action_function == 'save'){
                $code = $route->system_action_module.'/create';
            }
            Route::{$route->system_action_method}($code, $path)->name($route->system_action_code . '_api');
        }
    });
}

Route::post('login', [TeamController::class, 'login']);
