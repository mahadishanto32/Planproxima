<?php

namespace App\Http\Resources;
use App\Http\Resources\MosdataResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MosTreeResourceUnassign extends JsonResource
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
            "kra_count" =>$this->count_kraun,
            "krajoin" =>$this->krajoin, 
            "kpi_count" =>$this->count_kpiun, 
            "kpijoin" =>$this->kpijoin, 
            "kpi_id" => $this->kpi_id, 
            "mos_name" => $this->mos_name, 
            "mos_calculation" => $this->mos_calculation, 
            "weightage" => $this->weightage, 
            "isvalorper" => $this->isvalorper,
            "mosachievementjoin" => New MosdataResource($this->mosachievementjoin($request)),
            "mostargetjoin" => New MosdataResource($this->mostargetjoin($request)),
            "mosmodulejoin" => New MosdataResource($this->mosmodulejoin($request)),
            "kra_checked" => false,
            "kpi_checked" => false,
            "mos_checked" => false,    
            "kra_weight_assign" => 0,
            "kpi_weight_assign" => 0, 
        ];
    }
}
