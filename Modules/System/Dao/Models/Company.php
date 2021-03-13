<?php

namespace Modules\System\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\System\Plugins\Helper;

class Company extends Model
{
    protected $table = 'system_company';
    protected $primaryKey = 'company_id';

    protected $fillable = [
        'company_id',
        'company_name',
        'company_description',
        'company_address',
        'company_email',
        'company_phone',
        'company_person',
        'company_logo',
        'company_sign',
        'company_holding_id'
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = false;
    public $rules = [
        'company_name' => 'required|min:3',
    ];

    const CREATED_AT = 'company_created_at';
    const UPDATED_AT = 'company_created_by';

    public $searching = 'company_name';
    public $datatable = [
        'company_id' => [false => 'Code'],
        'company_name' => [true => 'Company Name'],
        'company_person' => [true => 'Contact Person'],
        'company_email' => [true => 'Email'],
        'company_phone' => [true => 'Phone'],
    ];
    
    public $show    = [
        '1' => ['Show', 'info'],
        '0' => ['Hide', 'warning'],
    ];

    public static function boot()
    {
        parent::saving(function ($model) {
            $file_name = 'file';
            if (request()->has($file_name)) {
                // $image = $model->company_logo;
                // if (file_exists(public_path().'files/company/'.$image)) {
                //     Helper::removeImage($image, Helper::getTemplate(__CLASS__));
                // }
                
                $file = request()->file($file_name);
                $name = Helper::uploadImage($file, Helper::getTemplate(__CLASS__));
                $model->company_logo = $name;
            }
        });

        parent::boot();
    }
}

