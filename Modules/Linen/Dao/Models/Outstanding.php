<?php

namespace Modules\Linen\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\Item\Dao\Models\Linen;
use Modules\Linen\Dao\Facades\MasterOutstandingFacades;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Wildside\Userstamps\Userstamps;

class Outstanding extends Model
{
    use Userstamps;

    protected $table = 'linen_outstanding';
    protected $primaryKey = 'linen_outstanding_id';

    protected $fillable = [
        'linen_outstanding_id',
        'linen_outstanding_rfid',
        'linen_outstanding_status',
        'linen_outstanding_created_at',
        'linen_outstanding_updated_at',
        'linen_outstanding_downloaded_at',
        'linen_outstanding_uploaded_at',
        'linen_outstanding_deleted_at',
        'linen_outstanding_updated_by',
        'linen_outstanding_created_by',
        'linen_outstanding_deleted_by',
        'linen_outstanding_session',
        'linen_outstanding_key',
        // 'linen_outstanding_scan_location_id',
        // 'linen_outstanding_scan_location_name',
        'linen_outstanding_scan_company_id',
        'linen_outstanding_scan_company_name',
        'linen_outstanding_product_id',
        'linen_outstanding_product_name',
        'linen_outstanding_ori_location_id',
        'linen_outstanding_ori_location_name',
        'linen_outstanding_ori_company_id',
        'linen_outstanding_ori_company_name',
        'linen_outstanding_description',
    ];

    // public $with = ['module'];

    public $timestamps = true;
    public $incrementing = true;
    public $rules = [
        'linen_outstanding_scan_company_id' => 'required|exists:system_company,company_id',
        // 'linen_outstanding_scan_location_id' => 'required|exists:system_location,location_id',
        'linen_outstanding_rfid' => 'required|exists:item_linen,item_linen_rfid'
    ];

    const CREATED_AT = 'linen_outstanding_created_at';
    const UPDATED_AT = 'linen_outstanding_updated_at';
    const DELETED_AT = 'linen_outstanding_deleted_at';

    const CREATED_BY = 'linen_outstanding_created_by';
    const UPDATED_BY = 'linen_outstanding_updated_by';
    const DELETED_BY = 'linen_outstanding_deleted_by';

    protected $casts = [
        'linen_outstanding_created_at' => 'datetime:Y-m-d H:i:s',
        'linen_outstanding_updated_at' => 'datetime:Y-m-d H:i:s',
        'linen_outstanding_deleted_at' => 'datetime:Y-m-d H:i:s',
        'linen_outstanding_status' => 'string',
        'linen_outstanding_description' => 'string',
    ];

    protected $dates = [
        'linen_outstanding_created_at',
        'linen_outstanding_updated_at',
        'linen_outstanding_deleted_at',
    ];

    public $searching = 'linen_outstanding_rfid';
    public $datatable = [
        'linen_outstanding_session' => [false => 'No. Transaksi'],
        'linen_outstanding_key' => [true => 'No. Transaksi'],
        'linen_outstanding_id' => [false => 'Code', 'width' => 50],
        'linen_outstanding_rfid' => [true => 'No. Seri RFID', 'width' => 180],
        'linen_outstanding_product_name' => [true => 'Product'],
        'linen_outstanding_scan_company_name' => [true => 'Scan R.S'],
        'linen_outstanding_ori_company_name' => [true => 'Original R.S'],
        'linen_outstanding_ori_location_id' => [false => 'Location'],
        'linen_outstanding_ori_location_name' => [true => 'Location'],
        'linen_outstanding_created_at' => [false => 'Created At'],
        'name' => [true => 'Operator'],
        'linen_outstanding_status' => [true => 'Status', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
        'linen_outstanding_description' => [true => 'Description', 'width' => 100, 'class' => 'text-center', 'status' => 'description'],
    ];

    public $status = [
        '1' => ['Sync', 'success'],
        '2' => ['Gate', 'info'],
        '3' => ['Done', 'primary'],
        '4' => ['Pending', 'warning'],
        '5' => ['Hilang', 'danger'],
    ];

    public $description = [
        '1' => ['OK', 'success'],
        '2' => ['Beda Rumah Sakit', 'info'],
        '3' => ['Belum di Scan', 'danger'],
    ];

    public function description()
    {
        return $this->description;
    }

    public function status()
    {
        return $this->status;
    }

    public function getStatusAttribute($value)
    {
        return $this->status[$value][0];
    }

    public function getDescriptionAttribute($value)
    {
        return $this->description()[$value][0];
    }

    public function getSessionKeyName(){
        return 'linen_outstanding_session';
    }

    public function getGroupingKeyName(){
        return 'linen_outstanding_grouping';
    }

    public function master()
	{
		return $this->hasOne(MasterOutstanding::class, MasterOutstandingFacades::getSessionKeyName(), $this->getSessionKeyName());
    }

    public function rfid()
    {
        return $this->hasOne(Linen::class, 'item_linen_rfid', 'linen_outstanding_rfid');
    }

    public function user(){

		return $this->hasOne(User::class, TeamFacades::getKeyName(), self::CREATED_BY);
    }
    
    public static function boot()
    {
        parent::boot();
        parent::saved(function($model){
            
            $session = $model->{$model->getSessionKeyName()};
            if($session)
            {
                $master = MasterOutstandingFacades::where('linen_master_outstanding_session', $session)->first();
                if($master){
                    $initial = $master->linen_master_outstanding_total ?? 0;
                    $total = $master->outstanding->count() ?? 0;
                    if($total >= $initial){
                        $master->linen_master_outstanding_status = 2;
                        $master->save();
                    }
                }
            }
        });
        
        parent::saving(function ($model) {
            $linen = LinenFacades::where('item_linen_rfid', $model->linen_outstanding_rfid)->first();
            if ($linen) {

                $model->linen_outstanding_product_id = $linen->item_linen_product_id;
                $model->linen_outstanding_product_name = $linen->product->item_product_name ?? '';

                $model->linen_outstanding_ori_company_id = $linen->item_linen_company_id;
                $model->linen_outstanding_ori_company_name = $linen->company->company_name ?? '';

                // $model->linen_outstanding_ori_location_id = $linen->item_linen_location_id;
                // $model->linen_outstanding_ori_location_name = $linen->location->location_name ?? '';
            }

            $model->linen_outstanding_created_name = auth()->user()->name ?? '';

            $company = CompanyFacades::find($model->linen_outstanding_scan_company_id);
            $model->linen_outstanding_scan_company_name = $company->company_name ?? '';

            // $location = LocationFacades::find($model->linen_outstanding_scan_location_id);
            // $model->linen_outstanding_scan_location_name = $location->location_name ?? '';

            $model->linen_outstanding_description = 1;
            if($model->linen_outstanding_scan_company_id != $linen->item_linen_company_id){

                $model->linen_outstanding_description = 2;
            }

            // if ($model->linen_outstanding_scan_company_id == $linen->item_linen_company_id && $linen->item_linen_location_id == $model->linen_outstanding_scan_location_id) {

            //     $model->linen_outstanding_description = 1;

            // } else if ($model->linen_outstanding_scan_company_id != $linen->item_linen_company_id) {

            //     $model->linen_outstanding_description = 2;

            // } else {

            //     $model->linen_outstanding_description = 3;
            // }

        });

        parent::creating(function ($model) {

            $model->linen_outstanding_status = 1;
            $rfid = $model->rfid;
            $counter = $rfid->item_linen_counter ?? 0;
            $rfid->item_linen_counter = $counter + 1;
            $rfid->save();
        });
    }

}
