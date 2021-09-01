<?php

namespace Modules\Linen\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Item\Dao\Models\Linen;
use Modules\Linen\Dao\Facades\StockFacades;
use Modules\Linen\Dao\Models\Grouping;
use Modules\Linen\Dao\Models\GroupingDetail;
use Modules\Linen\Dao\Models\Outstanding;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Alert;

class DeliveryCreateService
{
    public function save(CrudInterface $repository, $data)
    {
        $check = false;
        try {
            $check = $repository->saveRepository($data->all());
            $key = $data->linen_delivery_key;
            
            Grouping::whereIn('linen_grouping_barcode', $data->barcode)->update([
                'linen_grouping_delivery' => $key
            ]);
           
            $detail = GroupingDetail::whereIn('linen_grouping_detail_barcode', $data->barcode)
            ->where(function($query) use($data){
                $query->whereIn('linen_grouping_detail_rfid', $data->detail);
            })->update([
                'linen_grouping_detail_delivery' => $key
            ]);

            $linen = LinenFacades::whereIn('item_linen_rfid', $data->detail)->update(['item_linen_counter' => DB::raw('item_linen_counter + 1')]);
            
            if($data->stock){
                foreach($data->stock as $key_stock => $stock){
                    $update_stock = StockFacades::where('linen_stock_company_id', $data->linen_delivery_company_id)
                    ->where('linen_stock_item_product_id', $key_stock)->update([
                        'linen_stock_qty' => DB::raw('linen_stock_qty + '.count($stock))
                    ]);
                }
            }

            Outstanding::whereIn('linen_outstanding_rfid', $data->detail)->delete();

            if(isset($check['status']) && $check['status']){

                Alert::create();
            }
            else{
                $message = env('APP_DEBUG') ? $check['data'] : $check['message'];
                Alert::error($message);
            }
            
        } catch (\Throwable $th) {
            Alert::error($th->getMessage());
            return $th->getMessage();
        }

        return $check;
    } 
}
