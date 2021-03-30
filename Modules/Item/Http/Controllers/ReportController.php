<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Item\Dao\Repositories\LinenRepository;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Item\Dao\Repositories\ReportLinenRegisterRepository;
use Modules\System\Dao\Repositories\CompanyRepository;
use Modules\System\Dao\Repositories\LocationRepository;
use Modules\System\Dao\Repositories\TeamRepository;
use Modules\System\Http\Requests\GeneralRequest;
use Modules\System\Http\Services\CreateService;
use Modules\System\Http\Services\ExcelService;
use Modules\System\Http\Services\ReportService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class ReportController extends Controller
{
    public static $template;
    public static $service;
    public static $model;

    public function __construct(ReportLinenRegisterRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        $product = Views::option(new ProductRepository());
        $location = Views::option(new LocationRepository());
        $company = Views::option(new CompanyRepository());
        $user = Views::option(new TeamRepository());
        $status = Views::status(self::$model->status);
        $rent = Views::status(self::$model->rent);

        $view = [
            'product' => $product,
            'location' => $location,
            'user' => $user,
            'company' => $company,
            'status' => $status,
            'rental' => $rent,
        ];

        return array_merge($view, $data);
    }

    public function create()
    {
        return view(Views::create(config('page'), config('folder')))->with($this->share());
    }

    public function save(Request $request, ReportService $service)
    {
        return $service->generate(self::$model, $request);
    }
}
