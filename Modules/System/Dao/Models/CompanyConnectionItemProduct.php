<?php

namespace Modules\System\Dao\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Item\Dao\Facades\CompanyProductFacades;
use Modules\Item\Dao\Facades\ProductFacades;
use Modules\Item\Dao\Facades\SizeFacades;
use Modules\Item\Dao\Facades\UnitFacades;
use Modules\Item\Dao\Models\Product;
use Modules\Item\Dao\Models\Size;
use Modules\Item\Dao\Models\Unit;
use Modules\Item\Dao\Repositories\CompanyProductRepository;
use Modules\Linen\Dao\Facades\StockFacades;
use Modules\System\Dao\Facades\CompanyFacades;

class CompanyConnectionItemProduct extends Model
{
	protected $table = 'system_company_connection_item_product';
	protected $primaryKey = 'company_item_id';

	protected $fillable = [
		'company_id',
		'item_product_id',
		'company_item_id',
		'company_item_target',
		'company_item_realisasi',
		'company_item_maximal',
		'company_item_minimal',
		'company_item_unit_id',
		'company_item_size_id',
		'company_item_weight',
		'company_item_price',
		'company_item_description',
		'company_item_counter',
	];

	public $incrementing = true;
	public $timestamps = false;	

	public $searching = 'system_company.company_id';
    public $datatable = [
        'company_item_id' => [false => 'ID'],
        'system_company.company_id' => [false => 'Code'],
        'company_name' => [true => 'Company Name'],
        'item_product.item_product_id' => [false => 'Product Id'],
        'item_product_name' => [true => 'Product Name'],
        'company_item_unit_id' => [false => 'Unit Id'],
        'item_size_code' => [false => 'Size Id'],
        'item_size_name' => [true => 'Size Name'],
        'company_item_price' => [true => 'Price', 'width' => '50'],
        'company_item_target' => [true => 'Parstok', 'width' => '50'],
        'company_item_realisasi' => [true => 'Real', 'width' => '50'],
        'company_item_minimal' => [true => 'Kekurangan', 'width' => '80'],
        'company_item_weight' => [true => 'Kg', 'width' => '50'],
        'item_unit_name' => [false => 'Unit Name', 'width' => '80'],
        'company_item_maximal' => [false => 'Max', 'width' => '50'],
	];
	
	public $rules = [
        'company_id' => 'required|exists:system_company',
        'item_product_id' => 'required|exists:item_product',
	];
	
	public function company()
    {
        return $this->hasOne(Company::class, CompanyFacades::getKeyName(), CompanyFacades::getKeyName());
	}
	
	public function product()
    {
        return $this->hasOne(Product::class, ProductFacades::getKeyName(), ProductFacades::getKeyName());
	}
	
	public function size()
    {
        return $this->hasOne(Size::class, SizeFacades::getKeyName(), 'company_item_size_id');
	}
	
	public function unit()
    {
        return $this->hasOne(Unit::class, UnitFacades::getKeyName(), 'company_item_unit_id');
    }

	public static function boot()
    {
        parent::saving(function ($model) {

			$company = CompanyFacades::find($model->company_id);
			$item_product = ProductFacades::find($model->item_product_id);

            $stock = StockFacades::where('linen_stock_company_id', $model->company_id)->where('linen_stock_item_product_id', $model->item_product_id)->first();
			if($stock){

			}
			else{
				$data = [
					'linen_stock_company_id' => $model->company_id,
					'linen_stock_company_name' => $company->company_name ?? '',
					'linen_stock_item_product_id' => $model->item_product_id,
					'linen_stock_item_product_name' => $item_product->item_product_name ?? '',
					'linen_stock_qty' => 0,
				];
				StockFacades::create($data);
			}
        });

        parent::boot();
    }
}
