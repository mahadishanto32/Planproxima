<?php
namespace App\Imports;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\Factory;
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class ManufacturerImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    { 
        $product = Product::where('material_code',$row['products_code'])->first(); 
        $factory = Factory::where('fac_code',$row['factory_code'])->first()->toArray();  
            return new Manufacturer([
                'product_id'     => $product->id ? $product->id : '' ,
                'production_target'  => $row['production_target'], 
                'pg_code' =>  $product->product_group ?  $product->product_group : '', 
                'production_quatity_gnh' => $row['actual_production'],
                'factory_code' => $row['factory_code'],
                'date' => $row['date'] ,
                'factory_id' => $factory['id'] ? $factory['id'] : '' ,
                'actual_delivery' => $row['actual_delivery'], 
                'remarks' => $row['remarks'], 
                'created_by' => Auth::user()->id ,  
                'updated_by' => Auth::user()->id ,
             ]);
    }  
}
