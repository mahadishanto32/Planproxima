<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuPermissionResource extends JsonResource
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
            "menu_url" =>$this->menu_url , 
            "name"  =>  'tanjib',
            "view" => $this->view ? $this->view : 0,  
            "sub_menu" => $this->submenu_permission($request->id , $request->get('type')) ,
              
        ];
    }
}
