<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'company_id' => $this->company_id,
            'company_name' => $this->company_name,
            'locations' => LocationResource::collection($this->locations),
            'products' => ProductResource::collection($this->products)
        ];
    }
}
