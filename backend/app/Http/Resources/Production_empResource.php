<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Production_empResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id, 
            "factory_id" => $this->factory_id, 
            "product_id" => $this->product_id, 
            "week" => $this->week, 
            "month" => $this->month, 
            "year" => $this->year, 
            "deptjoin" => $this->deptjoin, 
            "projoin" => $this->projoin, 
            "number_of_join" => $this->number_of_join, 
            "number_of_resig" => $this->number_of_resig, 
            "begining_emp" => $this->begining_emp, 
            "ending_emp" => $this->ending_emp, 
            "remarks" => $this->remarks, 
            "user_id" => $this->user_id, 
            "active" => $this->active, 
            "created_at" => $this->created_at, 
            "updated_at" => $this->updated_at  
        ];
    }
}
