<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductionTargetResource extends JsonResource
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
            "production_target" => ($this->jan + $this->feb + $this->mar + $this->apr + $this->may + $this->jun + $this->jul + $this->aug + $this->sep + $this->oct + $this->nov + $this->dec ), 
            "material_code" => $this->material_code , 
            "productjoin" => $this->productjoin , 
            "summarygroupjoin" => $this->summarygroupjoin , 
            "material_code" => $this->material_code , 
            "created_by" => $this->created_by ,
            "updated_by" => $this->updated_by ,
            "created_at" => $this->created_at ,
            "updated_at" => $this->updated_at 
        ];
    }
}
