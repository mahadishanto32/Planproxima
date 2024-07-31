<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// DailyTaskBreakDownResource
class DailyTaskBreakDownResource extends JsonResource
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
            "daily_schedules_id" => $this->daily_schedules_id ,   
            "department" => $this->department,   
            "department_list" => $this->department_list,   
            "duration" => $this->duration,   
            "project" => $this->project,   
            "project_id" => $this->project_id,   
            "schedule_details" => $this->schedule_details,   
            "schedule_type_id" => $this->schedule_type_id,   
            "user_id" => $this->user_id ,  
            "factory_formatjoin" => $this->factory_formatjoin,
            "task_type" =>  $this->task_type, 
            "task" =>  $this->task, 
            "status" =>  $this->status, 
            "top_priority" =>  $this->top_priority, 
            "start_time" => (
                !is_null($this->start_time) && strtotime($this->start_time) !== false
                ? date("h:i A", strtotime($this->start_time))
                : date("h:i A", strtotime("20:30:00"))
            ),
            "end_time" => (
                !is_null($this->end_time) && strtotime($this->end_time) !== false
                ? date("h:i A", strtotime($this->end_time))
                : date("h:i A", strtotime("17:00:00"))
            ),
            "work_type" => $this->work_type,
            "created_at" => $this->created_at , 
            "updated_at" => $this->updated_at  
        ];
    }
}
