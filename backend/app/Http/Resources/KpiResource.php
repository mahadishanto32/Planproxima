<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MOS; 
use App\Http\Resources\MosResource;
class KpiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $MOSQuery = MOS::where('kpi_id',  $this->id);
        if($request['checkMos']){
            $MOSQuery->where('m_o_s.rep_id' , $request->id);
        }
        $MOS = $MOSQuery->get();
        return [
            "id" => $this->id ,
            "kpi_name" => $this->kpi_name , 
            "kpi_weight" => $this->kpi_weight ,
            "dept_id" => $this->dept_id ,
            "kra_id" => $this->kra_id , 
            "kra_id" => $this->kra_id , 
            "employee" => $this->krauser,             
            //"mosnumber" => $this->mosnumber ,
            "mosjoin"  =>  MosResource::collection($MOS) ,
            "sssss"  =>  $request['checkMos'] ,
        ];
    }
}
