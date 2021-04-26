<?php

namespace Modules\Item\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Item\Dao\Facades\LinenFacades;

class LinenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       $rent = LinenFacades::rent(); 
       $status = LinenFacades::status(); 

       return [
           'item_linen_id' => $this->item_linen_id,
           'item_linen_rfid' => $this->item_linen_rfid,
           'item_product_name' => $this->item_product_name,
           'company_id' => $this->company_id,
           'company_name' => $this->company_name,
           'location_id' => $this->location_id,
           'location_name' => $this->location_name,
           'name' => $this->name,
           'item_linen_rent' => $rent[$this->item_linen_rent][0] ?? '',
           'item_linen_status' => $status[$this->item_linen_status][0] ?? '',
           'item_linen_session' => $this->item_linen_session,
           'item_linen_created_at' => $this->item_linen_created_at->format('Y-m-d H:i:s') ?? null,
       ];
    }
}
