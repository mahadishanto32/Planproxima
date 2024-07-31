<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectAPIResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id ,  
            "name" => $this->name ,
            "text" => $this->name ,
            "dept_id" => $this->dept_id ,
            "wing_id" => $this->wing_id ,
            "updated_at" => $this->updated_at , 
        ];
    }
}
