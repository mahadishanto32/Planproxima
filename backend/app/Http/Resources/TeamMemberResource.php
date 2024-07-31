<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id ,
            "teamJoin" => $this->teamJoin, 
            "userJoin" => $this->userJoin,
            "wingJoin" => $this->wingJoin,//New WingResource($this->wingJoin) ,
            "deptjoin" => $this->deptjoin,   
        ];
    }
}
