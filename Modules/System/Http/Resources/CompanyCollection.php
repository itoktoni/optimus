<?php

namespace Modules\System\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Linen\Dao\Facades\ReturFacades;
use Modules\Linen\Dao\Facades\ReturnFacades;
use Modules\Linen\Dao\Facades\RewashFacades;

class CompanyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status' => true,
            'code' => 200,
            'name' => 'list',
            'message' => 'Data berhasil di ambil',
            'data' => [
                'total' => $this->collection->count(),
                'rental' => collect(LinenFacades::rent())->map(function($item, $key){
                    return ['id' => strval($key), 'name' => $item[0]];
                })->toArray(),
                'retur' => collect(ReturFacades::status())->map(function($item, $key){
                    return ['id' => strval($key), 'name' => $item[0]];
                })->toArray(),
                'rewash' => collect(RewashFacades::status())->map(function($item, $key){
                    return ['id' => strval($key), 'name' => $item[0]];
                })->toArray(),
                'status' => collect(LinenFacades::status())->map(function($item, $key){
                    return ['id' => strval($key), 'name' => $item[0]];
                })->toArray(),
                'data' => $this->collection,
            ]
        ];
    }
}
