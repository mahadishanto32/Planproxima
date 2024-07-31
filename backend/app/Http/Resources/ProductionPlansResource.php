<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductionPlansResource extends JsonResource
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
            "jan" => $this->jan, 
            "feb" => $this->feb, 
            "mar" => $this->mar, 
            "apr" => $this->apr, 
            "may" => $this->may, 
            "jun" => $this->jun, 
            "jul" => $this->jul, 
            "aug" => $this->aug, 
            "sep" => $this->sep, 
            "oct" => $this->oct, 
            "nov" => $this->nov, 
            "dec" => $this->dec,
            "projoin" => $this->projoin,  
            "summary_group_id" => $this->summary_group_id, 
            "summary_groupjoin" => $this->summary_groupjoin, 
            "year" => $this->year, 
            "type" => $this->type, 
            "created_by" => $this->created_by, 
            "updated_by" => $this->updated_by, 
            "production_plan" => $this->production_plan, 
            "material_code" => $this->material_code, 
            "created_at" => $this->created_at 
        ];
    }
}
