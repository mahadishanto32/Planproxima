<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManufacturerResource extends JsonResource
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
            "created_by" => $this->created_by ,
            "updated_by" => $this->updated_by ,
            "created_at" => $this->created_at ,
            "updated_at" => $this->updated_at , 
            "pg_code" => $this->pg_code ,
            "cost_code" => $this->costjoin ,
            "delivery" => $this->delivery( $request ),
            "summarygroupjoin" => $this->summarygroupjoin , 
            "date"  => $this->date , 
            "product_id" => $this->product_id,
            "productjoin" => $this->productjoin, 
            "production_quantity_gnh" => $this->production_quantity_gnh ,
            "production_quantity_oth" => $this->production_quantity_oth , 
            "remarks" => $this->remarks,
            "userjoin" => $this->userjoin , 
            "factoryjoin" => $this->factoryjoin , 
        ];
    }
}
