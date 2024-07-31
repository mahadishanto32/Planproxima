<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource; 
class ReportFilesResource extends JsonResource
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
            "report_id" => $this->report_id, 
            "file_name" => $this->file_name, 
            "file_caption" => $this->file_caption, 
            "file_type" => $this->file_type, 
            "created_at" => $this->created_at, 
            "updated_at" => $this->updated_at 
        ];
    }
}
