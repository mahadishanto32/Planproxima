<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MosdataResource;
use App\Http\Resources\MosFeadbackResource;
use App\Http\Resources\KpiResource;
class MosItemResource extends JsonResource
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
            "kpi_id" => $this->kpi_id ,
            "mos_name" => $this->mos_name,
            "weightage" => $this->weightage,
            "isvalorper" => $this->isvalorper,
            "year" => $this->year,
           // "feadback" =>  MosFeadbackResource::collection($this->feadback) ,
            "feadback" => $this->feadback($request),
            "mos_calculation" => $this->mos_calculation,
            "modification_type" => $this->modification_type ,
            "modification_status" => $this->modification_status ,
            "start_date" => $this->start_date ,
            "end_date" => $this->end_date ,
            "modification_months" => json_decode($this->modification_months),
            "mostargetjoin" => New MosdataResource($this->mostargetjoin($request)),
            "mosmodulejoin" => New MosdataResource($this->mosmodulejoin($request)),
            "mosachievementjoin" => New MosdataResource($this->mosachievementjoin($request)),
            "mos_kpi" => KpiResource::collection($this->mos_kpi),
        ];
    }
}
