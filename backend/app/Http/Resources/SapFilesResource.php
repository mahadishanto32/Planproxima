<?php

namespace App\Http\Resources;
use App\Models\KPI;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\KpiResource;
class SapFilesResource extends JsonResource
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
            "file_name" => $this->file_name, 
            "comp_code" => $this->comp_code, 
            "created_by" => $this->created_by,
            "created_at" => $this->created_at,
            "updated_by" => $this->updated_by,
            "updated_at" => $this->updated_at,
            "userjoin" => $this->userjoin,
            "note" => $this->note,    
            "date" => $this->date  
        ];
    }
}
