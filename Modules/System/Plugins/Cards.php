<?php

namespace Modules\System\Plugins;

use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Linen\Dao\Facades\CardFacades;
use Modules\System\Dao\Facades\CompanyFacades;

class Cards
{
    public static function stock($company_id, $product_id, $qty)
    {
        $card = CardFacades::where('linen_card_company_id', $company_id)->where('linen_card_item_product_id', $product_id)->orderBy('linen_card_id', 'DESC');
        $single = $card->first();

        if($single){

            $data = [
               'linen_card_item_product_name' => $single->linen_card_item_product_name, 
               'linen_card_company_name' => $single->linen_card_company_name, 
            ];
        }
        else{

            $data = [
                'linen_card_item_product_name' => ProductFacades::find($product_id)->item_product_name ?? '', 
                'linen_card_company_name' => CompanyFacades::find($company_id)->company_name ?? '', 
            ];
        }
        
        $merge = [
            'linen_card_status' => 1,
            'linen_card_stock_company' => 1,
            'linen_card_company_id' => $company_id,
            'linen_card_item_product_id' => $product_id,
            'linen_card_stock_kotor' => $single->linen_card_stock_kotor ?? 0, 
            'linen_card_stock_bersih' => $single->linen_card_stock_bersih ?? 0, 
            'linen_card_stock_pending' => $single->linen_card_stock_pending ?? 0, 
            'linen_card_stock_hilang' => $single->linen_card_stock_hilang ?? 0, 
            'linen_card_stock_saldo' => $qty, 
        ];
        
        CardFacades::create(array_merge($data, $merge));
    }
}
