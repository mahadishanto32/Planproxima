<?php

namespace App\Http\Resources;
use App\Models\KPI;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\KpiResource;
use App\Http\Resources\KpiEmployeeWiseResource;
class KraEmployeeWiseResource extends JsonResource
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
            "kra_name" => $this->kra_name , 
            "kra_weight" => $this->kra_weight ,  
            "year" => $this->year ,  
            //"calculating_wattage" => ($this->kra_weight/100)*70 ,
            "kpi" =>  KpiEmployeeWiseResource::collection( KPI::where('kra_id',  $this->id)->get()) 
        ];
    }
}
