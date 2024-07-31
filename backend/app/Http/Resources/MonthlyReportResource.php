<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ReportFilesResource;
use App\Models\MonthlyReportFile;
class MonthlyReportResource extends JsonResource
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
            "id" => $this->id, 
            "dept_id" => $this->dept_id, 
            "kra_id" => $this->kra_id, 
            "kpi_id" => $this->kpi_id, 
            "custom_kra" => $this->custom_kra, 
            "task_name" => $this->task_name, 
            "monthly_work" => $this->monthly_work, 
            "topforcurrentmonth" => $this->topforcurrentmonth, 
            "man_power_efficiency" => $this->man_power_efficiency, 
            "valueadd" => $this->valueadd, 
            "reason" => $this->reason, 
            "month" => $this->month, 
            "year" => $this->year, 
            "date" => $this->date, 
            "worktype" => $this->worktype, 
            "user_id" => $this->user_id, 
            "krajoin" =>$this->krajoin,  
            "kpijoin" =>$this->kpijoin, 
            "files" =>  ReportFilesResource::collection( MonthlyReportFile::where('report_id',  $this->id)->get()),
            "created_at" => $this->created_at, 
            "updated_at" => $this->updated_at   
        ];
    }
}
