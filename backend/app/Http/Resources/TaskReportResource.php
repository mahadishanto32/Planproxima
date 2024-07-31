<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\DailySchedule;
class TaskReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $to_date = date('Y-m-d', strtotime($request['date'])) ;
        return [ 
            "id" => $this->id , 
            "employee_id" => $this->employee_id , 
            "designation" => $this->designation ,
            "user_name" => $this->user_name, 
            "employee_id" => $this->employee_id , 
            "designation" => $this->designation , 
            "dept_name" => $this->dept_name , 
            "task_status" =>  DailySchedule::where('user_id', $this->id )->where('date',$to_date)->count()
        ];
    }
}
