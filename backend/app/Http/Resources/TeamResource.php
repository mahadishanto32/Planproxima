<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\WingResource;
class TeamResource extends JsonResource
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
            "team_name" => $this->team_name , 
            "userJoin" => $this->userJoin ,
            "wingJoin" => New WingResource($this->wingJoin) ,
            "deptjoin" => $this->deptjoin ,   
        ];
    }
}
