<?php

namespace Modules\Item\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Models\Location;

class Linen extends Model
{
    use SoftDeletes;
    protected $table = 'item_linen';
    protected $primaryKey = 'item_linen_id';

    protected $fillable = [
        'item_linen_id',
        'item_linen_rfid',
        'item_linen_description',
        'item_linen_status',
        'item_linen_rent',
        'item_linen_location_id',
        'item_linen_product_id',
        'item_linen_created_at',
        'item_linen_updated_at',
        'item_linen_update_by',
        'item_linen_created_by',
        'item_linen_deleted_by',
    ];

    // public $with = ['location', 'product'];

    public $timestamps = true;
    public $incrementing = true;

    public $rules = [
        'item_linen_location_id' => 'required|exists:system_location,location_id',
        'item_linen_product_id' => 'required|exists:item_product,item_product_id',
        'item_linen_rfid' => 'required|unique:item_linen',
    ];

    const CREATED_AT = 'item_linen_created_at';
    const UPDATED_AT = 'item_linen_updated_at';
    const DELETED_AT = 'item_linen_deleted_at';

    public $searching = 'item_linen_rfid';
    public $datatable = [
        'item_linen_id' => [true => 'Code', 'width' => 50],
        'item_linen_rfid' => [true => 'RFID', 'width' => 150],
        'item_linen_product_id' => [false => 'Product Id'],
        'item_product_name' => [true => 'Product Name'],
        'item_linen_location_id' => [false => 'Location Id'],
        'location_name' => [true => 'Location Name'],
        'item_linen_rent' => [true => 'Rental', 'width' => 100, 'class' => 'text-center'],
        'item_linen_status' => [true => 'Status', 'width' => 100, 'class' => 'text-center'],
    ];

    protected $casts = [
        'item_linen_created_at' => 'datetime:Y-m-d',
        'item_linen_updated_at' => 'datetime:Y-m-d',
        'item_linen_deleted_at' => 'datetime:Y-m-d',
    ];
    
    public $status    = [
        '1' => ['Enable', 'info'],
        '0' => ['Disable', 'default'],
    ];

    public $rent    = [
        '1' => ['Enable', 'info'],
        '0' => ['Disable', 'default'],
    ];

	public function product(){

		return $this->hasOne(Product::class, ProductFacades::getKeyName(), 'item_linen_product_id');
    }
    
	public function location(){

		return $this->hasOne(Location::class, LocationFacades::getKeyName(), 'item_linen_location_id');
    }
    
    public static function boot()
    {
        parent::created(function($model){
            $model->item_linen_created_by = auth()->user()->username;
        });

        parent::updated(function($model){
            $model->item_linen_update_by = auth()->user()->username;
        });

        parent::deleted(function($model){
            $model->item_linen_deleted_by = auth()->user()->username;
        });

        parent::boot();
    }

}
