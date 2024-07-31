<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MOS; 
use App\Http\Resources\MosEmployeeWiseResource;
use App\Http\Resources\MosEmployeeWiseModifiedResource;
class KpiEmployeeWiseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $MOS=MOS::where('kpi_id',  $this->id)->get();
        return [
            "id" => $this->id ,
            "kra_id" => $this->kra_id , 
            "kpi_name" => $this->kpi_name , 
            "kpi_weight" => $this->kpi_weight ,
            "calculating_wattage" => $this->kpi_weight ,
            "dept_id" => $this->dept_id ,
            //"kra_id" => $this->kra_id , 
            //"kra_id" => $this->kra_id , 
            //"mosnumber" => $this->mosnumber ,
            "mos" => $request->year!=2023?
            MosEmployeeWiseResource::collection($MOS)
            :MosEmployeeWiseModifiedResource::collection($MOS) 
        ];
    }
}
