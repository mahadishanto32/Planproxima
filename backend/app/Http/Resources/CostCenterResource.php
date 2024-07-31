<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CostCenterResource extends JsonResource
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
            "id" => $this->id ,   
            "controlling_area" => $this->controlling_area ,
            "company_code" => $this->company_code ,
            "name"  => $this->name , 
            "cost_code" => $this->cost_code,
            "order_type" => $this->order_type ,
            "description" => $this->description ,
              
        ];
    }
}
