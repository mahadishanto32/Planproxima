<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDelivaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
      public $filter;
       
        public function __construct($resource, $filter ) {
            // Ensure we call the parent constructor
            parent::__construct($resource);
            $this->resource = $resource;
            $this->filter = $filter;
         
        }
       
    public function toArray($request)
    {
        
        return [
            "id" => $this->id ,   
            "code" => $this->code , 
            "name" => $this->name,  
            "gnh_actual_production" => $this->aProduction('gnh',$this->filter,'current'),
            "gnh_actual_production_previous" => $this->aProduction('gnh',$this->filter,'previous'),

            "gnh_production_target" => $this->productionTarget('gnh' ,$this->filter['end_date']) ,
            "gnh_production_target_previous" => $this->productionTarget('gnh' ,$this->filter['end_date_previous']) , 

            "gnh_actual_delivery" => $this->aDelivery('gnh',$this->filter,'current'),
            "gnh_actual_delivery_previous" => $this->aDelivery('gnh',$this->filter,'previous'),
            
            "gnh_actual_production_avg" => $this->aProductionAvg('gnh',$this->filter ),
            "gnh_actual_delivery_avg" => $this->aDeliveryAvg('gnh',$this->filter ),

            "oth_actual_production" => $this->aProduction('oth',$this->filter,'current'),
            "oth_actual_production_previous" => $this->aProduction('oth',$this->filter,'previous'),

            "oth_production_target" => $this->productionTarget('oth' ,$this->filter['end_date']) ,
            "oth_production_target_previous" => $this->productionTarget('oth' ,$this->filter['end_date_previous']) , 

            "oth_actual_delivery" => $this->aDelivery('oth',$this->filter,'current'),
            "oth_actual_delivery_previous" => $this->aDelivery('oth',$this->filter,'previous'),
            "oth_actual_production_avg" => $this->aProductionAvg('oth',$this->filter ),
            "gnh_actual_delivery_avg" => $this->aDeliveryAvg('oth',$this->filter ),
            //aDeliveryAvg
            "gnh_production_plan" => $this->productionPlan('gnh' ,$this->filter['end_date']) ,
            "oth_production_plan" => $this->productionPlan('oth' ,$this->filter['end_date']) ,
            
            "gnh_capacity" => $this->capacity('gnh' ,$this->filter['end_date']) ,
            "oth_capacity" => $this->capacity('oth' ,$this->filter['end_date']) ,
            'filter' =>$this->filter //here
        ];
    }
  
}
