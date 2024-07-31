<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product; 
use App\Models\Factory;
use App\Models\CostCenter;
use App\Models\Consumtion;
use App\Models\User;
use DB ;
class Wastage extends Model
{
    protected $table = "wastages";
    protected $fillable = array('id', 
    'product_id', 
    'factory_id',
    'created_by',
    'updated_by', 
    'created_at',
    'updated_at',
    'factory_code',
    'pg_code',
    'cost_code_id',
    'order_group_id',
    'summary_group_id',
    'wastage_summary_group_id',
    'date' ,
    'area_id', 
    "darft_id", 
    "sap_file_id",
    'actual_wastage',
    'wastage_value',
    'unit',
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

    public function consumtionjoin($request_data,$product_id){
       $query  =   Consumtion::select(DB::Raw('SUM(consumtion) as consumtion'));
       if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 
        // if($order_group_id){
        //     $query->where('order_group_id',$order_group_id);
        // }
       $result =   $query->where('product_id', $product_id)->first();
       return $result  ;
        // return $product_id  ; 
    }
    public  function consumtionNewjoin($request_data,$product_id){ 
        $product  = Product::find($product_id);  
        $parent_material_query  = Product::select( DB::Raw('SUM(consumtions.consumtion) as consumtion') );
        $parent_material_query->where('parent_material' , $product->material_code);
        $parent_material_query->leftJoin('consumtions', 'consumtions.product_id', '=', 'products.id');
        if($request_data['start_date']  && $request_data['end_date']){
            $parent_material_query->whereBetween('consumtions.date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 
        $parent_material =  $parent_material_query->first();

        return $parent_material  ;
    }
    public function previousdata($request_data,$item){
       
       $wastage_query =  Wastage::select('product_id',DB::Raw('SUM(actual_wastage) as actual_wastage'))->where('product_id',$item->product_id );
       if($request_data['start_date']  && $request_data['end_date']){
            $wastage_query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        } 

       $wastage_items =  $wastage_query->groupBy('product_id')->first();
       return  $wastage_items   ; 
         
    }
    public function previousYearAverage($item ,  $request_data , $year ){

        $wastage_query = Wastage::select('product_id',DB::Raw('SUM(actual_wastage) as actual_wastage')); 
        if($year == 'last_year'){
            $prev_year = date("Y", strtotime(date("Y-m-d", strtotime($request_data['start_date'])) . " - 1 year")) ;
            $prev_year_start_date  =  $prev_year.'-01-01' ;
            $prev_year_end_date  =  $prev_year.'-12-31' ;   
        }else{
            $prev_year = date("Y", strtotime(date("Y-m-d", strtotime($request_data['start_date'])))) ;
            $prev_year_start_date  =  $prev_year.'-01-01' ;
            $prev_year_end_date  =  $prev_year.'-12-31' ;   
        }
     
        if( $prev_year_start_date && $prev_year_end_date   ){
            $wastage_query->whereBetween('date', [date('Y-m-d', strtotime($prev_year_start_date)), date('Y-m-d', strtotime( $prev_year_end_date ))]);  
        } 
        $wastage_query->where('product_id',$item->product_id); 
        $wastage_items = $wastage_query->groupBy('product_id')->first();  
        $actual_wastage =   $wastage_items ?   $wastage_items ->actual_wastage : 0 ; 

        $product  = Product::find($item->product_id);  
        $parent_material_query  = Product::select( DB::Raw('SUM(consumtions.consumtion) as consumtion') );
        $parent_material_query->where('parent_material' , $product->material_code);
        $parent_material_query->leftJoin('consumtions', 'consumtions.product_id', '=', 'products.id'); 
        if( $prev_year_start_date && $prev_year_end_date   ){
            $parent_material_query->whereBetween('consumtions.date', [date('Y-m-d', strtotime($prev_year_start_date)), date('Y-m-d', strtotime( $prev_year_end_date ))]);  
        } 
        $parent_material =  $parent_material_query->first();

        $parent_material_number  =   $parent_material ? $parent_material->consumtion : 0 ;

        return  $parent_material_number ?  $actual_wastage /  $parent_material_number  :  0 ;
 
  
    }


}
 