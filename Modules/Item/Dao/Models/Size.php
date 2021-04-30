<?php

namespace Modules\Item\Dao\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'item_size';
    protected $primaryKey = 'item_size_code';
    protected $keyType = 'string';

    protected $fillable = [
        'item_size_code',
        'item_size_name',
        'item_size_description',
        'item_size_created_at',
        'item_size_created_by',
    ];

    // public $with = ['module'];

    public $timestamps = true;
    public $incrementing = false;
    public $rules = [
        'item_size_code' => 'required',
        'item_size_name' => 'required|min:3',
    ];

    const CREATED_AT = 'item_size_created_at';
    const UPDATED_AT = 'item_size_updated_at';

    public $searching = 'item_size_name';
    public $datatable = [
        'item_size_code' => [true => 'Code', 'width' => 50],
        'item_size_name' => [true => 'Name','width' => 150],
        'item_size_description' => [true => 'Description'],
    ];
    
    public $status    = [
        '1' => ['Enable', 'info'],
        '0' => ['Disable', 'default'],
    ];
}
