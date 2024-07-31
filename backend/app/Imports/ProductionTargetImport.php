<?php
namespace App\Imports;
use App\Models\ProductionTarget; 
use App\Models\Product; 
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
use DB;
class ProductionTargetImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {    
        $product = Product::where('material_code',$row['material_code'])
        ->where('plant',$row['plant'])
        //->where('product_type','PRODUCTION')
        ->where(function($q) {
            $q->where('product_type', 'PRODUCTION')
              ->orWhere('product_type', 'production')
              ->orWhere('product_type', 'consumption');
        })
        ->first(); 
        if($product){ 
            if(!$product['summary_group_id']){
                $product['summary_group_id'] = 0;
            }
            $production_target = ProductionTarget::where('year',  $row['year'] )
            ->where('material_code',  $row['material_code'] )
            ->where('summary_group_id',  $product['summary_group_id'] )
            ->where('type',  strtolower($row['type']))
            ->first(); 
            $month = $row['month'];  
            $month_name = substr($month, 0, 3);  // abcd 
                if($production_target){ 
                    ProductionTarget::where('id', $production_target->id)
                        ->update([
                            $month_name => $row['target']
                        ]);
                    return $production_target ;
                
                }else{
                    return new ProductionTarget([ 
                        $month_name => $row['target'] , 
                        'summary_group_id' => $product['summary_group_id'] , 
                        'year' => $row['year'],
                        'type' => strtolower($row['type']),
                        'production_target' =>  0,  
                        'material_code' =>   $row['material_code'], 
                        ]); 
                }
        }else{
            return  ProductionTarget::find(100);
        }
     
    }  
    public function totat($row){
        return ( $row['jan'] + $row['feb'] +  $row['mar'] +  $row['apr'] +  $row['may'] + $row['jun'] + $row['jul'] + $row['aug']  + $row['sep'] + $row['oct']  + $row['nov']  + $row['dec'] ); 
        //return 200 ;
    }
}
