<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\CostCenter;
use App\Models\Factory;
use App\Models\User;
class Delivery extends Model
{
    protected $table = "delivery";
    protected $fillable = array(
        "id", 
        "product_id", 
        "factory_id", 
        "pg_code", 
        "date", 
        "delivery_qty", 
        "remarks", 
        "summary_group_id", 
        "darft_id",
        "sap_file_id",
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at");

    public function productjoin()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'created_by');
    } 
    public function factoryjoin()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

}
