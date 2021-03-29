<?php

namespace Modules\System\Http\Services;

use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use Modules\System\Http\Services\DataService;
use Yajra\DataTables\Facades\DataTables;

class CompanyDataService extends DataService
{
    public function make()
    {
        $this->setFilter();

        if (!request()->ajax()) {

            $pagination = request()->get('page') ? $this->filter->paginate(request()->get('limit') ?? config('website.pagination')) : $this->filter->get();
            return new CompanyCollection($pagination);
        }
        
        $this->datatable = Datatables::of($this->filter);
        $this->setAction();
        $this->setStatus();
        $this->setImage();
        $this->datatable->rawColumns($this->column);
        return $this->datatable->make(true);
    }
}
