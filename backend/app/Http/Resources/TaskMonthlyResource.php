<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskMonthlyResource extends JsonResource
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
            "dates" =>date('Y , m , d' , strtotime($this->date)) ,
            // "date" => date('d' , strtotime($this->date)),
            "customData" =>$this->customData()
        ];
    }
}
