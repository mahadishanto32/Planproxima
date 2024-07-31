<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MosdataResource;
use App\Models\KPI; 
use App\Models\MosData;
class MosEmployeeWiseResource extends JsonResource
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
            "kra_id" => $this->kra_id ,
            "kpi_id" => $this->kpi_id ,
            "weightage" => $this->weightage ,
            "kpi_name" =>  KPI::find($this->kpi_id )->kpi_name,
            "mos_name" => $this->mos_name,
            "achievement" => $this->total_achievement('achievement' ,$request),
            "target" => $this->total_target('target' ,$request),
            "score" => $this->total_score('score' , $request),
            "target_data" =>  $request->type == 'confirmation' ? MosData::where('type', 'target')->where('mos_id', $this->id)->first():[] ,
            "achv_data" =>   $request->type == 'confirmation' ? MosData::where('type', 'achievement')->where('mos_id', $this->id)->first():[] ,
            //"mosachievementjoin" => New MosdataResource($this->mosachievementjoin($request)),
            //"mostargetjoin" => New MosdataResource($this->mostargetjoin($request)),
            //"mosmodulejoin" => New MosdataResource($this->mosmodulejoin($request)),
        ];
    }
}
