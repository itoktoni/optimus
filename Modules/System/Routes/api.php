<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\Item\Http\Controllers\LinenController;
use Modules\Linen\Dao\Facades\OutstandingFacades;
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
            $code = $route->system_action_module . '/' . $route->system_action_function;

            if (in_array($route->system_action_function, ['get', 'update'])) {
                $code = $code . '/{code}';
            } else if ($route->system_action_function == 'save') {
                $code = $route->system_action_module . '/create';
            }
            if ($route->system_action_method) {

                Route::{$route->system_action_method}($code, $path)->name($route->system_action_code . '_api');
            }
        }

        Route::post('sync_outstanding_delete', function () {

            $rfid = request()->get('rfid');
            OutstandingFacades::whereIn('linen_outstanding_rfid', $rfid)->update([
                'linen_outstanding_downloaded_at' => date('Y-m-d H:i:s'),
            ]);
        });

        Route::post('sync_outstanding_download', function () {

            $limit = request()->get('limit');

            $outstanding = OutstandingFacades::dataRepository()->select([
                'linen_outstanding_key',
                'linen_outstanding_rfid',
                'linen_outstanding_status',
                'linen_outstanding_created_at',
                'linen_outstanding_updated_at',
                'linen_outstanding_deleted_at',
                'linen_outstanding_updated_by',
                'linen_outstanding_created_name',
                'linen_outstanding_created_by',
                'linen_outstanding_deleted_by',
                'linen_outstanding_session',
                // 'linen_outstanding_scan_location_id',
                // 'linen_outstanding_scan_location_name',
                'linen_outstanding_scan_company_id',
                'linen_outstanding_scan_company_name',
                'linen_outstanding_product_id',
                'linen_outstanding_product_name',
                // 'linen_outstanding_ori_location_id',
                // 'linen_outstanding_ori_location_name',
                'linen_outstanding_ori_company_id',
                'linen_outstanding_ori_company_name',
                'linen_outstanding_description',

            ])->whereNull('linen_outstanding_downloaded_at')->limit($limit)->get();

            $id = $outstanding->pluck('linen_outstanding_rfid');

            return $outstanding->toArray();

        })->name('sync_outstanding_download');

        Route::post('sync_outstanding_update', function () {

            $rfid = request()->get('rfid');
            $check = OutstandingFacades::whereIn('linen_outstanding_rfid', $rfid)->update([
                'linen_outstanding_status' => 2,
            ]);

            return $rfid;

        })->name('sync_outstanding_update');

        Route::post('sync_outstanding_upload', function () {

            $insert = request()->get('insert');
            $collect = collect($insert)->pluck('linen_outstanding_rfid');
            $check = OutstandingFacades::whereIn('linen_outstanding_rfid', $collect);
            if (!empty($check)) {
                $check->delete();
            }

            OutstandingFacades::insert($insert);
            return $insert;

        })->name('sync_outstanding_upload');

    });
}

Route::post('login', [TeamController::class, 'login'])->name('api_login');

Route::match(['POST', 'GET'], '/deploy', function (Request $request) {

    // $githubPayload = $request->getContent();
    // $githubHash = $request->header('X-Hub-Signature');

    // $localToken = config('app.deploy_secret');
    // $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

    // if (hash_equals($githubHash, $localHash)) {
    //     $root_path = base_path();
    // }

    exec('git pull origin master');

    return 'sucess';
});

Route::get('download', [LinenController::class, 'download'])->name('download');
