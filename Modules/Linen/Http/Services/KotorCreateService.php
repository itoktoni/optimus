<?php

namespace Modules\Linen\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Linen\Dao\Facades\CardFacades;
use Modules\Linen\Dao\Facades\StockFacades;
use Modules\Linen\Dao\Models\KotorDetail;
use Modules\Linen\Dao\Models\Outstanding;
use Modules\System\Dao\Interfaces\CrudInterface;
use Modules\System\Plugins\Alert;
use Modules\System\Plugins\Notes;

class KotorCreateService
{
    public function save(CrudInterface $repository, $data)
    {
        $check = false;
        try {
            
            $check = $repository->saveRepository($data->all());
            KotorDetail::insert($data['kotor']);
            Outstanding::insert($data['outstanding']);
            
            if($data->stock){
                foreach($data->stock as $key_stock => $stock){
                    $update_stock = StockFacades::where('linen_stock_company_id', $data->linen_kotor_company_id)
                    ->where('linen_stock_item_product_id', $key_stock)->update([
                        'linen_stock_qty' => DB::raw('linen_stock_qty - '.count($stock))
                    ]);

                    // CardFacades::create([
                    //     'linen_card_status' => 1,
                    //     'linen_card_company_id' => $this->linen_kotor_company_id,
                    //     'linen_card_item_product_id' => $key_stock,
                    //     'linen_card_stock_company' => 1,
                    //     'linen_card_stock_notes' => 'Tambahan 1 Stock Perusahaan',
                    // ]);
                }
            }

            if(isset($check['status']) && $check['status']){

                Alert::create();
                $check = Notes::create(array_keys($data['outstanding']));
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
