<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KpiTreeResource extends JsonResource
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
            "dept_id" => $this->dept_id, 
            "kra_id" => $this->kra_id, 
            "kra_name" => $this->kra_name,
            "kra_weight" => $this->kra_weight, 
            "kpi_weight" => $this->kpi_weight,
            "kpi_name" => $this->kpi_name,
            "year" => $this->year,
            "mos" => $this->mos
        ];
    }
}
