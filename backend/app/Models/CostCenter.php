<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model; 
use App\Models\ProductionTarget;
use App\Models\Manufacturer;
use App\Models\Cost;
use App\Models\FactoryStandard;
use DB;
class CostCenter extends Model
{
    protected $table = "cost_centers";
    protected $fillable = array(
        'id', 
        'controlling_area', 
        'company_code',
        'name',
        'cost_code',
        'summary_group_id' ,
        'order_type' ,
        'description'
    ); 
    
   
    public function standardCost( $type , $gl_code, $request_data ){  
      if(date('Y' , strtotime($request_data['start_date'])) <= 2023 && (date('m' , strtotime($request_data['start_date'])) <= 6)){
        
        $data = FactoryStandard::selectRaw(DB::raw('SUM(factory_standards.cost_amount) as cost_amount'))
        ->join('cost_centers','cost_centers.cost_code','=','factory_standards.cost_center')
        ->where('factory_standards.type', $type)
        ->where('factory_standards.report_type', 'yearly')
        ->where('factory_standards.gl_code', $gl_code )  
        ->where('cost_centers.summary_group_id', $request_data['summary_group_id']) 
        ->where('year', date('Y', strtotime($request_data['start_date']))) 
        ->first(); 
        return  $data->cost_amount ?  $data->cost_amount : 0 ; 
      }else{

        $month =  strtolower(date('M' , strtotime($request_data['start_date']))) ;
        $where =  'factory_standards.'.$month ;
        $data = FactoryStandard::selectRaw(DB::raw('SUM('. $where.') as cost_amount'))
        ->join('cost_centers','cost_centers.cost_code','=','factory_standards.cost_center')
        ->where('factory_standards.type', $type)
        ->where('factory_standards.report_type', 'monthly')
        ->where('factory_standards.gl_code', $gl_code )  
        ->where('cost_centers.summary_group_id', $request_data['summary_group_id']) 
        ->where('year', date('Y', strtotime($request_data['start_date']))) 
        ->first(); 
        return  $data->cost_amount ?  $data->cost_amount : 0 ;
      } 
      
       
      
     } 
     public function cost($cost_gl_id ,  $type , $request_data , $current_or_previous){
      
        $cost =Cost::select(DB::Raw('SUM(costs.cost) as cost'));
        if( $current_or_previous =='current'){
            if($request_data['start_date']  && $request_data['end_date']){
             $cost->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
            }
        }else if($current_or_previous =='previous'){
            if($request_data['start_date_previous']  && $request_data['end_date_previous']){
                $cost->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date_previous'])), date('Y-m-d', strtotime($request_data['end_date_previous']))]);  
             }
        }else if($current_or_previous =='average'){
            if($request_data['start_date']  && $request_data['end_date']){
                $year = date('Y', strtotime($request_data['end_date'])); 
                $cost->whereBetween('date', [date('Y-m-d', strtotime($year.'-01-01')), date('Y-m-d', strtotime($request_data['end_date']))]);  
             }
        }
     
        $cost->where('summary_group_id',$request_data['summary_group_id']); 
        $cost->where('cost_gl_id', $cost_gl_id); 
        $data = $cost->first();
        if($current_or_previous =='average'){
           // return $data->cost ;
            return $data->cost ? ($data->cost/date('m', strtotime($request_data['start_date']))) : 0  ;
        }else{
            return $data->cost ? $data->cost : 0  ;
        }
        
    }
 
   
}
 