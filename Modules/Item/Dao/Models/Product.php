<?php

namespace Modules\Item\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\System\Plugins\Helper;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'item_product';
    protected $primaryKey = 'item_product_id';

    protected $fillable = [
        'item_product_id',
        'item_product_name',
        'item_product_sku',
        'item_product_image',
        'item_product_status',
        'item_product_weight',
        'item_product_category_id',
        'item_product_description',
        'item_product_updated_at',
        'item_product_created_at',
        'item_product_deleted_at',
        'item_product_updated_by',
        'item_product_created_by',
    ];

    // public $with = ['module'];

    public $timestamps = true;
    public $incrementing = true;
    public $rules = [
        'item_product_name' => 'required|min:3',
    ];

    const CREATED_AT = 'item_product_created_at';
    const UPDATED_AT = 'item_product_updated_at';
    const DELETED_AT = 'item_product_deleted_at';

    public $searching = 'item_product_name';
    public $datatable = [
        'item_product_image' => [true => 'Image', 'width' => 100, 'class' => 'text-center'],
        'item_product_id' => [true => 'Code', 'width' => 50],
        'item_product_sku' => [true => 'SKU', 'width' => 100],
        'item_product_name' => [true => 'Name'],
        'item_product_weight' => [true => 'Weight', 'width' => 70],
        'item_product_status' => [true => 'Status', 'width' => 100,'class' => 'text-center'],
    ];

    protected $casts = [
        'item_product_created_at' => 'datetime:Y-m-d',
        'item_product_updated_at' => 'datetime:Y-m-d',
        'item_product_deleted_at' => 'datetime:Y-m-d',
    ];
    
    public $status    = [
        '1' => ['Enable', 'info'],
        '0' => ['Disable', 'default'],
    ];

    public static function boot()
    {
        parent::saving(function ($model) {
            $file_name = 'file';
            if (request()->has($file_name)) {
                $file = request()->file($file_name);
                $name = Helper::uploadImage($file, Helper::getTemplate(__CLASS__));
                $model->item_product_image = $name;
            }
        });

        parent::boot();
    }
}
