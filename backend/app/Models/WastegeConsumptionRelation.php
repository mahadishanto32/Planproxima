<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;  
class WastegeConsumptionRelation extends Model
{
	protected $table = "wastege_consumption_relation";
	protected $fillable = array('id', 
		'wastage_summary_group_id', 
		'wastage_product_code', 
		'wastage_product_id',
		'consumtion_product_code',
		'consumtion_product_id',
		'consumtion_product',
		'created_by'
		  
	);
 
}
