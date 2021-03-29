<?php

namespace Modules\System\Http\Services;

use Yajra\DataTables\Facades\DataTables;
use Modules\System\Http\Services\DataService;

class LinenDataService extends DataService
{
    public function make()
    {
        $this->setFilter();
        $this->datatable = Datatables::of($this->filter);

        if (!request()->ajax()) {
            
            return $this->datatable;
        }

        $this->setAction();
        $this->setStatus();
        $this->setImage();
        $this->datatable->rawColumns($this->column);
        return $this->datatable->make(true);
    }
}
