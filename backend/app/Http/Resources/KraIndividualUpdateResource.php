<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KraIndividualUpdateResource extends JsonResource
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
            "users" => $this->users,
            "kra_updated" => $this->kra_updated , 
            "dept_upload_kra" => $this->dept_upload_kra,
            "upload_kra" => $this->upload_kra , 
            "kra_due" =>  $this->kra_due 
        ];
    }
}
