<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductionWastageDaftResource extends JsonResource
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
            "comp_code"  => $this->comp_code ,
            "unit_code"  => $this->unit_code ,
            "product_code"  => $this->product_code ,
            "date" => $this->date ,
            "target_qty"  => $this->target_qty , 
            "production_quantity_gnh"  => $this->production_quantity_gnh ,
            "production_quantity_oth"  => $this->production_quantity_oth ,
            "delivery_qty"  => $this->delivery_qty ,
            "consumtion"  => $this->consumtion ,
            "consumtion_value" => $this->consumtion_value,
            "wastage"  => $this->wastage ,
            "wastage_value" => $this->wastage_value,
            "return"  => $this->return ,
            "type"  => $this->type ,
            "status"  => $this->status ,
            "sap_file_id"  => $this->sap_file_id ,
            "remarks"  => $this->remarks ,
            "error_note"  => $this->error_note ,
            "created_by"  => $this->created_by ,
            "updated_by"  => $this->updated_by ,
            "created_at"  => $this->created_at ,
            "updated_at"  => $this->updated_at ,
        ];
    }
}
