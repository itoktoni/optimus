<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Kirschbaum\PowerJoins\PowerJoins;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Item\Dao\Repositories\LinenRepository;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Item\Http\Services\LinenDataService;
use Modules\System\Dao\Repositories\CompanyRepository;
use Modules\System\Dao\Repositories\LocationRepository;
use Modules\System\Http\Requests\DeleteRequest;
use Modules\System\Http\Requests\GeneralRequest;
use Modules\System\Http\Services\CreateService;
use Modules\System\Http\Services\DeleteService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Http\Services\UpdateService;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Notes;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class LinenController extends Controller
{
    use PowerJoins;
    public static $template;
    public static $service;
    public static $model;

    public function __construct(LinenRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        $product = Views::option(new ProductRepository());
        $location = Views::option(new LocationRepository());
        $company = Views::option(new CompanyRepository());
        $status = Views::status(self::$model->status, true);
        $rent = Views::status(self::$model->rent, true);

        $view = [
            'product' => $product,
            'location' => $location,
            'company' => $company,
            'status' => $status,
            'rent' => $rent,
        ];

        return array_merge($view, $data);
    }

    public function index()
    {
        $data_user = LinenFacades::joinRelationship('user')->select('name','id')->distinct()->get();
        $user = [];
        if($data_user){
            $user = $data_user->pluck('name', 'id')->prepend(__('- Select Option -'),'');
        }
        return view(Views::index(config('page'), config('folder')))->with($this->share([
            'fields' => Helper::listData(self::$model->datatable),
            'user' => $user
        ]));
    }

    public function create()
    {
        return view(Views::create())->with($this->share());
    }

    public function save(GeneralRequest $request, CreateService $service)
    {
        $data = $service->save(self::$model, $request);
        return Response::redirectBack($data);
    }

    public function patch(GeneralRequest $request)
    {
        $check = LinenFacades::where('item_linen_rfid', $request->item_linen_rfid)->first();
        if ($check) {

            if ($request->type == 'update') {

                $check->item_linen_location_id = $request->item_linen_location_id;
                $check->item_linen_location_id = $request->item_linen_location_id;
                $check->item_linen_rent = $request->item_linen_rent;
                $check->item_linen_status = $request->item_linen_status;
                $check->item_linen_company_id = $request->item_linen_company_id;
                $check->item_linen_product_id = $request->item_linen_product_id;
                $check->item_linen_session = $request->item_linen_session;
                $check->save();

                return Notes::update($check);
            }

            return Notes::single($check);
        }

        $data = LinenFacades::saveRepository($request->all());
        return Response::redirectBack($data);
    }

    public function data(LinenDataService $service)
    {
        return $service
            ->setModel(self::$model)
            ->EditStatus([
                'item_linen_status' => self::$model->status,
                'item_linen_rent' => self::$model->rent,
            ])->make();
    }

    public function edit($code)
    {
        return view(Views::update())->with($this->share([
            'model' => $this->get($code),
        ]));
    }

    public function update($code, GeneralRequest $request, UpdateService $service)
    {
        $data = $service->update(self::$model, $request, $code);
        return Response::redirectBack($data);
    }

    public function show($code)
    {
        return view(Views::show())->with($this->share([
            'fields' => Helper::listData(self::$model->datatable),
            'model' => $this->get($code),
        ]));
    }

    public function get($code = null, $relation = null)
    {
        $relation = $relation ?? request()->get('relation');
        if ($relation) {
            return self::$service->get(self::$model, $code, $relation);
        }
        return self::$service->get(self::$model, $code);
    }

    public function delete(DeleteRequest $request, DeleteService $service)
    {
        $code = $request->get('code');
        $data = $service->delete(self::$model, $code);
        return Response::redirectBack($data);
    }

    public function download()
    {
        $data = LinenFacades::dataRepository()->whereNull('item_linen_sync_at')->limit(10)->get();
        $json = json_encode($data);
        // $jsongFile = 'data_file.json';
        // File::put(public_path('/files/linen/'.$jsongFile), $json);

        // LinenFacades::whereIn('item_linen_id', $data->pluck('item_linen_id'))->update([
        //     'item_linen_sync_at' => date('Y-m-d')
        // ]);

        // return FacadesResponse::download(public_path('/files/linen/'.$jsongFile));
    }
}
