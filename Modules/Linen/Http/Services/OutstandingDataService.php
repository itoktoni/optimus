<?php

namespace Modules\Linen\Http\Services;

use Modules\Linen\Dao\Facades\OutstandingFacades;
use Modules\Linen\Http\Resources\LinenCollection;
use Modules\Linen\Http\Resources\OutstandingCollection;
use Yajra\DataTables\Facades\DataTables;
use Modules\System\Http\Services\DataService;

class OutstandingDataService extends DataService
{
    public function make()
    {
        $this->setFilter();

        if (!request()->ajax()) {

            if(request()->get('status')){
                return [
                    'status' => true,
                    'code' => 200,
                    'name' => 'list',
                    'message' => 'Data berhasil di ambil',
                    'data' => [
                        'description' => collect(OutstandingFacades::description())->map(function($item, $key){
                            return ['id' => strval($key), 'name' => $item[0]];
                        })->toArray(),
                        'status' => collect(OutstandingFacades::status())->map(function($item, $key){
                            return ['id' => strval($key), 'name' => $item[0]];
                        })->toArray(),
                    ]
                ];
            }
            
            $pagination = request()->get('page') ? $this->filter->paginate(request()->get('limit') ?? config('website.pagination')) : $this->filter->get();
            return new OutstandingCollection($pagination);
        }

        $this->datatable = Datatables::of($this->filter);
        $this->setAction();
        $this->setStatus();
        $this->setImage();
        $this->datatable->rawColumns($this->column);
        return $this->datatable->make(true);
    }
}