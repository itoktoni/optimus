<?php

namespace Modules\Linen\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Wildside\Userstamps\Userstamps;

class Card extends Model
{
    protected $table = 'linen_card';
    protected $primaryKey = 'linen_card_id';
    // protected $keyType = 'string';

    protected $fillable = [
        'linen_card_id',
        'linen_card_status',
        'linen_card_company_id',
        'linen_card_company_name',
        'linen_card_item_product_id',
        'linen_card_item_product_name',
        'linen_card_stock_company',
        'linen_card_stock_kotor',
        'linen_card_stock_bersih',
        'linen_card_stock_pending',
        'linen_card_stock_hilang',
        'linen_card_stock_saldo',
        'linen_card_stock_notes',
        'linen_card_created_at',
        'linen_card_updated_at',
        'linen_card_deleted_at',
        'linen_card_updated_by',
        'linen_card_created_by',
    ];

    // public $with = ['module'];

    public $timestamps = false;
    public $incrementing = false;
    public $rules = [
        'linen_card_id' => 'required',
        'linen_card_company_id' => 'required|unique:system_company',
    ];

    const CREATED_AT = 'linen_card_created_at';
    const UPDATED_AT = 'linen_card_updated_at';
    const DELETED_AT = 'linen_card_deleted_at';

    const CREATED_BY = 'linen_card_created_by';
    const UPDATED_BY = 'linen_card_updated_by';
    const DELETED_BY = 'linen_card_deleted_by';

    protected $casts = [
        'linen_card_created_at' => 'datetime:Y-m-d H:i:s',
        'linen_card_updated_at' => 'datetime:Y-m-d H:i:s',
        'linen_card_deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $dates = [
        'linen_card_created_at',
        'linen_card_updated_at',
        'linen_card_deleted_at',
    ];

    public $searching = 'linen_card_item_product_name';
    public $datatable = [
        'linen_card_id' => [false => 'Code', 'width' => 50],
        'linen_card_company_id' => [false => 'Company'],
        'linen_card_company_name' => [true => 'Company'],        
        'linen_card_item_product_id' => [false => 'Company'],
        'linen_card_item_product_name' => [true => 'Product'],
        'linen_card_stock_company' => [true => 'Stock', 'width' => 50],
        'linen_card_stock_kotor' => [true => 'Kotor', 'width' => 50],
        'linen_card_stock_bersih' => [true => 'Bersih', 'width' => 50],
        'linen_card_stock_pending' => [true => 'Pending', 'width' => 50],
        'linen_card_stock_hilang' => [true => 'Hilang', 'width' => 50],
        'linen_card_stock_saldo' => [true => 'Saldo', 'width' => 50],
        'linen_card_stock_notes' => [true => 'Notes'],
    ];

    public static function boot()
    {
        parent::boot();

        parent::saving(function($model){

       
            
        });
    }    
}
