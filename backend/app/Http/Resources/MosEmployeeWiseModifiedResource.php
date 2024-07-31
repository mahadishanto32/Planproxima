<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MosdataResource;
use App\Models\KPI; 
use App\Models\MosData;
class MosEmployeeWiseModifiedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        $targetHalf = $this->total_targetNew('target' , 'first' , $request);
        $totaltarget = $this->total_target('target' , $request);
        $weightageCustome = $this->weightageCalculation($this->weightage ,$targetHalf,$totaltarget);
        $achievement = $this->total_achievementNew('achievement' , 'first' , $request);
        return [
            // "test" => $this->weightage.'---'.$targetHalf.'---'.$totaltarget,
            "id" => $this->id ,
            "kra_id" => $this->kra_id,
            "kpi_id" => $this->kpi_id,
            //"totaltarget" => $totaltarget ,
            "weightage" => $weightageCustome,
            "kpi_name" =>  KPI::find($this->kpi_id )->kpi_name,
            "mos_name" => $this->mos_name,
            "achievement" => $achievement,
            "target" => $targetHalf,
            "score" => $this->total_scoreNew($targetHalf , $achievement , $weightageCustome),//$this->total_scoreNew($targetHalf , $achievement , $weightageCustome),
            "target_data" =>  $request->type == 'confirmation' ? MosData::where('type', 'target')->where('mos_id', $this->id)->first():[] ,
            "achv_data" =>   $request->type == 'confirmation' ? MosData::where('type', 'achievement')->where('mos_id', $this->id)->first():[] ,
            //"mostargetjoin" => New MosdataResource($this->mostargetjoin($request)),
            //"mosmodulejoin" => New MosdataResource($this->mosmodulejoin($request)),
        ];
    }
}
