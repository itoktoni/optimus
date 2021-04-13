<?php

namespace Modules\Item\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Observers\LinenObserver;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Modules\System\Dao\Models\Location;
use Wildside\Userstamps\Userstamps;
use Illuminate\Validation\Rule;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Models\Company;

class Linen extends Model
{
    use SoftDeletes, Userstamps;
    protected $table = 'item_linen';
    protected $primaryKey = 'item_linen_id';

    protected $fillable = [
        'item_linen_id',
        'item_linen_rfid',
        'item_linen_description',
        'item_linen_status',
        'item_linen_rent',
        'item_linen_session',
        'item_linen_location_id',
        'item_linen_company_id',
        'item_linen_product_id',
        'item_linen_created_at',
        'item_linen_updated_at',
        'item_linen_update_by',
        'item_linen_created_by',
        'item_linen_deleted_by',
    ];

    // public $with = ['location', 'product', 'user'];

    public $timestamps = true;
    public $incrementing = true;

    public $rules = [
        'item_linen_location_id' => 'required|exists:system_location,location_id',
        'item_linen_company_id' => 'required|exists:system_company,company_id',
        'item_linen_product_id' => 'required|exists:item_product,item_product_id',
        'item_linen_rent' => 'required|in:1,2',
        'item_linen_rfid' => 'required|unique:item_linen',
    ];

    const CREATED_AT = 'item_linen_created_at';
    const UPDATED_AT = 'item_linen_updated_at';
    const DELETED_AT = 'item_linen_deleted_at';
    
    const CREATED_BY = 'item_linen_created_by';
    const UPDATED_BY = 'item_linen_updated_by';
    const DELETED_BY = 'item_linen_deleted_by';

    public $searching = 'item_linen_rfid';
    public $datatable = [
        'item_linen_id' => [true => 'Code', 'width' => 50],
        'item_linen_rfid' => [true => 'RFID', 'width' => 200],
        'item_linen_product_id' => [false => 'Product Id'],
        'item_product_name' => [true => 'Product Name'],
        'item_linen_location_id' => [false => 'Location Id'],
        'company_name' => [true => 'Company Name'],
        'location_name' => [true => 'Location Name'],
        'name' => [true => 'Register By'],
        'item_linen_session' => [false => 'Key'],
        'item_linen_created_at' => [true => 'Created At'],
        'item_linen_rent' => [true => 'Rental', 'width' => 50, 'class' => 'text-center'],
        'item_linen_status' => [true => 'Status', 'width' => 50, 'class' => 'text-center'],
    ];

    protected $casts = [
        'item_linen_created_at' => 'datetime:Y-m-d H:i:s',
        'item_linen_updated_at' => 'datetime:Y-m-d',
        'item_linen_deleted_at' => 'datetime:Y-m-d',
        'item_linen_rent' => 'string',
    ];

    protected $dates = [
        'item_linen_created_at',
        'item_linen_updated_at',
        'item_linen_deleted_at',
    ];
    
    public $status    = [
        '1' => ['Baik', 'info'],
        '0' => ['Rusak', 'danger'],
        '' => ['Unset', 'default'],
    ];

    public $rent    = [
        '1' => ['Sewa', 'success'],
        '2' => ['Laundry', 'primary'],
    ];

    public function getPrimaryKeyCompanyAttribute(){

        return 'item_linen_company_id';
    }

    public function getPrimaryKeyLocationAttribute(){

        return 'item_linen_location_id';
    }

    public function getPrimaryKeyProductAttribute(){

        return 'item_linen_product_id';
    }

    public function rent(){
        return $this->rent;
    }

    public function status(){
        return $this->status;
    }

	public function product(){

		return $this->hasOne(Product::class, ProductFacades::getKeyName(), $this->getPrimaryKeyProductAttribute());
    }
    
	public function location(){

		return $this->hasOne(Location::class, LocationFacades::getKeyName(), $this->getPrimaryKeyLocationAttribute());
    }
    
	public function company(){

		return $this->hasOne(Company::class, CompanyFacades::getKeyName(), $this->getPrimaryKeyCompanyAttribute());
    }

	public function user(){

		return $this->hasOne(User::class, TeamFacades::getKeyName(), self::CREATED_BY);
    }
}
