<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model; 
use App\Models\ProductionTarget;
use App\Models\Manufacturer;
use App\Models\Delivery; 
use App\Models\FactoryCapacity;  
use DB ;
use App\Models\Product;
use App\Models\CostCenter; 
use App\Models\ProductionPlans;
use App\Models\Factory;
use App\Models\User;

class SummaryGroup extends Model
{
	protected $table = "summary_group";
	protected $fillable = array('id', 
		'name', 
		'code',
		'type',
		'year',
		'factory_id',
		'created_by',
		'updated_by', 
		'created_at',
		'updated_at',
	);

	public function capacity($type , $end_date)
	{ 
		$month_name = strtolower(date("M", strtotime($end_date)));
		$data = FactoryCapacity::where('type',$type) 
		->where('summary_group_id',$this->id)   
		->where('year',date("Y", strtotime($end_date)) ) 
		->selectRaw(DB::raw('SUM(`'.$month_name.'`) as `'.$month_name.'`')) 
		->first();  
		return  isset($data) ? $data[$month_name] : 0 ;  
	}
	public function productionPlan($type , $end_date ){ 
		 $month_name = strtolower(date("M", strtotime($end_date)));
		  $data = ProductionPlans::where('type',$type) 
		  ->where('summary_group_id',$this->id)  
		  ->where('year',date("Y", strtotime($end_date)) ) 
		  ->selectRaw(DB::raw('SUM(`'.$month_name.'`) as `'.$month_name.'`'))  
		  ->first();  
		   return  isset($data) ? $data[$month_name] : 0 ; 
	}
	public function productionTarget($type , $end_date ){
		 
		$month_name = strtolower(date("M", strtotime($end_date)));
		   $data = ProductionTarget::where('type',$type)  
	 		->where('year',date("Y", strtotime($end_date)) ) 
			->where('summary_group_id',$this->id) 
			->selectRaw(DB::raw('SUM(`'.$month_name.'`) as `'.$month_name.'`'))   
			->first();  
		return   $data[$month_name] ? $data[$month_name] : 0 ;  
	}

	public function getUomAttribute()
	{
		return  0 ;
	}

	public function aProduction($type,$request_data , $filter_time)
	{
	 
		if($request_data['start_date']){
			$query =   Manufacturer::where('summary_group_id',$this->id);
			if($request_data['factory_id']){
				//$query->where('factory_id', $request_data['factory_id'] );
			} 
			 
			if($request_data['start_date']  && $request_data['end_date'] && $filter_time == 'current'){
				$query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]); 
			} 
			
			if($request_data['start_date_previous']  && $request_data['end_date_previous'] &&  $filter_time == 'previous'){
				$query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date_previous'])), date('Y-m-d', strtotime($request_data['end_date_previous']))]); 
			} 
			 
			if($type == 'gnh'){
				return $query->sum('production_quantity_gnh'); 
			}else{
				return $query->sum('production_quantity_oth'); 
			} 
		}else{
			return 0 ;
		}
	}
	public function aDelivery($type,$request_data, $filter_time)
	{
		$query =   Delivery::where('summary_group_id',$this->id);
		if($request_data['factory_id']){
			$query->where('factory_id', $request_data['factory_id'] );
		} 
		if($request_data['start_date']  && $request_data['end_date'] && $filter_time == 'current'){
			$query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]); 
		} 
		
		if($request_data['start_date_previous']  && $request_data['end_date_previous'] &&  $filter_time == 'previous'){
			$query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date_previous'])), date('Y-m-d', strtotime($request_data['end_date_previous']))]); 
		} 
		$result =  $query->sum('delivery_qty');  
		return $result  ;  
	}
	public function aProductionPrevious($type,$request_data)
	{
		$query =   Manufacturer::where('summary_group_id',$this->id);
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
		$query =   Delivery::where('summary_group_id',$this->id);
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
		$query =   Manufacturer::where('summary_group_id',$this->id);
		if($request_data['factory_id']){
			$query->where('factory_id', $request_data['factory_id'] );
		} 
		if($request_data['start_date']  && $request_data['end_date']){
			$year_start = date("Y",strtotime($request_data['start_date'] )).'-01-01';
			$query->whereBetween('date', [date('Y-m-d', strtotime($year_start )), date('Y-m-d', strtotime($request_data['end_date']))]);  
		} 
 
		if($type == 'gnh'){
			return $query->sum('production_quantity_gnh')/date("m",strtotime($request_data['start_date'] ));   
		}else{
			return $query->sum('production_quantity_oth')/date("m",strtotime($request_data['start_date'] ));  
		};  
	}
    //AVG Production 
	public function  aDeliveryAvg($type,$request_data)
	{

		$query =   Delivery::where('summary_group_id',$this->id);
		if($request_data['factory_id']){
			$query->where('factory_id', $request_data['factory_id'] );
		} 
		if($request_data['start_date']  && $request_data['end_date']){
			$year_start = date("Y",strtotime($request_data['start_date'] )).'-01-01';
			$query->whereBetween('date', [date('Y-m-d', strtotime($year_start))  , date('Y-m-d', strtotime($request_data['end_date']))]);  
		} 
	 
		$result =  $query->sum('delivery_qty')/date("m",strtotime($request_data['end_date'] ));  
		return $result  ;

	}

      //Production Plan
	public function  getProductionPlanGNHAttribute()
	{
		return   0 ;
	}
 

}
 