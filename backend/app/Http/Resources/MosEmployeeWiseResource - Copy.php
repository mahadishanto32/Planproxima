<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MosdataResource;
class ConfirmationMosEmployeeWiseResource extends JsonResource
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
            "mos_name" => $this->mos_name ,
            "achievement" => $this->total_achievement('achievement'),
            "target" => $this->total_target('target'),
            "score" => $this->total_score('score'),
            //"calculating_wattage" => ($this->weightage/100)*70 ,
            //"mosachievementjoin" => New MosdataResource($this->mosachievementjoin($request)),
            //"mostargetjoin" => New MosdataResource($this->mostargetjoin($request)),
            //"mosmodulejoin" => New MosdataResource($this->mosmodulejoin($request)),
        ];
    }
}
