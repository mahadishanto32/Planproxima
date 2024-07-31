<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TourPlanResource extends JsonResource
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
            "user_id" => $this->user_id,
            "point" => $this->point,
            "point_id" => $this->point_id,
            "territory" => $this->territory,
            "sap_code" => $this->sap_code,
            "route_name" => $this->route_name,
            "objectives" => $this->objectives,
            "specia_objective" => $this->specia_objective,
            "contactperson" => $this->contactperson,
            "hq" => $this->hq,
            "remarks" => $this->remarks,
            "feedback" => $this->feedback,
            "status" => $this->status,
            "work_station" => $this->work_station,
            "work_with" => $this->work_with,
            "work_with_id" => $this->work_with_id,
            "date" => $this->date,
            //"userjoin" => $this->userjoin, 
            "approval" => $this->approval,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
            "approval_count" => $this->approval_count,
            //"objectiveItem" => $this->objectiveItem() ,
            'objective_id'=> $this->objective_id,
            "last_approval_date" => $this->last_approval_date,
            "designation" => $this->designation,
            "employee_id" => $this->employee_id,
            "name" => $this->name,  
            'touruser' => $this->touruser,
            'routejoin' => $this->routejoin,
            'territoryjoin' => $this->territoryjoin,
            'pointjoin' => $this->pointjoin,
            'fojoin' => $this->fojoin,
        ];
    }
}
