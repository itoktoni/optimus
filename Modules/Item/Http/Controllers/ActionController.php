<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Item\Dao\Repositories\ActionRepository;
use Modules\Item\Http\Requests\GeneralRequest;
use Modules\Item\Http\Services\CreateService;
use Modules\Item\Http\Services\DataService;
use Modules\Item\Http\Services\DeleteService;
use Modules\Item\Http\Services\SingleService;
use Modules\Item\Http\Services\UpdateService;
use Modules\Item\Plugins\Helper;
use Modules\Item\Plugins\Response;
use Modules\Item\Plugins\Views;

class ActionController extends Controller
{
    public static $template;
    public static $service;
    public static $model;

    public function __construct(ActionRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        $view = [];
        return array_merge($view, $data);
    }

    public function index()
    {
        return view(Views::index())->with([
            'fields' => Helper::listData(self::$model->datatable),
        ]);
    }

    public function create()
    {
        return view(Views::create())->with($this->share());
    }

    public function save(GeneralRequest $request, CreateService $service)
    {
        $data = $service->save(self::$model, $request->all());
        return Response::redirectBack($data);
    }

    public function data(DataService $service)
    {
        return $service
            ->setModel(self::$model)
            ->EditStatus([
                'system_action_show' => self::$model->show,
                'system_action_api' => self::$model->api,
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
        $data = $service->update(self::$model, $request->all(), $code);
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

    public function delete(DeleteService $service)
    {
        $code = request()->get('code');
        $data = $service->delete(self::$model, $code);
        return Response::redirectBack($data);
    }
}
