<?php

namespace Modules\Item\Dao\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProduct extends Model
{
	protected $table = 'system_company_connection_item_product';
	protected $primaryKey = 'company_item_id';
	public $foreignKey = 'item_product_id';

	protected $fillable = [
		'company_id',
		'item_product_id',
		'company_item_target',
		'company_item_maximal',
		'company_item_minimal',
		'company_item_unit_id',
		'company_item_size_id',
		'company_item_weight',
		'company_item_id',
		'company_item_description',
	];

	public $incrementing = true;
	public $timestamps = false;	

	public $searching = 'company_item_id';
    public $datatable = [
        'company_item_id' => [false => 'Code'],
        'company_id' => [true => 'Code'],
        'item_product_id' => [true => 'Product Id'],
	];
	
	public $rules = [
        'company_id' => 'required|exists:system_company',
        'item_product_id' => 'required|exists:item_product',
    ];
}
