<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Follow_upResource extends JsonResource
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
            "date" => $this->date, 
            "details" => $this->details, 
            "dept_id" => $this->dept_id, 
            "firstremind" => $this->firstremind, 
            "secondremind" => $this->secondremind, 
            "user_id" => $this->user_id, 
            "users" => $this->users, 
            "status" => $this->status, 
            "active" => $this->active, 
            "dmdactive" => $this->dmdactive, 
            "complete" => $this->complete, 
            "reminderflag" => $this->reminderflag, 
            "reminderflagid" => $this->reminderflagid, 
            "reminderdate" => $this->reminderdate, 
            "remindertime" => $this->remindertime,
            "deptsjoin" => $this->deptsjoin(),
            "created_at" => $this->created_at
        ];
    }
}
