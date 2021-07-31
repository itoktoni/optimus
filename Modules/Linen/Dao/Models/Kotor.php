<?php

namespace Modules\Linen\Dao\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Item\Dao\Facades\LinenFacades;
use Modules\System\Dao\Facades\CompanyFacades;
use Modules\System\Dao\Facades\LocationFacades;
use Modules\System\Dao\Facades\TeamFacades;
use Wildside\Userstamps\Userstamps;

class Kotor extends Model
{
    use SoftDeletes, Userstamps;

    protected $table = 'linen_kotor';
    protected $primaryKey = 'linen_kotor_key';
    protected $keyType = 'string';

    protected $fillable = [
        'linen_kotor_id',
        'linen_kotor_total',
        'linen_kotor_status',
        'linen_kotor_created_at',
        'linen_kotor_updated_at',
        'linen_kotor_deleted_at',
        'linen_kotor_updated_by',
        'linen_kotor_created_by',
        'linen_kotor_created_name',
        'linen_kotor_deleted_by',
        'linen_kotor_key',
        'linen_kotor_company_id',
        'linen_kotor_company_name',
    ];

    // public $with = ['module'];

    public $timestamps = true;
    public $incrementing = false;
    public $rules = [
        'linen_kotor_key' => 'required|unique:linen_kotor',
        'linen_kotor_company_id' => 'required|unique:system_company',
        'barcode' => 'required',
    ];

    const CREATED_AT = 'linen_kotor_created_at';
    const UPDATED_AT = 'linen_kotor_updated_at';
    const DELETED_AT = 'linen_kotor_deleted_at';

    const CREATED_BY = 'linen_kotor_created_by';
    const UPDATED_BY = 'linen_kotor_updated_by';
    const DELETED_BY = 'linen_kotor_deleted_by';

    protected $casts = [
        'linen_kotor_created_at' => 'datetime:Y-m-d H:i:s',
        'linen_kotor_updated_at' => 'datetime:Y-m-d H:i:s',
        'linen_kotor_deleted_at' => 'datetime:Y-m-d H:i:s',
        'linen_kotor_status' => 'string',
        'linen_kotor_total' => 'integer',
    ];

    protected $dates = [
        'linen_kotor_created_at',
        'linen_kotor_updated_at',
        'linen_kotor_deleted_at',
    ];

    public $searching = 'linen_kotor_key';
    public $datatable = [
        'linen_kotor_id' => [false => 'Code', 'width' => 50],
        'linen_kotor_key' => [true => 'No. Linen Kotor'],
        'linen_kotor_company_name' => [true => 'Company'],
        'linen_kotor_total' => [true => 'Total'],
        'linen_kotor_created_by' => [false => 'Created At'],
        'linen_kotor_created_at' => [true => 'Created At'],
        'name' => [true => 'Created By'],
        'linen_kotor_status' => [false => 'Status', 'width' => 50, 'class' => 'text-center', 'status' => 'status'],
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

    public function user(){

		return $this->hasOne(User::class, TeamFacades::getKeyName(), self::CREATED_BY);
    }

    public function detail(){

		return $this->hasMany(KotorDetail::class, 'linen_kotor_detail_key', 'linen_kotor_key');
    }
    
    public static function boot()
    {
        parent::boot();

        parent::saving(function($model){

            $company = $model->linen_kotor_company_id;
            $model->linen_kotor_company_name = CompanyFacades::find($company)->company_name ?? '';
            $model->linen_kotor_created_name = auth()->user()->name ?? '';
        });
    }    
}
