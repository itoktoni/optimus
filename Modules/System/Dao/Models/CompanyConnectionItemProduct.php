<?php

namespace Modules\System\Dao\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyConnectionItemProduct extends Model
{
	protected $table = 'system_company_connection_item_product';
	protected $primaryKey = 'company_id';
	public $foreignKey = 'item_product_id';
	protected $fillable = [
		'company_id',
		'item_product_id',

	];
	public $incrementing = false;
	public $timestamps = false;	

	
	public function getForeignKey()
	{
		return $this->foreignKey;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}
}
