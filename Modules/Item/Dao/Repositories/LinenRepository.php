<?php

namespace Modules\Item\Dao\Repositories;

use Illuminate\Database\QueryException;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Models\Linen;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Notes;

class LinenRepository extends Linen implements CrudInterface
{
    public function dataRepository()
    {
        $list = Helper::dataColumn($this->datatable);
        $query = $this->select($list)
        ->leftJoin(ProductFacades::getTable(), ProductFacades::getKeyName(), 'item_linen_product_id')
        ->leftJoin(LocationFacades::getTable(), LocationFacades::getKeyName(), 'item_linen_location_id')
        ->leftJoin(CompanyFacades::getTable(), CompanyFacades::getKeyName(), 'item_linen_company_id');
        return $query;
    }

    public function saveRepository($request)
    {
        try {
            $activity = $this->create($request);
            return Notes::create($activity);
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }

    public function updateRepository($request, $code)
    {
        try {
            $update = $this->findOrFail($code);
            $update->update($request);
            return Notes::update($update->toArray());
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }

    public function deleteRepository($request)
    {
        try {
            is_array($request) ? $this->Destroy(array_values($request)) : $this->Destroy($request);
            return Notes::delete($request);
        } catch (QueryException $ex) {
            return Notes::error($ex->getMessage());
        }
    }

    public function singleRepository($code, $relation = false)
    {
        if ($relation) {
            return $this->with($relation)->findOrFail($code);
        }
        return $this->findOrFail($code);
    }

}
