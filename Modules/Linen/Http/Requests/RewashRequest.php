<?php

namespace Modules\Linen\Http\Requests;

use Modules\Item\Dao\Facades\LinenFacades;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Http\Requests\GeneralRequest;

class RewashRequest extends GeneralRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    private $rules;
    private $model;

    public function prepareForValidation()
    {
        $company = CompanyFacades::find($this->linen_rewash_company_id);
        $linen = LinenFacades::dataRepository()->whereIn('item_linen_rfid', $this->rfid)->with([
            'company', 'location', 'product'
        ])->get();

        if ($linen) {
            $linen = $linen->mapWithKeys(function ($data_linen) {
                return [$data_linen['item_linen_rfid'] => $data_linen];
            });
        }

        $stock = $linen->mapToGroups(function($items){
            return [$items->item_linen_product_id => $items];
        });

        $validate = $linen->map(function ($item) use($company) {

            $user = auth()->user();
            $data = [

                'linen_rewash_detail_rfid' => $item->item_linen_rfid,
                'linen_rewash_detail_product_id' => $item->product->item_product_id ?? '',
                'linen_rewash_detail_product_name' => $item->product->item_product_name ?? '',
                'linen_rewash_detail_key' => $this->linen_rewash_key,
                'linen_rewash_detail_ori_company_id' => $item->company->company_id ?? '',
                'linen_rewash_detail_ori_company_name' => $item->company->company_name ?? '',
                'linen_rewash_detail_ori_location_id' => $item->location->location_id ?? '',
                'linen_rewash_detail_ori_location_name' => $item->location->location_name ?? '', 
                'linen_rewash_detail_scan_company_id' => $company->company_id ?? '',
                'linen_rewash_detail_scan_company_name' => $company->company_name ?? '',
                'linen_rewash_detail_created_at' => date('Y-m-d H:i:s') ?? '',
                'linen_rewash_detail_created_by' => $user->id ?? '',
                'linen_rewash_detail_created_name' => $user->name ?? '',
            ];

            return $data;

        })->toArray();

        $this->merge([  
            'detail' => $validate,
            'stock' => $stock,
            'linen_rewash_company_name' => $company->company_name ?? '',
            'linen_rewash_total' => count($validate),
        ]);

    }

    public function withValidator($validator)
    {
        // $validator->after(function ($validator) {
        //     $validator->errors()->add('system_action_code', 'The title cannot contain bad words!');
        // });
    }

    public function rules()
    {
        return [
            'linen_rewash_key' => 'required|unique:linen_rewash',
            'linen_rewash_company_id' => 'required|exists:system_company,company_id',
            'linen_rewash_status' => 'required|in:7,8',
            'rfid.*' => 'required',
        ];
    }
}
