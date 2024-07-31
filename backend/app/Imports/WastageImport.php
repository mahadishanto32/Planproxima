<?php
namespace App\Imports;
use App\Models\Wastage;
use App\Models\Product;
use App\Models\Area;
use App\Models\Factory;
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class WastageImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    { 
        $product = Product::where('material_code',$row['products_code'])->first(); 
        $area = Area::where('area_code',$row['area_code'])->first(); 
        $factory = Factory::where('fac_code',$row['factory_code'])->first()->toArray();  
            return new Wastage([
                'product_id'     => $product->id ? $product->id : '' , 
                'pg_code' =>  $product->product_group ?  $product->product_group : '', 
                'area_id' =>  $area['id'] ? $area['id'] : '0',  
                'actual_wastage'  => $row['actual_wastage'], 
                'factory_code' => $row['factory_code'],
                'date' => $row['date'] ,
                'factory_id' => $factory['id'] ? $factory['id'] : '' , 
                'remarks' => $row['remarks'], 
                'created_by' => Auth::user()->id ,  
                'updated_by' => Auth::user()->id ,
             ]);
    }  
}
