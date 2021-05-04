<?php

namespace Modules\Item\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Item\Dao\Repositories\ReportLinenRegisterRepository;
use Modules\System\Dao\Repositories\CompanyRepository;
use Modules\System\Dao\Repositories\LocationRepository;
use Modules\System\Dao\Repositories\TeamRepository;
use Modules\System\Http\Services\PreviewService;
use Modules\System\Http\Services\ReportService;
use Modules\System\Http\Services\SingleService;
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
        $status = Views::status(self::$model->status, true);
        $rent = Views::status(self::$model->rent, true);

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

    public function create(Request $request, PreviewService $service)
    {
        $linen = LinenFacades::dataRepository();
        $preview = $service->data($linen, $request);
        
        return view(Views::create(config('page'), config('folder')))->with($this->share([
            'preview' => $preview,
            'model' => $linen->getModel(),
        ]));
    }

    public function save(Request $request, ReportService $service)
    {
        if ($request->get('action') == 'report') {
            $data = $request->except('_token');
            return redirect()->route('item_report_create', $data)->withInput();
        }
        return $service->generate(self::$model, $request);
    }
}
