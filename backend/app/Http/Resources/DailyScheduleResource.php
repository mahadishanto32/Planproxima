<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyScheduleResource extends JsonResource
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
            "user_id" =>  $this->user_id, 
            "userjoin" =>  $this->userjoin, 
            "kra_id" =>  $this->kra_id,
            "krajoin" => $this->krajoin, 
            "role_id" => $this->role_id, 
            "kpi_id" =>  $this->kpi_id, 
            "kpijoin" => $this->kpijoin, 
            "mos_id" =>  $this->mos_id, 
            "mosjoin" => $this->mosjoin, 
            "deptjoin" => $this->deptjoin, 
            "factory_formatjoin" => $this->factory_formatjoin, 
            "schedule_items" =>  $this->schedule_items(),
            "comments" => $this->comments(), 
            "date" =>  $this->date, 
            "start_time" =>  $this->start_time, 
            "end_time" =>  $this->end_time, 
            "task" =>  $this->task,    
            "status" =>  $this->status,
            "top_priority" =>  $this->top_priority,
            "created_at" => $this->created_at , 
            "updated_at" => $this->updated_at  
        ];
    }
}
