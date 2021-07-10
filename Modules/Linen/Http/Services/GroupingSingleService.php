<?php

namespace Modules\Linen\Http\Services;

use Modules\Linen\Dao\Models\Delivery;
use Modules\Linen\Dao\Models\GroupingDetail;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Notes;

class GroupingSingleService
{
    public function get(CrudInterface $repository, $code, $relation = false)
    {
        if($relation == 'summary'){

            $delivery = Delivery::where(['linen_delivery_key' => $code])->with(['detail'])->first();
            $detail = $delivery->detail->groupBy('linen_grouping_detail_scan_location_name');
            
            $data = [];
            foreach($detail as $key => $summary){
                $concat = $summary->groupBy('linen_grouping_detail_product_name');
                $con_data = [];
                foreach($concat as $con_key => $con){
                    $con_data[] = [
                        'product' => $con_key,
                        'total' =>  $con->count()
                    ];
                }

                $data[] = ['room' => $key, 'linen' => $con_data];
            }
            // dd(json_encode($data));
            return Notes::single($data);
        }
        else if($relation == 'detail'){
            $delivery = GroupingDetail::select([
                'linen_grouping_detail_rfid',
                'linen_grouping_detail_delivery',
                'linen_grouping_detail_barcode',
                'linen_grouping_detail_product_id',
                'linen_grouping_detail_product_name',
                'linen_grouping_detail_scan_company_id',
                'linen_grouping_detail_scan_company_name',
                'linen_grouping_detail_scan_location_id',
                'linen_grouping_detail_scan_location_name',
                'linen_grouping_detail_created_by',
                'linen_grouping_detail_created_name',
            ])->where(['linen_grouping_detail_delivery' => $code])->get();
            return Notes::single($delivery->toArray() ?? []);
        }

        if(request()->wantsJson()){
            return Notes::single($repository->singleRepository($code, $relation));
        }
        return $repository->singleRepository($code, $relation);
    }
}
