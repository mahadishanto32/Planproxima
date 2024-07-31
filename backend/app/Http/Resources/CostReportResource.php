<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class CostReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private $filter;

        public function __construct($resource, $filter ) {
            // Ensure we call the parent constructor
            parent::__construct($resource);
            $this->resource = $resource;
            $this->filter = $filter; // $apple param passed
        }
    
    public function toArray($request)
    {
        return [
            'filters'=>$this->filters,
            "id" => $this->id , 
            "gl_name" => $this->gl_name , 
            "gl_code" => $this->gl_code , 
            "type" => $this->type , 
            "cost" => $this->cost($this->id , 'gnh' ,  $this->filters ,'current' ),
            "average_cost" => $this->cost($this->id , 'gnh' ,  $this->filters ,'average' ),
            "cost_previous" => $this->cost($this->id , 'gnh' ,  $this->filters ,'previous' ),
            "standard_gnh" =>  $this->standardCost('gnh' , $this->gl_code  , $this->filters ),
            "standard_oth" => $this->standardCost('oth' , $this->gl_code  , $this->filters ),
            "group_id" => $this->group_id
            //`id`, `gl_name`, `gl_code`, `type`
             
        ];
    }
}
// Note:Backup Previous Code 
// "id" => $this->id , 
// "gl_name" => $this->gl_name , 
// "gl_code" => $this->gl_code , 
// "type" => $this->type , 
// "cost" => $this->cost($this->id , 'gnh' ,  $request ,'current' ),
// "average_cost" => $this->cost($this->id , 'gnh' ,  $request ,'average' ),
// "cost_previous" => $this->cost($this->id , 'gnh' ,  $request ,'previous' ),
// "standard_gnh" => $this->standardCost('gnh' , $this->gl_code  ,  $request ),
// "standard_oth" => $this->standardCost('oth' , $this->gl_code  ,  $request ),
// "group_id" => $this->group_id