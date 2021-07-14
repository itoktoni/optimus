<?php

namespace Modules\Linen\Http\Requests;

use Modules\Item\Dao\Facades\LinenFacades;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Http\Requests\GeneralRequest;

class GroupingRequest extends GeneralRequest
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
        $company = CompanyFacades::find($this->linen_grouping_company_id);
        $location = LocationFacades::find($this->linen_grouping_location_id);

        $linen = LinenFacades::dataRepository()->whereIn('item_linen_rfid', $this->rfid)->with([
            'company', 'location', 'product'
        ])->get();

        if ($linen) {
            $linen = $linen->mapWithKeys(function ($data_linen) {
                return [$data_linen['item_linen_rfid'] => $data_linen];
            });
        }

        $validate = $linen->map(function ($item) use($company, $location) {

            $user = auth()->user();
            $data = [

                'linen_grouping_detail_rfid' => $item->item_linen_rfid,
                'linen_grouping_detail_product_id' => $item->product->item_product_id ?? '',
                'linen_grouping_detail_product_name' => $item->product->item_product_name ?? '',
                'linen_grouping_detail_barcode' => $this->linen_grouping_barcode,
                'linen_grouping_detail_ori_company_id' => $item->company->company_id ?? '',
                'linen_grouping_detail_ori_company_name' => $item->company->company_name ?? '',
                'linen_grouping_detail_ori_location_id' => $item->location->location_id ?? '',
                'linen_grouping_detail_ori_location_name' => $item->location->location_name ?? '', 
                'linen_grouping_detail_scan_company_id' => $company->company_id ?? '',
                'linen_grouping_detail_scan_company_name' => $company->company_name ?? '',
                'linen_grouping_detail_scan_location_id' => $location->location_id ?? '',
                'linen_grouping_detail_scan_location_name' => $location->location_name ?? '',
                'linen_grouping_detail_created_at' => date('Y-m-d H:i:s') ?? '',
                'linen_grouping_detail_created_by' => $user->id ?? '',
                'linen_grouping_detail_created_name' => $user->name ?? '',
            ];

            return $data;

        })->toArray();

        // dd($validate);

        $this->merge([
            'detail' => $validate,
            'linen_grouping_company_name' => $company->company_name ?? '',
            'linen_grouping_location_name' => $location->location_name ?? '',
            'linen_grouping_total' => count($validate),
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
            'linen_grouping_barcode' => 'required|unique:linen_grouping',
            'linen_grouping_location_id' => 'required|exists:system_location,location_id',
            'linen_grouping_company_id' => 'required|exists:system_company,company_id',
            'rfid.*' => 'required|exists:linen_outstanding,linen_outstanding_rfid',
        ];
    }
}
