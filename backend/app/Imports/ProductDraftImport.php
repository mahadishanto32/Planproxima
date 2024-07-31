<?php
namespace App\Imports; 
use App\Models\ProductDraft;  
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class ProductDraftImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {  
        return new ProductDraft([  
            'plant' => $row['plant'],
            'product_group' => $row['product_group'],
            'wastage_group' => $row['wastage_group'],
            'material_code' => $row['material_code'],
            'description' => $row['description'],
            'material_group' => $row['material_group'],
            'material_type' => $row['material_type'],
            'base_unit_of_measure' => $row['base_unit_of_measure'],
            'product_type' => $row['product_type'],
            'status' => 0,
            ]);
 
    }  
}
