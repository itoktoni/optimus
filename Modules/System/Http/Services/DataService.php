<?php

namespace Modules\System\Http\Services;

use Illuminate\Support\Facades\Log;
use Modules\System\Plugins\Debug;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Views;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Notes;
use Yajra\DataTables\Facades\DataTables;

class DataService
{
    protected $model;
    protected $filter;
    protected $query;
    protected $searching;
    protected $raw;
    protected $datatable;
    protected $status = null;
    protected $column = ['action', 'checkbox'];

    public function setModel(CrudInterface $repository)
    {
        $this->model = $repository->dataRepository();
        return $this;
    }

    public function EditStatus($data)
    {
        $this->status = $data;
        return $this;
    }

    private function setFilter()
    {
        $this->filter = Helper::filter($this->model);
        $request = request();
        if ($request->has('search')) {
            $code = $request->get('code') ?? null;
            $search = $request->get('search') ?? null;
            $aggregate = $request->get('aggregate') ?? null;
            $search_field = empty($code) ? $this->model->getModel()->searching : $code;
            $aggregation = empty($aggregate) ? 'like' : $aggregate;
            $input = empty($aggregate) ? "%$search%" : "$search";
            $this->filter->where($search_field, $aggregation, $input);
        }
        if ($this->searching) {

            foreach ($this->searching as $key => $value) {
                if (is_array($value)) {
                    $this->filter->where($key, $value[0], $value[1]);
                } else {
                    $this->filter->where($key, $value);
                }
            }
        }

        return $this;
    }

    public function addFilter(array $where)
    {
        $this->searching = $where;
        return $this;
    }

    private function setApi()
    {
        $pagination = request()->get('page') ? $this->filter->paginate(request()->get('limit') ?? config('website.pagination')) : ['total' => $this->filter->count(), 'data' => $this->filter->get()->toArray()];
        return Notes::data($pagination);
    }

    private function setAction()
    {
        $this->datatable->addColumn('checkbox', function($model){
           return view(Views::checkbox())->with(['model' => $model]);
        });
        $this->datatable->addColumn('action', function($model){
           return view(Views::action())->with(['model' => $model]);
        });
    }

    private function setStatus()
    {
        if (!empty($this->status)) {
            foreach ($this->status as $key => $data) {
                $this->datatable->editColumn($key, function ($select) use ($key, $data) {
                    return Helper::createStatus($select->{$key}, $data);
                });
            }

            $this->column = array_merge($this->column, array_keys($this->status));
        }
    }

    public function make()
    {
        $this->setFilter();
        $this->datatable = Datatables::of($this->filter);

        if (!request()->ajax()) {
            return $this->setApi();
        }

        $this->setAction();
        $this->setStatus();
        $this->datatable->rawColumns($this->column);
        return $this->datatable->make(true);

    }
}
