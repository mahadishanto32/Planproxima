<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Factory;
use App\Models\CostCenter;
use App\Models\User;
class Consumtion extends Model
{
    protected $table = "consumtions";
    protected $fillable = array('id', 
    'product_id', 
    'factory_id', 
    'factory_code',
    'pg_code',
    'cost_code_id',
    'summary_group_id',
    'date' , 
    "darft_id",
    "wastage_summary_group_id",
    "sap_file_id", 
    'order_group_id',
    'consumtion', 
    'consumtion_value',
    'created_by',
    'updated_by', 
    'created_at',
    'updated_at',
    'remarks');

    public function productjoin()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function costjoin()
    {
        return $this->belongsTo(CostCenter::class, 'cost_code');
    }
    public function factoryjoin()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }
    public function areajoin()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

}
 