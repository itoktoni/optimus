<?php

namespace Modules\Linen\Http\Services;

use Modules\Linen\Dao\Facades\OutstandingFacades;
use Modules\Linen\Http\Resources\LinenCollection;
use Modules\Linen\Http\Resources\OutstandingCollection;
use Yajra\DataTables\Facades\DataTables;
use Modules\System\Http\Services\DataService;

class OpnameDataService extends DataService
{
    public function make()
    {
        $this->setFilter();

        if (!request()->ajax()) {
            
            $pagination = request()->get('linen_opname_key') ? $this->filter->paginate(request()->get('limit') ?? config('website.pagination')) : $this->filter->select([
                'linen_opname_key',
                'linen_opname_company_id',
                'linen_opname_company_name',
            ])->where('linen_opname_status', 1)->whereDate('linen_opname_date', date('Y-m-d'))->get();
            return $pagination;
        }

        $request = request()->all();
        $filter = $this->filter;

        if($key = $request['linen_opname_key']){
            $filter = $filter->where('linen_opname_key', $key);
        }
        if($date = $request['linen_opname_date']){
            $filter = $filter->where('linen_opname_date', $date);
        }
        if($company_ori = $request['linen_opname_company_id']){
            $filter = $filter->where('linen_opname_company_id', $company_ori);
        }
        if($create = $request['linen_opname_petugas_id']){
            $filter = $filter->where('linen_opname_petugas_id', $create);
        }
       

        $this->datatable = Datatables::of($this->filter);
        $this->setAction();
        $this->setStatus();
        $this->setImage();
        $this->datatable->rawColumns($this->column);
        $this->datatable->orderColumn('linen_opname_created_at', '-linen_opname_created_at $1');
        return $this->datatable->make(true);
    }
}
