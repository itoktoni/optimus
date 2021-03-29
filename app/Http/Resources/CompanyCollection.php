<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

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
            'message' => 'Data berhasil di ambil',
            'code' => 200,
            'name' => 'list',
            'status' => true,
            'total' => $this->collection->count(),
            'data' => $this->collection,
        ];
    }
}
