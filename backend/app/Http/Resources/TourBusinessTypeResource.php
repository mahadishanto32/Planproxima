<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TourBusinessTypeResource extends JsonResource
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
            "title" => $this->title,
            "description" => $this->description,
            "created_by" => $this->created_by,
            "dept_id" => $this->dept_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "status" => $this->status
        ];
    }
}
