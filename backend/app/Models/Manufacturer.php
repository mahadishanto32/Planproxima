<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\CostCenter;
use App\Models\SummaryGroup;
use App\Models\Factory;
use App\Models\Delivery;
use App\Models\User;
class Manufacturer extends Model
{
    protected $table = "manufacturers";
    protected $fillable = array('id', 
    'product_id', 
    'factory_id',
    'created_by',
    'updated_by', 
    'created_at',
    'updated_at', 
    'pg_code',
    'cost_code_id',
    'order_group_id',
    'date' , 
    'darft_id',
    'sap_file_id', 
    'summary_group_id',
    'production_quantity_gnh',
    'production_quantity_oth', 
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
        return $this->belongsTo(CostCenter::class, 'cost_code_id');
    }
    public function factoryjoin()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }
    public function ordergroupjoin()
    {
        return $this->belongsTo(Ordergroup::class, 'order_group_id');
    }
    public function summarygroupjoin()
    {
        return $this->belongsTo(SummaryGroup::class, 'summary_group_id');
    }
    public function delivery($request)
    {
        $query =  Delivery::where('product_id',$this->product_id);
        if($request['start_date']  && $request['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        }
       return  $delivery = $query->sum('delivery_qty'); 
    }

}
