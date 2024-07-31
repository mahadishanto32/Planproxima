<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Factory;
use App\Models\User;
class ProductionWastageDaft extends Model
{
    protected $table = "productionwastagedafts";
    protected $fillable = array(
        "id",
        "comp_code" ,
        "plant",
        "unit_code" ,
        "product_code" ,
        "date",
        "target_qty" ,
        "production_quantity_gnh" ,
        "production_quantity_oth" ,
        "delivery_qty" ,
        "consumtion" ,
        "consumtion_value",
        "wastage_value",
        "wastage" ,
        "consumtion" ,
        "return" ,
        "type" ,
        "type" , 
        "sap_file_id" ,
        "error_note",
        "remarks" ,
        "created_by" ,
        "updated_by" ,
        "created_at" ,
        "updated_at" 
    );

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
    public function areajoin()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

}
 