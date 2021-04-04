<?php

namespace Modules\System\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Item\Dao\Facades\LinenFacades;

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
                'data' => $this->collection,
            ]
        ];
    }
}
