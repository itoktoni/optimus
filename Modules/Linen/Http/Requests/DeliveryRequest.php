<?php

namespace Modules\Linen\Http\Requests;

use App\Models\User;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Linen\Dao\Models\Grouping;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Http\Requests\GeneralRequest;

class DeliveryRequest extends GeneralRequest
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
        $company = CompanyFacades::find($this->linen_delivery_company_id);

        $grouping = Grouping::whereIn('linen_grouping_barcode', $this->barcode)->with([
            'detail'
        ])->get();
        
        $data = [];
        foreach($grouping as $barcode){

            $data = $barcode->detail->pluck('linen_grouping_detail_rfid')->merge($data)->unique()->toArray();
        }

        $driver = User::find($this->linen_delivery_driver_id);

        $this->merge([
            'detail' => $data,
            'linen_delivery_company_name' => $company->company_name ?? '',
            'linen_delivery_driver_name' => $driver->name ?? '',
            'linen_delivery_total' => count($grouping),
            'linen_delivery_total_detail' => count($data),
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
            'linen_delivery_key' => 'required|unique:linen_delivery',
            'linen_delivery_company_id' => 'required|exists:system_company,company_id',
            'barcode.*' => 'required|exists:linen_grouping,linen_grouping_barcode',
        ];
    }
}
