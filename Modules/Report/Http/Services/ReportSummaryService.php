<?php

namespace Modules\Report\Http\Services;

use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\System\Plugins\Views;

class ReportSummaryService
{
    public $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function generate($repository, $data)
    {
        if($data->action == 'excel'){
            
            $name = 'report_register_linen_' . date('Y_m_d') . '.xlsx';
            return $this->excel->download($repository, $name);
        }
        else if($data->action == 'pdf'){
            $pdf = PDF::loadView(Views::pdf(config('page'), config('folder')), $repository)->setPaper('A4', 'potrait');
            return $pdf->download();
            // return $pdf->stream();
        }
    } 
}
