<?php

namespace Modules\Item\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\System\Http\Requests\GeneralRequest;

class LinenRequest extends GeneralRequest
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
        $this->merge([
            // 'content' => ''
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
            'item_linen_rfid' => 'required',
            'item_linen_location_id' => 'required|exists:system_location,location_id',
            'item_linen_product_id' => 'required|exists:item_product,item_product_id',
            'item_linen_rent' => 'required',
        ];
    }
}
