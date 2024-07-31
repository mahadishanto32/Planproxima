<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model; 
use App\Models\ProductionTarget;
use App\Models\Manufacturer;
use App\Models\Delivery;
class Ordergroup extends Model
{
    protected $table = "ordergroup";
    protected $fillable = array('id', 
    'name', 
    'code',
    'created_by',
    'updated_by', 
    'created_at',
    'updated_at',
    );
    public function capacity($type )
    {
        return ProductionTarget::where('order_group_id',$this->id)->where('type',$type)->first();
    }
    public function getUomAttribute()
    {
        return  0 ;
    }
 
    public function aProduction($type,$request_data)
    {
        $query =   Manufacturer::where('order_group_id',$this->id);
        if($request_data['factory_id']){
         $query->where('factory_id', $request_data['factory_id'] );
        } 
        if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]); 
        } 
        if($type == 'gnh'){
            return $query->sum('production_quantity_gnh'); 
        }else{
            return $query->sum('production_quantity_oth'); 
        } 
    }
    public function aDelivery($type,$request_data)
    {
        $query =   Delivery::where('order_group_id',$this->id);
        if($request_data['factory_id']){
         $query->where('factory_id', $request_data['factory_id'] );
        } 
        if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 
        $result =  $query->sum('delivery_qty');  
        return $result  ;  
    }
    public function aProductionPrevious($type,$request_data)
    {
        $query =   Manufacturer::where('order_group_id',$this->id);
        if($request_data['factory_id']){
         $query->where('factory_id', $request_data['factory_id'] );
        } 
        if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 
        if($type == 'gnh'){
            return $query->sum('production_quantity_gnh'); 
        }else{
            return $query->sum('production_quantity_oth'); 
        } 
    
    }
    public function aDeliveryPrevious($type,$request_data)
    {
        $query =   Delivery::where('order_group_id',$this->id);
        if($request_data['factory_id']){
         $query->where('factory_id', $request_data['factory_id'] );
        } 
        if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 
        $result =  $query->sum('delivery_qty');  
        return $result  ; 
  
    }
    //AVG Production 
    public function  aProductionAvg($type,$request_data)
    {
        $query =   Manufacturer::where('order_group_id',$this->id);
        if($request_data['factory_id']){
         $query->where('factory_id', $request_data['factory_id'] );
        } 
        if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 
        if($type == 'gnh'){
            return $query->sum('production_quantity_gnh')/12;   
        }else{
            return $query->sum('production_quantity_oth')/12;  
        }  ;  
    }
    //AVG Production 
    public function  aDeliveryAvg($type,$request_data)
    {
       
        $query =   Delivery::where('order_group_id',$this->id);
        if($request_data['factory_id']){
         $query->where('factory_id', $request_data['factory_id'] );
        } 
        if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 
       
        $result =  $query->sum('delivery_qty')/ date("m",strtotime($request_data['start_date'] ));;  
        return $result  ;

    }

      //Production Plan
    public function  getProductionPlanGNHAttribute()
    {
        return   0 ;
    }
    public function test($a){
        return   $a ;
    }

}
 