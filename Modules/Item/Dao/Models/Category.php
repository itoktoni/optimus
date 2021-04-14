<?php

namespace Modules\Item\Dao\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'item_category';
    protected $primaryKey = 'item_category_id';

    protected $fillable = [
        'item_category_id',
        'item_category_name',
        'item_category_slug',
        'item_category_description',
        'item_category_created_at',
        'item_category_created_by',
        'item_category_image',
        'item_category_status',
    ];

    // public $with = ['module'];

    public $timestamps = true;
    public $incrementing = true;
    public $rules = [
        'item_category_name' => 'required|min:3',
    ];

    const CREATED_AT = 'item_category_created_at';
    const UPDATED_AT = 'item_category_updated_at';

    public $searching = 'item_category_name';
    public $datatable = [
        'item_category_id' => [false => 'Code', 'width' => 50],
        'item_category_name' => [true => 'Name'],
        'item_category_status' => [true => 'Status', 'width' => 100,'class' => 'text-center', 'status' => 'status'],
    ];
    
    public $status    = [
        '1' => ['Enable', 'info'],
        '0' => ['Disable', 'default'],
    ];
}
