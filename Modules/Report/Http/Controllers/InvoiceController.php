<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Item\Dao\Repositories\ProductRepository;
use Modules\Linen\Dao\Repositories\DeliveryRepository;
use Modules\Report\Dao\Repositories\ReportInvoiceRumahSakitRepository;
use Modules\Report\Dao\Repositories\ReportLinenRegisterRepository;
use Modules\Report\Dao\Repositories\ReportLinenSummaryRepository;
use Modules\Report\Http\Services\ReportSummaryService;
use Modules\System\Dao\Repositories\CompanyRepository;
use Modules\System\Dao\Repositories\LocationRepository;
use Modules\System\Dao\Repositories\TeamRepository;
use Modules\System\Http\Services\PreviewService;
use Modules\System\Http\Services\ReportService;
use Modules\System\Http\Services\SingleService;
use Modules\System\Plugins\Views;

class InvoiceController extends Controller
{
    public static $template;
    public static $service;
    public static $model;
    public static $summary;

    public function __construct(ReportInvoiceRumahSakitRepository $model, SingleService $service)
    {
        self::$model = self::$model ?? $model;
        self::$service = self::$service ?? $service;
    }

    private function share($data = [])
    {
        $company = Views::option(new CompanyRepository());
        $delivery = Views::option(new DeliveryRepository());

        $view = [
           
            'company' => $company,
            'delivery' => $delivery,
        ];

        return array_merge($view, $data);
    }

    public function rumahSakit(Request $request, PreviewService $service)
    {
        $linen = LinenFacades::dataRepository();
        $preview = $service->data($linen, $request);
        return view(Views::form(__FUNCTION__,config('page'), config('folder')))->with($this->share([
            'preview' => $preview,
            'model' => $linen->getModel(),
        ]));
    }

    public function rumahSakitExport(Request $request, ReportService $service)
    {
        if ($request->get('action') == 'preview') {
            $data = $request->except('_token');
            return redirect()->route('report_rumah_sakit', $data)->withInput();
        }
        return $service->generate(self::$model, $request, 'excel_report_invoice');
    }
}
