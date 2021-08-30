<?php

namespace Modules\Linen\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Wildside\Userstamps\Userstamps;

class Stock extends Model
{
    protected $table = 'linen_stock';
    protected $primaryKey = 'linen_stock_id';
    protected $keyType = 'string';

    protected $fillable = [
        'linen_stock_id',
        'linen_stock_company_id',
        'linen_stock_company_name',
        'linen_stock_item_product_id',
        'linen_stock_item_product_name',
        'linen_stock_qty',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = false;
    public $rules = [
        'linen_stock_key' => 'required|unique:linen_stock',
        'linen_stock_company_id' => 'required|unique:system_company',
        'barcode' => 'required',
    ];

    const CREATED_AT = 'linen_stock_created_at';
    const UPDATED_AT = 'linen_stock_updated_at';
    const DELETED_AT = 'linen_stock_deleted_at';

    const CREATED_BY = 'linen_stock_created_by';
    const UPDATED_BY = 'linen_stock_updated_by';
    const DELETED_BY = 'linen_stock_deleted_by';

    protected $casts = [
        'linen_stock_created_at' => 'datetime:Y-m-d H:i:s',
        'linen_stock_updated_at' => 'datetime:Y-m-d H:i:s',
        'linen_stock_deleted_at' => 'datetime:Y-m-d H:i:s',
        'linen_stock_status' => 'string',
        'linen_stock_total' => 'integer',
    ];

    protected $dates = [
        'linen_stock_created_at',
        'linen_stock_updated_at',
        'linen_stock_deleted_at',
    ];

    public $searching = 'linen_stock_key';
    public $datatable = [
        'linen_stock_id' => [false => 'Code', 'width' => 50],
        'linen_stock_key' => [true => 'No. DO'],
        'linen_stock_company_id' => [false => 'Company'],
        'linen_stock_company_name' => [true => 'Company'],
        'linen_stock_total' => [true => 'Total'],
        'linen_stock_total_detail' => [true => 'Detail'],
        'linen_stock_reported_date' => [true => 'Report Date'],
        'linen_stock_created_by' => [false => 'Created At'],
        'linen_stock_created_at' => [true => 'Created At'],
        'name' => [true => 'Created By'],
        'linen_stock_status' => [false => 'Status', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
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

		return $this->hasMany(Grouping::class, 'linen_grouping_stock', 'linen_stock_key');
    }

    public function detail(){

		return $this->hasMany(GroupingDetail::class, 'linen_grouping_detail_stock', 'linen_stock_key');
    }
    
    public static function boot()
    {
        parent::boot();

        parent::saving(function($model){

            $company = $model->linen_stock_company_id;
            $model->linen_stock_company_name = CompanyFacades::find($company)->company_name ?? '';
            
        });
    }    
}
