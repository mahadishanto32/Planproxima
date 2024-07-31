<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;  
use DB;
use App\Models\Wastage;
use App\Models\Consumtion;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
class WastageSummaryGroup extends Model
{
	protected $table = "wastage_summary_group";
	protected $fillable = array('id',   
        'group_name', 
         'scrap_material', 
         'plant', 
         'plant_id', 
         'status',
         'summary_group_id', 
         'type', 
         'uom'

	);
    public function test(){ 

    }
	 
    public  function consumtionjoin($grouping_id , 
    $request , $filterType){ 
        $groups = WastageSummaryGroup::where('grouping_id', $grouping_id)->get();
        $parent_material_query = WastegeConsumptionRelation::selectRaw('wastege_consumption_relation.consumtion_product_code')
        ->select(DB::Raw('SUM(consumtions.consumtion) as consumtion'))
        ->join('consumtions', 'consumtions.product_id', '=', 'wastege_consumption_relation.consumtion_product_code');
        if($request['start_date']  && $request['end_date'] && $filterType == 'current'){
            $parent_material_query->whereBetween('consumtions.date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        } 
       
        $parent_material_query->where('consumtions.summary_group_id', $request['summary_group_id']);
    //     $parent_material_query  = WastageSummaryGroup::selectRaw("wastege_consumption_relation.consumtion_product_code")
    //     ->select(DB::Raw('SUM(consumtions.consumtion) as consumtion'))
    //    // ->join('wastege_consumption_relation','wastege_consumption_relation.wastage_summary_group_id','wastage_summary_group.id') 
    //    ->join('wastege_consumption_relation', function($join) use ($grouping_id)
    //    {
    //        $join->on('wastege_consumption_relation.wastage_summary_group_id', '=', 'wastage_summary_group.id');
    //        $join->on('wastege_consumption_relation.wastage_summary_group_id','=',DB::raw($grouping_id));
    //    })
    //    ->join('consumtions', 'consumtions.product_id', '=', 'wastege_consumption_relation.consumtion_product_code')
    //     ->where('grouping_id', $grouping_id)
    //     ->where('consumtions.summary_group_id', $request['summary_group_id']);
        // if($request['start_date']  && $request['end_date'] && $filterType == 'current'){
        //     $parent_material_query->whereBetween('consumtions.date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        // } 

        if($request['summary_group_id'] == 3 ){ 
            if($request['watt5'] == 1){
                $parent_material_query->where('consumtions.order_group_id', 51 );
            }else{
               $parent_material_query->where('consumtions.order_group_id', '!=', 51 );
            } 
        } 
        if($request['start_date']  && $request['end_date'] && $filterType == 'previous'){
            $parent_material_query->whereBetween('consumtions.date', [date('Y-m-d', strtotime($request['start_date_previous'])), date('Y-m-d', strtotime($request['end_date_previous']))]);  
           // $parent_material_query->whereBetween('consumtions.date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        } 
        $group_id = array();
        foreach ($groups  as $key => $value) { 
            $group_id[]=$value->id ; 
        }
         $parent_material_query->whereIn('wastege_consumption_relation.wastage_summary_group_id', $group_id );
        $parent_material =  $parent_material_query->first(); 
        return $parent_material ? $parent_material->consumtion : 0 ;
    }
  

    public function previousdata($request_data){ 
        // $wastage_query =  Wastage::select(DB::Raw('SUM(actual_wastage) as actual_wastage'))
        // ->where('wastage_summary_group_id',$this->grouping_id );
        // if($request_data['start_date_previous']  && $request_data['end_date_previous']){
        //      $wastage_query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date_previous'])), date('Y-m-d', strtotime($request_data['end_date_previous']))]);  
        //  } 
 
        // $wastage_items =  $wastage_query->groupBy('wastage_summary_group_id')->first();
        // return  $wastage_items ? $wastage_items->actual_wastage: 0   ; 

        $wastage_query = WastageSummaryGroup::selectRaw("SUM(wastages.actual_wastage) as actual_wastage ")    
        ->join('products', 'wastage_summary_group.scrap_material', '=', 'products.material_code') 
        ->leftjoin('wastages', 'wastages.product_id', '=', 'products.id'); 
        if($request_data['start_date_previous']  && $request_data['end_date_previous']){
            $wastage_query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date_previous'])), date('Y-m-d', strtotime($request_data['end_date_previous']))]);  
        } 
        if($request_data['summary_group_id']){
            $wastage_query->where('wastages.summary_group_id',$request_data['summary_group_id']);
        } 
        if($request_data['summary_group_id'] == 3 ){ 
            if($request_data['watt5'] == 1){
                $wastage_query->where('wastages.order_group_id', 51 );
            }else{
                $wastage_query->where('wastages.order_group_id', '!=', 51 );
            } 
        } 
        $wastage_query->where('wastage_summary_group.grouping_id',$this->grouping_id);
        // if($factInfo){
        //     $wastage_query->where('wastage_summary_group.plant',  $factInfo->fac_code );
        // } 
        $wastage_query->groupBy('wastage_summary_group.group_name'); 
        $wastage_query->groupBy('wastage_summary_group.grouping_id');  
        $wastage_items = $wastage_query->first(); 
        
        return  $wastage_items ? $wastage_items->actual_wastage: 0   ;

          
     } 

     public function previousYearAverage( $request_data  ){  
        $previousYear = date('Y',strtotime($request_data['start_date'])) - 1;
        $avg = YearlyWastage::where('wastege_summary_group_id',$this->id)
        ->where('summary_group_id', $this->summary_group_id)
        ->where('your',$previousYear)
        ->first() ;

        if($this->summary_group_id  == 3 ){ 
            if($request_data['watt5'] == 1){
                return $avg ? $avg->watt5 : 0 ; 
            }else{
                return $avg ? $avg->avg : 0 ; 
            } 
        }else{
            return $avg ? $avg->avg : 0 ; 
        }

        
    }

    public function currentYearAverage($request_data){      
        $prev_year = date("Y", strtotime(date("Y-m-d", strtotime($request_data['start_date'])))) ;
        $prev_year_start_date  =  $prev_year.'-01-01' ;
        $prev_year_end_date  =  $prev_year.'-12-31' ; 
          
        $last_month_last_date = new DateTime('last day of previous month');
        $lastDate = $last_month_last_date->format('Y-m-d');
        
        $query = Wastage::where('wastage_summary_group_id', $this->id );
        
        if( $prev_year_start_date && $prev_year_end_date ){
            $query->whereBetween('date', [date('Y-m-d', strtotime($prev_year_start_date)), date('Y-m-d', strtotime( $lastDate ))]);  
        }  

        if($request_data['summary_group_id'] == 3){ 
            if($request_data['watt5'] == 1){
                $query->where('order_group_id', 51 );
            }else{
                $query->where('order_group_id','!=', 51 ); 
            } 
        }  

        $monthNo = $last_month_last_date->format('m');
        // if($request_data['summary_group_id'] == 3){
        //     // 5watt 
        //    // $wastage_query->where('wastages.order_group_id','!=', 51 );
        //      if($request_data['watt5'] == 1){
        //         $query->where('order_group_id', 51 );
        //     }else{
        //        $query->where('order_group_id', '!=', 51 );
        //     } 
        // }  

        $actual_wastage = $query->sum('actual_wastage'); 

        $query2 = Consumtion::where('wastage_summary_group_id', $this->id );
        if( $prev_year_start_date && $prev_year_end_date   ){
            $query2->whereBetween('date', [date('Y-m-d', strtotime($prev_year_start_date)), date('Y-m-d', strtotime( $lastDate ))]);  
        } 
        // if($request_data['summary_group_id'] == 3){
        //     // 5watt 
        //    // $wastage_query->where('wastages.order_group_id','!=', 51 );
        //      if($request_data['watt5'] == 1){
        //         $query2->where('order_group_id', 51 );
        //     }else{
        //        $query2->where('order_group_id', '!=', 51 );
        //     } 
        // } 
        if($request_data['summary_group_id'] == 3){ 
            if($request_data['watt5'] == 1){
                $query2->where('order_group_id', 51 );
            }else{
                $query2->where('order_group_id','!=', 51 ); 
            } 
        }   
        $consumtion = $query2->sum('consumtion'); 
        return ($consumtion ?  (($actual_wastage/ $monthNo) /  ($consumtion / $monthNo))  :  0 ) *100 ;
        // return ($consumtion ?  $actual_wastage /  $consumtion  :  0 ) *100 ;
    }

    public function consumption(){
        return $consumption = DB::table('wastege_consumption_relation')
            ->join('products', 'products.id', '=', 'wastege_consumption_relation.consumtion_product_code') 
            ->select('wastege_consumption_relation.*', 'products.description', 'products.material_code')
            ->where('wastege_consumption_relation.wastage_summary_group_id',$this->id)
            ->get();
        //return WastegeConsumptionRelation::where('wastage_summary_group_id',$this->id)->get();
    }
    public function actualWastage($request ,$filterType,$type = 'qty' ){ 
        $query  =  Wastage::where('wastage_summary_group_id', $this->id );
        if($request['start_date_previous']  && $request['end_date_previous'] && $filterType == 'previous'){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request['start_date_previous'])), date('Y-m-d', strtotime($request['end_date_previous']))]);     
        } 
         // 5 watt
        if($request['summary_group_id'] == 3){ 
            if($request['watt5'] == 1){
                $query->where('order_group_id', 51 );
            }else{
                $query->where('order_group_id','!=', 51 ); 
            } 
        }  
        if($request['start_date']  && $request['end_date'] && $filterType == 'current'){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);     
        }
        if($type == 'qty'){
            return $query->sum('actual_wastage');
        }else{
            return $query->sum('wastage_value');
        }
        
    }

    public function consumtion($request ,$filterType ,$type){ 
        $query  =  Consumtion::where('wastage_summary_group_id', $this->id );
        if($request['start_date_previous']  && $request['end_date_previous'] && $filterType == 'previous'){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request['start_date_previous'])), date('Y-m-d', strtotime($request['end_date_previous']))]);     
        }
        if($request['start_date']  && $request['end_date'] && $filterType == 'current'){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);     
        }
        if($request['summary_group_id'] == 3){ 
            if($request['watt5'] == 1){
                $query->where('order_group_id', 51 );
            }else{
                $query->where('order_group_id','!=', 51 ); 
            } 
        } 
        if($type == 'qty'){ 
            return $query->sum('consumtion');
        }else{
            return $query->sum('consumtion_value');
        }
    }

    // protected function filter(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => ucfirst($value),
    //         set: fn ($value) => strtolower($value),
    //     );
    // }
}
