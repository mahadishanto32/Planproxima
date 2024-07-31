<?php
namespace App\Imports;
use App\Models\Cost;
use App\Models\Product;
use App\Models\Area;
use App\Models\CostGl;
use App\Models\Factory;
use App\Models\CostCenter;
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class CostImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    { 
       // $product = Product::where('material_code',$row['products_code'])->first(); 
     
       if($row){ 
        $costgl = CostGl::where('gl_code',$row['gl_code'])->first();
        $cost_center = CostCenter::where('cost_code',$row['cost_center'])->first();
        
        $factory = Factory::where('fac_code',$row['factory_code'])->first();  
         
            return new Cost([
                //'product_id'     => $product->id ? $product->id : '' , 
                'cost_center_id' =>  $cost_center ?  $cost_center->id : 0, 
                'cost_gl_id' =>  $costgl['id'] ? $costgl['id'] : '0', 
                'summary_group_id' =>  $cost_center->summary_group_id ? $cost_center->summary_group_id : 0 ,  
                'cost'  => $row['cost'], 
                'factory_code' => $row['factory_code'],
                'date' => date('Y-m-d H:i:s', strtotime($row['date'])) ,
                'factory_id' => $factory['id'] ? $factory['id'] : '' , 
                'remarks' => $row['remarks'], 
                'created_by' => Auth::user()->id ,  
                'updated_by' => Auth::user()->id ,
             ]);
        }
    }  
}
