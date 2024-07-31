<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MOS; 
use App\Http\Resources\MosResource;
class WingResource extends JsonResource
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
            "wing_title" => $this->wing_title , 
            "status" => $this->status ,
            "dept_id" => $this->dept_id ,
            "deptjoin" => $this->deptjoin ,   
            "userjoin" => $this->userjoin , 
        ];
    }
}
