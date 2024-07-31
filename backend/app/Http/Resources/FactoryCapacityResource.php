<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FactoryCapacityResource extends JsonResource
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
            "jan" => $this->jan ,
            "feb" => $this->feb ,
            "mar" => $this->mar ,
            "apr" => $this->apr ,
            "may" => $this->may ,
            "jun" => $this->jun ,
            "jul" => $this->jul ,
            "aug" => $this->aug ,
            "sep" => $this->sep ,
            "oct" => $this->oct ,
            "nov" => $this->nov ,
            "dec" => $this->dec , 
            "dec" => $this->dec , 
            "year" => $this->year , 
            "type" => $this->type , 
            "summary_groupjoin" => $this->summary_groupjoin , 
            "total_capacity" => $this->total_capacity ,  
            "created_by" => $this->created_by ,
            "updated_by" => $this->updated_by ,
            "created_at" => $this->created_at ,
            "updated_at" => $this->updated_at 
        ];
    }
}
