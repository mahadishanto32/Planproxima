<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportWastageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */


        public function __construct($resource ) {
            // Ensure we call the parent constructor
            parent::__construct($resource);
            $this->resource = $resource;
           // $this->filter = $filter; // $apple param passed
        }
    
    public function toArray($request)
    {
        return [ 
            'filters' => $this->filters,
            "actual_wastage" => $this->actualWastage( $this->filters , 'current', 'qty') , 
            "actual_wastage_amount" => $this->actualWastage( $this->filters , 'current','amount') ,     
            "uom" => $this->uom ,  
            "previous_year_average" =>  $this->previousYearAverage($this->filters),
            "current_year_average" => $this->currentYearAverage( $this->filters ),
            "group_name" => $this->group_name ,    
            "consumtion" => $this->consumtion( $this->filters , 'current' ,'qty') , 
            "consumtion_amount" => $this->consumtion( $this->filters , 'current' ,'amount') , 
            "actual_wastage_previous" => $this->actualWastage( $this->filters , 'previous', 'qty') ,
            "actual_wastage_amount_previous" => $this->actualWastage( $this->filters , 'previous', 'amount') ,
            "consumtion_previous" => $this->consumtion( $this->filters , 'previous' ,'qty'),
            "consumtion_amount_previous" => $this->consumtion( $this->filters , 'previous' ,'amount'),
        ];
    }
}
// Note:Backup Previous Code
// "actual_wastage" => $this->actualWastage( $request , 'current') ,   
// "uom" => $this->uom ,  
// "previous_year_average" =>  $this->previousYearAverage($request),
// "current_year_average" => $this->currentYearAverage( $request ),
// "group_name" => $this->group_name ,    
// "consumtion" => $this->consumtion( $request , 'current') , 
// "actual_wastage_previous" => $this->actualWastage( $request , 'previous') ,
// "consumtion_previous" => $this->consumtion( $request , 'previous') 