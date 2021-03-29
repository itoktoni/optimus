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
    protected $image = null;
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

    public function EditImage($data)
    {
        $this->image = $data;
        return $this;
    }

    protected function setFilter()
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

    protected function setApi()
    {
        $pagination = request()->get('page') ? $this->filter->paginate(request()->get('limit') ?? config('website.pagination')) : ['total' => $this->filter->count(), 'data' => $this->filter->get()->toArray()];
        return Notes::data($pagination);
    }

    protected function setAction()
    {
        $this->datatable->addColumn('checkbox', function($model){
           return view(Views::checkbox())->with(['model' => $model]);
        });
        $this->datatable->addColumn('action', function($model){
           return view(Views::action())->with(['model' => $model]);
        });
    }

    protected function setStatus()
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

    protected function setImage()
    {
        if (!empty($this->image)) {

            foreach ($this->image as $key => $data) {
                $this->datatable->editColumn($key, function ($select) use ($key, $data) {
                    return Helper::createImage($data . '/thumbnail_' . $select->{$key});
                });
            }

            $this->column = array_merge($this->column, array_keys($this->image));
        }
    }

    public function make()
    {
        $this->setFilter();
        
        if (!request()->ajax()) {
            return $this->setApi();
        }
        
        $this->datatable = Datatables::of($this->filter);
        $this->setAction();
        $this->setStatus();
        $this->setImage();
        $this->datatable->rawColumns($this->column);
        return $this->datatable->make(true);

    }
}
