<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
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
            "pg_code" => $this->pg_code , 
            "date"  => $this->date , 
            "product_id" => $this->product_id,
            "productjoin" => $this->productjoin, 
            "delivery_qty" => $this->delivery_qty ,
            "remarks" => $this->remarks,
            "userjoin" => $this->userjoin , 
            "factoryjoin" => $this->factoryjoin , 
            "created_by" => $this->created_by ,
            "updated_by" => $this->updated_by ,
            "created_at" => $this->created_at ,
            "updated_at" => $this->updated_at ,
        ];
    }
}
