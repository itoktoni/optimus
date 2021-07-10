<?php

namespace Modules\Linen\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Wildside\Userstamps\Userstamps;

class Delivery extends Model
{
    use SoftDeletes, Userstamps;

    protected $table = 'linen_delivery';
    protected $primaryKey = 'linen_delivery_key';

    protected $fillable = [
        'linen_delivery_id',
        'linen_delivery_status',
        'linen_delivery_total',
        'linen_delivery_total_detail',
        'linen_delivery_created_at',
        'linen_delivery_updated_at',
        'linen_delivery_deleted_at',
        'linen_delivery_updated_by',
        'linen_delivery_created_by',
        'linen_delivery_deleted_by',
        'linen_delivery_key',
        'linen_delivery_company_id',
        'linen_delivery_company_name',
        'linen_delivery_driver_id',
        'linen_delivery_driver_name',
    ];

    // public $with = ['module'];

    public $timestamps = true;
    public $incrementing = true;
    public $rules = [
        'linen_delivery_key' => 'required|unique:linen_delivery',
        'linen_delivery_company_id' => 'required|unique:system_company',
        'barcode' => 'required',
    ];

    const CREATED_AT = 'linen_delivery_created_at';
    const UPDATED_AT = 'linen_delivery_updated_at';
    const DELETED_AT = 'linen_delivery_deleted_at';

    const CREATED_BY = 'linen_delivery_created_by';
    const UPDATED_BY = 'linen_delivery_updated_by';
    const DELETED_BY = 'linen_delivery_deleted_by';

    protected $casts = [
        'linen_delivery_created_at' => 'datetime:Y-m-d H:i:s',
        'linen_delivery_updated_at' => 'datetime:Y-m-d H:i:s',
        'linen_delivery_deleted_at' => 'datetime:Y-m-d H:i:s',
        'linen_delivery_status' => 'string',
        'linen_delivery_total' => 'integer',
    ];

    protected $dates = [
        'linen_delivery_created_at',
        'linen_delivery_updated_at',
        'linen_delivery_deleted_at',
    ];

    public $searching = 'linen_delivery_key';
    public $datatable = [
        'linen_delivery_id' => [false => 'Code', 'width' => 50],
        'linen_delivery_key' => [true => 'No. DO'],
        'linen_delivery_company_name' => [true => 'Company'],
        'linen_delivery_total' => [true => 'Total'],
        'linen_delivery_total_detail' => [true => 'Detail'],
        'linen_delivery_created_at' => [true => 'Created At'],
        'name' => [true => 'Created By'],
        'linen_delivery_status' => [false => 'Status', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
    ];

    public $status = [
        '1' => ['Initial', 'success'],
        '2' => ['Completed', 'primary'],
    ];

    public function status(){
        return $this->status;
    }

    public function user(){

		return $this->hasOne(User::class, TeamFacades::getKeyName(), self::CREATED_BY);
    }

    public function grouping(){

		return $this->hasMany(Grouping::class, 'linen_grouping_delivery', 'linen_delivery_key');
    }

    public function detail(){

		return $this->hasMany(GroupingDetail::class, 'linen_grouping_detail_delivery', 'linen_delivery_key');
    }
    
    public static function boot()
    {
        parent::boot();

        parent::saving(function($model){

            $company = $model->linen_delivery_company_id;
            $model->linen_delivery_company_name = CompanyFacades::find($company)->company_name ?? '';
            
        });
    }    
}