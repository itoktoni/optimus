<?php

namespace Modules\Linen\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Linen\Dao\Models\Grouping;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Http\Requests\GeneralRequest;

class KotorRequest extends GeneralRequest
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
        $key = $this->linen_kotor_key;
        $session = $this->linen_kotor_session;
        $company = CompanyFacades::find($this->linen_kotor_company_id);
        $linen = LinenFacades::dataRepository()->whereIn('item_linen_rfid', $this->rfid)->with([
            'company', 'location', 'product'
        ])->get();
        
        $stock = $linen->mapToGroups(function($items){
            return [$items->item_linen_product_id => $items];
        });

        if ($linen) {
            $linen = $linen->mapWithKeys(function ($data_linen) {
                return [$data_linen['item_linen_rfid'] => $data_linen];
            });
        }

        $kotor = $linen->map(function ($item) use($company, $key) {
            $user = auth()->user();

            $description = 1;
            if($company->company_id != $item->item_linen_company_id){

                $description = 2;
            }

            $data = [

                'linen_kotor_detail_rfid' => $item->item_linen_rfid,
                'linen_kotor_detail_product_id' => $item->product->item_product_id ?? '',
                'linen_kotor_detail_product_name' => $item->product->item_product_name ?? '',
                'linen_kotor_detail_ori_company_id' => $item->company->company_id ?? '',
                'linen_kotor_detail_ori_company_name' => $item->company->company_name ?? '',
                'linen_kotor_detail_ori_location_id' => $item->location->location_id ?? '',
                'linen_kotor_detail_ori_location_name' => $item->location->location_name ?? '', 
                'linen_kotor_detail_scan_company_id' => $company->company_id ?? '',
                'linen_kotor_detail_scan_company_name' => $company->company_name ?? '',
                'linen_kotor_detail_created_at' => date('Y-m-d H:i:s') ?? '',
                'linen_kotor_detail_created_by' => $user->id ?? '',
                'linen_kotor_detail_created_name' => $user->name ?? '',
                'linen_kotor_detail_key' => $key ?? '',
                'linen_kotor_detail_session' => $session ?? '',
                'linen_kotor_detail_status' => 1 ?? '',
                'linen_kotor_detail_description' => $description,
            ];

            return $data;

        })->toArray();

        $outstanding = $linen->map(function ($item) use($company, $key) {

            $user = auth()->user();

            $description = 1;
            if($company->company_id != $item->item_linen_company_id){

                $description = 2;
            }

            $data = [

                'linen_outstanding_rfid' => $item->item_linen_rfid,
                'linen_outstanding_product_id' => $item->product->item_product_id ?? '',
                'linen_outstanding_product_name' => $item->product->item_product_name ?? '',
                'linen_outstanding_ori_company_id' => $item->company->company_id ?? '',
                'linen_outstanding_ori_company_name' => $item->company->company_name ?? '',
                'linen_outstanding_ori_location_id' => $item->location->location_id ?? '',
                'linen_outstanding_ori_location_name' => $item->location->location_name ?? '', 
                'linen_outstanding_scan_company_id' => $company->company_id ?? '',
                'linen_outstanding_scan_company_name' => $company->company_name ?? '',
                'linen_outstanding_created_at' => date('Y-m-d H:i:s') ?? '',
                'linen_outstanding_created_by' => $user->id ?? '',
                'linen_outstanding_created_name' => $user->name ?? '',
                'linen_outstanding_key' => $key ?? '',
                'linen_outstanding_session' => $session ?? '',
                'linen_outstanding_status' => 1 ?? '',
                'linen_outstanding_description' => $description,
            ];

            return $data;

        })->toArray();

        $this->merge([  
            'kotor' => $kotor,
            'stock' => $stock,
            'outstanding' => $outstanding,
            'linen_kotor_company_name' => $company->company_name ?? '',
            'linen_kotor_status' => 1,
            'linen_kotor_total' => count($kotor),
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
            'linen_kotor_key' => 'required|unique:linen_kotor',
            'linen_kotor_company_id' => 'required|exists:system_company,company_id',
            'rfid.*' => 'required|exists:item_linen,item_linen_rfid|unique:linen_outstanding,linen_outstanding_rfid',
        ];
    }
}
