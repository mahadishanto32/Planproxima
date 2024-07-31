<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FactoryStandardResource extends JsonResource
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
            "cost_center_id" => $this->cost_center_id ,  
            "product_group" => $this->product_group(),
            "cost_center" => $this->cost_center ,  
            "gl_text" => $this->gl_text ,  
            "gl_code" => $this->gl_code , 
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
            "cost_amount" => $this->cost_amount ,  
            "type" => $this->type ,  
            'report_type' => $this->report_type, 
            "year" => $this->year ,   
            "created_by" => $this->created_by ,
            "updated_by" => $this->updated_by ,
            "created_at" => $this->created_at ,
            "updated_at" => $this->updated_at 
        ];
    }
}
