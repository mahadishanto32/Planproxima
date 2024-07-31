<?php
namespace App\Imports;
use App\Models\ProductionPlans; 
use App\Models\Product; 
use App\Models\Factory; 
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class ProductionPlanImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {    
        $query = Product::where('material_code',$row['material_code']); 
        $query->where('plant',$row['plant']); 
        if($row['plant'] !='1302'){
            $query->where(function($q) {
                $q->where('product_type', 'PRODUCTION')
                ->orWhere('product_type', 'production');
            });
        }
        $product =  $query->first();  
        if(!$product){
            $Factory = Factory::where('fac_code',$row['plant'])->first();
            
            // $Product = new Product();
            // $product->plant = $row['plant'];
            // $product->plant_id = $Factory->id;
            // $product->material_code = $row['material_code'];
            // $product->description = $row['description'];
            // $product->summary_group_id = $row['summary_group_id'];
            // $product->material_type = $row['material_type'];
            // $product->product_type = 'production';
            // $product->save();
            
            return ProductionPlans::first() ;
        } 
        $productionPlans = ProductionPlans::where('year',  $row['year'] )
        ->where('material_code',  $row['material_code'] )
        ->where('summary_group_id',  $product['summary_group_id'] )
        ->where('type',  strtolower($row['type']))
        ->first();

        $planData = [ 
            'summary_group_id' => $product['summary_group_id'] , 
            'year' => $row['year'],
            'type' => strtolower($row['type']),
            'production_plan' =>  $this->totat($row),  
            'material_code' =>   $row['material_code'], 
        ];

        if(isset($row['jan'])) { $planData['jan'] = $row['jan']; }
        if(isset($row['feb'])) { $planData['feb'] = $row['feb']; }
        if(isset($row['mar'])) { $planData['mar'] = $row['mar']; }
        if(isset($row['apr'])) { $planData['apr'] = $row['apr']; }
        if(isset($row['may'])) { $planData['may'] = $row['may']; }
        if(isset($row['jun'])) { $planData['jun'] = $row['jun']; }
        if(isset($row['jul'])) { $planData['jul'] = $row['jul']; }
        if(isset($row['aug'])) { $planData['aug'] = $row['aug']; }
        if(isset($row['sep'])) { $planData['sep'] = $row['sep']; }
        if(isset($row['oct'])) { $planData['oct'] = $row['oct']; }
        if(isset($row['nov'])) { $planData['nov'] = $row['nov']; }
        if(isset($row['dec'])) { $planData['dec'] = $row['dec']; }
        

        if($productionPlans){ 
            ProductionPlans::where('id', $productionPlans->id)
                ->update($planData);
            return $productionPlans ;
        
        }else{
            return new ProductionPlans($planData);
        }  
      
    }  
    public function totat($row){
        return (
            (isset($row['jan']) ? $row['jan'] : 0) +
            (isset($row['feb']) ? $row['feb'] : 0) +
            (isset($row['mar']) ? $row['mar'] : 0) +
            (isset($row['apr']) ? $row['apr'] : 0) +
            (isset($row['may']) ? $row['may'] : 0) +
            (isset($row['jun']) ? $row['jun'] : 0) +
            (isset($row['jul']) ? $row['jul'] : 0) +
            (isset($row['aug']) ? $row['aug'] : 0) +
            (isset($row['sep']) ? $row['sep'] : 0) +
            (isset($row['oct']) ? $row['oct'] : 0) +
            (isset($row['nov']) ? $row['nov'] : 0) +
            (isset($row['dec']) ? $row['dec'] : 0)
        );
    }
        
}
