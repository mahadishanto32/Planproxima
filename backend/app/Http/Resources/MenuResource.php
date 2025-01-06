<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            "parent_id" => $this->parent_id, 
            "menu_name" => $this->menu_name , 
            "menu_url" => $this->menu_url ,
            "sort"  => $this->sort ,  
            "status" => $this->status ,  
            "remarks" => $this->remarks,
            "menu_hints" => $this->menu_hints , 
            "sub_menu" => $this->submenu ,
              
        ];
    }
}
