<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WastageResource extends JsonResource
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
            "created_by" => $this->created_by ,
            "updated_by" => $this->updated_by ,
            "created_at" => $this->created_at ,
            "updated_at" => $this->updated_at ,
            "factory_code" => $this->factory_code ,
            "pg_code" => $this->pg_code ,
            "cost_code" => $this->costjoin() ,
            "date"  => $this->date , 
            "productjoin" => $this->productjoin(),
            "actual_wastage" => $this->actual_wastage ,
            "wastage_value" => $this->wastage_value,
            "area_id" => $this->area_id , 
            "remarks" => $this->remarks,
            "userjoin" => $this->userjoin , 
            "factoryjoin" => $this->factoryjoin , 
            "areajoin" => $this->factoryjoin , 
        ];
    }
}
