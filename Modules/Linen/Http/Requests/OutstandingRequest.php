<?php

namespace Modules\Outstanding\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\System\Http\Requests\GeneralRequest;

class OutstandingRequest extends GeneralRequest
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
            'linen_linen_rfid' => 'required',
            'linen_linen_location_id' => 'required|exists:system_location,location_id',
            'linen_linen_product_id' => 'required|exists:linen_product,linen_product_id',
            'linen_linen_rent' => 'required',
        ];
    }
}
