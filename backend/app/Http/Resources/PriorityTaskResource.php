<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriorityTaskResource extends JsonResource
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
            "tasks" => $this->tasks(),
            "dept_id" =>$this->dept_id,
            "quarter_id" =>$this->quarter_id,
            "status" =>$this->status,
            "dept" => $this->dept(),
            "user" => $this->user(),
            "year" =>$this->year, 
            "created_by" => $this->created_by , 
            "created_at" => $this->created_at ,
            "updated_at" => $this->updated_at , 
        ];
    }
}
