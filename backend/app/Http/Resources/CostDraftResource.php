<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CostDraftResource extends JsonResource
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
            "gl_code" => $this->gl_code ,
            "cost_center" => $this->cost_center ,
            "error_note" => $this->error_note ,
            "remarks" => $this->remarks ,
            "date"  => $this->date ,  
            "cost" => $this->cost ,  
            "remarks" => $this->remarks
        ];
    }
}
