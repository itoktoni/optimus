<?php

namespace Modules\Linen\Http\Requests;

use Modules\Item\Dao\Facades\LinenFacades;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Http\Requests\GeneralRequest;

class OutstandingBatchRequest extends GeneralRequest
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
        if (!request()->has('update')) {

            $company = CompanyFacades::find($this->linen_outstanding_scan_company_id);
            $location = LocationFacades::find($this->linen_outstanding_scan_location_id);
            $session = $this->linen_outstanding_session;
            $status = $this->linen_outstanding_status;

            $linen = LinenFacades::dataRepository()->whereIn('item_linen_rfid', $this->data)->get();
            if ($linen) {
                $linen = $linen->mapWithKeys(function ($data_linen) {
                    return [$data_linen['item_linen_rfid'] => $data_linen];
                });
            }

            $validate = collect($this->data)->mapWithKeys(function ($item) use ($linen, $company, $location, $session, $status) {

                $user_id = auth()->user()->id;
                $data = [
                    'linen_outstanding_rfid' => $item,
                    'linen_outstanding_status' => $status,
                    'linen_outstanding_created_at' => now(),
                    'linen_outstanding_updated_at' => now(),
                    'linen_outstanding_updated_by' => $user_id,
                    'linen_outstanding_created_by' => $user_id,
                    'linen_outstanding_session' => $session,
                    'linen_outstanding_scan_location_id' => $location->location_id ?? '',
                    'linen_outstanding_scan_location_name' => $location->location_name ?? 'Beda Location',
                    'linen_outstanding_scan_company_id' => $company->company_id ?? '',
                    'linen_outstanding_scan_company_name' => $company->company_name ?? 'Beda Rumah Sakit',
                ];

                if (isset($linen[$item])) {

                    $slinen = $linen[$item];

                    if ($slinen->company_id != $company->company_id) {
                        $data = array_merge($data, [
                            'linen_outstanding_description' => 2,
                        ]);
                    } else if ($slinen->location_id != $location->location_id) {
                        $data = array_merge($data, [
                            'linen_outstanding_description' => 3,
                        ]);
                    } else {
                        $data = array_merge($data, [
                            'linen_outstanding_description' => 1,
                        ]);
                    }
                    $data = array_merge($data, [
                        'linen_outstanding_product_id' => $slinen->item_linen_product_id ?? '',
                        'linen_outstanding_product_name' => $slinen->product->item_product_name ?? '',
                        'linen_outstanding_ori_location_id' => $slinen->location_id,
                        'linen_outstanding_ori_location_name' => $slinen->location_name ?? '',
                        'linen_outstanding_ori_company_id' => $slinen->company_id ?? '',
                        'linen_outstanding_ori_company_name' => $slinen->company_name ?? '',
                    ]);

                } else {
                    $data = array_merge($data, [
                        'linen_outstanding_description' => 2,
                    ]);
                }

                return [$item => $data];
            })->toArray();

            $this->merge([
                'detail' => $validate ?? [],
            ]);

        }

    }

    public function withValidator($validator)
    {
        // $validator->after(function ($validator) {
        //     $validator->errors()->add('system_action_code', 'The title cannot contain bad words!');
        // });
    }

    public function rules()
    {
        if (request()->has('update')) {

            return [
                'linen_outstanding_status' => 'required|in:1,2,3',
                'data.*' => 'required|exists:item_linen,item_linen_rfid',
            ];
        } else {

            return [
                'linen_outstanding_scan_company_id' => 'required|exists:system_company,company_id',
                'linen_outstanding_scan_location_id' => 'required|exists:system_location,location_id',
                'detail.*.linen_outstanding_rfid' => 'required|unique:linen_outstanding,linen_outstanding_rfid',
                'linen_outstanding_session' => 'required',
            ];
        }
    }
}
