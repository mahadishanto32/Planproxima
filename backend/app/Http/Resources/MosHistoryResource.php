<?php

namespace App\Http\Resources;
use App\Models\MosDataLog;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AchievementHistoryResource;
class MosHistoryResource extends JsonResource
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
            "kra_id" => $this->kra_id, 
            "kpi_id" => $this->kpi_id, 
            "mos_name" => $this->mos_name, 
            "mos_calculation" => $this->mos_calculation, 
            "weightage" => $this->weightage, 
            "isvalorper" => $this->isvalorper,  
            "filter_month" => $request->month,  
            "achievement" =>AchievementHistoryResource::collection(MosDataLog::where('mos_id',$request->id)->where('permission_months',$request->month)->get())
        ];
    }
}
