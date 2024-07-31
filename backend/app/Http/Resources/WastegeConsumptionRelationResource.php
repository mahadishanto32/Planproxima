<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WastegeConsumptionRelationResource extends JsonResource
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
            "group_name" => $this->group_name, 
            "scrap_material" => $this->scrap_material, 
            "plant" => $this->plant, 
            "uom" => $this->uom, 
            "consumption" => $this->consumption() 
        ];
    }
}
