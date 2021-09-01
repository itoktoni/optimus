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
use Mehradsadeghi\FilterQueryString\FilterQueryString;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Item\Dao\Facades\CompanyProductFacades;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Linen\Dao\Facades\CardFacades;
use Modules\System\Dao\Models\Company;
use Modules\System\Plugins\Cards;

class Linen extends Model
{
    use Userstamps, FilterQueryString, HasFactory;
    protected $table = 'item_linen';
    protected $primaryKey = 'item_linen_id';

    protected $filters = [
        'item_linen_company_id',
        'item_linen_location_id',
        'item_linen_company_id',
        'item_linen_product_id',
        'item_linen_status',
        'item_linen_created_by',
        'item_linen_updated_at',
    ];
    
    protected $fillable = [
        'item_linen_id',
        'item_linen_rfid',
        'item_linen_description',
        'item_linen_status',
        'item_linen_rent',
        'item_linen_session',
        'item_linen_location_id',
        'item_linen_location_name',
        'item_linen_company_id',
        'item_linen_company_name',
        'item_linen_product_id',
        'item_linen_product_name',
        'item_linen_created_at',
        'item_linen_updated_at',
        'item_linen_counter',
        'item_linen_update_by',
        'item_linen_created_by',
        'item_linen_created_name',
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
        'item_linen_status' => 'required|in:1,2',
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
        'item_linen_id' => [false => 'Code', 'width' => 50],
        'item_linen_rfid' => [true => 'No. Seri RFID', 'width' => 200],
        'item_linen_product_id' => [false => 'Product Id'],
        'item_linen_product_name' => [true => 'Product Name'],
        'item_linen_company_id' => [false => 'Company Id'],
        'item_linen_company_name' => [true => 'Company Name'],
        'item_linen_location_id' => [false => 'Location Id'],
        'item_linen_location_name' => [true => 'Location Name'],
        'item_linen_session' => [false => 'Key'],
        'item_linen_counter' => [true => 'Counter', 'width' => 50],
        'item_linen_created_at' => [false => 'Created At'],
        'item_linen_rent' => [true => 'Rental', 'width' => 50, 'class' => 'text-center', 'status' => 'rent'],
        'item_linen_status' => [true => 'Status', 'width' => 150, 'class' => 'text-center', 'status' => 'status'],
    ];

    protected $casts = [
        'item_linen_created_at' => 'datetime:Y-m-d H:i:s',
        'item_linen_updated_at' => 'datetime:Y-m-d H:i:s',
        'item_linen_deleted_at' => 'datetime:Y-m-d H:i:s',
        'item_linen_rent' => 'string',
        'item_linen_status' => 'string',
    ];

    protected $dates = [
        'item_linen_created_at',
        'item_linen_updated_at',
        'item_linen_deleted_at',
    ];
    
    public $status    = [
        '1' => ['Register Baru', 'info'],
        '2' => ['Ganti chip', 'danger'],
    ];

    public $rent    = [
        '1' => ['Rental', 'success'],
        '2' => ['Cuci', 'primary'],
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

    public static function boot()
    {
        parent::boot();
        parent::saving(function ($model) {

            if(empty($model->item_linen_status)){
                $model->item_linen_status = 1;
            }

            $model->item_linen_company_name = CompanyFacades::find($model->item_linen_company_id)->company_name ?? '';
            $model->item_linen_product_name = ProductFacades::find($model->item_linen_product_id)->item_product_name ?? '';
            $model->item_linen_location_name = LocationFacades::find($model->item_linen_location_id)->location_name ?? '';
            $model->item_linen_created_name = auth()->user()->name ?? '';
            
        });

        parent::saved(function($model){

            $total = LinenFacades::getTotal($model->item_linen_company_id, $model->item_linen_product_id)->count();
            $realisasi = CompanyProductFacades::getRealisasi($model->item_linen_company_id, $model->item_linen_product_id)->first();
            if($realisasi){
                
                $realisasi->company_item_realisasi = $total;
                $realisasi->save();

                Cards::stock($model->item_linen_company_id, $model->item_linen_product_id, $total);
            }

        });

        parent::deleted(function($model){

            foreach(request()->get('code') as $id){

                $data = Linen::find($id);
                if($data){
                    $total = LinenFacades::getTotal($data->item_linen_company_id, $data->item_linen_product_id)->count();
                    $realisasi = CompanyProductFacades::getRealisasi($model->item_linen_company_id, $model->item_linen_product_id)->first();
                    if($realisasi){
                        $realisasi->company_item_realisasi = $total;
                        $realisasi->save();
                    }
                }
            }
            
        });
    }
}
