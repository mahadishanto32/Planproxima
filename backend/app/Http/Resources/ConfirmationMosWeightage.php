<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MOS; 
use App\Http\Resources\MosEmployeeWiseResource;
class ConfirmationMosWeightage extends JsonResource
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
            //"calculating_wattage" => $this->kpi_weight ,
            //"dept_id" => $this->dept_id ,
            //"kra_id" => $this->kra_id , 
            //"kra_id" => $this->kra_id , 
            //"mosnumber" => $this->mosnumber ,
            "mos"  =>  MosEmployeeWiseResource::collection( MOS::where('kra_id',  $this->id)->get()) 
        ];
    }
}
