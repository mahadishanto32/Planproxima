<?php
namespace App\Imports;
use App\Models\Product;   
use App\Models\Materialgroup;  
use App\Models\SummaryGroup ;
use App\Models\Factory;   
use App\Models\WastageSummaryGroup;   
use App\Models\Wastege_relation;   
use App\Models\Consumption_relation;   
use App\Models\WastegeConsumptionRelation;  
use App\Models\ProductionWastageDaft;  
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth ;
class ProductImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {  


        $summary_group = SummaryGroup::where('code',$row['product_group'])->first();  
        $factory = Factory::where('fac_code',$row['plant'])->first();  
        
        $wastageSummaryGroup = WastageSummaryGroup::where('group_name',$row['wastage_group'])->where('plant_id',$factory['id'])->where('summary_group_id',$summary_group['id'])->first(); 

        $product_info = Product::where('material_code',$row['material_code'])->where('plant',$row['plant'])->first();
        
        if(!$product_info){


        $materialgroup = Materialgroup::where('material_group',$row['material_group'])->first();
           
            $product_info =  Product::create([
                'plant'     =>  $row['plant'] , 
                'plant_id' =>  $factory['id'] ? $factory['id'] : 0 , 
                'material_code'     =>  $row['material_code'] ,   
                'description'     =>  $row['description'] ,   
                'product_group' =>  $row['material_group'] ,  
                'material_group_id' =>  $materialgroup ? $materialgroup['id'] : 0 ,  
                'material_type' =>  $row['material_type'] ,  
                'base_unit_of_measure' =>  $row['base_unit_of_measure'] ,  
                'summary_group_id' =>  $summary_group ? $summary_group['id'] : 0,    
                'created_by' => Auth::user()->id ,  
                'updated_by' => Auth::user()->id ,
             ]);
            if(ProductionWastageDaft::where('plant',$row['plant'])->where('product_code',$row['material_code'])->where('status' ,3)->exists()){
                ProductionWastageDaft::where('plant',$row['plant'])->where('product_code',$row['material_code'])->where('status' ,3)->update(
                    [
                        'status'=> 0,
                        'error_note' => null
                    ]);

            }
        } 

        if($wastageSummaryGroup && $product_info){   
            if($row['product_type'] == 'wastage'){
                if(!Wastege_relation::where('product_id', $product_info['id'])
                ->where('summary_group_id', $wastageSummaryGroup['summary_group_id'])
                ->where('wastage_summary_group_id',$wastageSummaryGroup['id'])->exists()){  
                    $insert_data =   Wastege_relation::create([
                        'wastage_summary_group_id'    =>  $wastageSummaryGroup['id'] , 
                        'product_id'        =>  $product_info['id'], 
                        'summary_group_id'     =>  $wastageSummaryGroup['summary_group_id'] ,
                        'plant_id'        =>   $factory['id'] ,
                        'created_by' => Auth::user()->id ,     
                        'updated_by' => Auth::user()->id ,
                    ]);  

                }  
            }else if($row['product_type'] == 'consumption'){

                if(!Consumption_relation::where('product_id', $product_info['id'])
                ->where('summary_group_id', $wastageSummaryGroup['summary_group_id'])
                ->where('wastage_summary_group_id',$wastageSummaryGroup['id'])->exists()){  
                    $insert_data =   Consumption_relation::create([
                        'wastage_summary_group_id'    =>  $wastageSummaryGroup['id'] , 
                        'product_id'        =>  $product_info['id'], 
                        'summary_group_id'  =>  $wastageSummaryGroup['summary_group_id'] ,
                        'plant_id'        =>   $factory['id'] ,
                        'created_by' => Auth::user()->id ,     
                        'updated_by' => Auth::user()->id ,
                    ]);  

                } 

            } 

            // if($wastageSummaryGroup['scrap_material'] != $row['wastage_group']){
            //    $wastageSummaryGroup = WastageSummaryGroup::create([
            //         'group_name' => $wastageSummaryGroup['group_name'],
            //         'scrap_material' => $row['material_code'],
            //         'plant' => $row['plant'],
            //         'plant_id' => $factory['id'],
            //         'grouping_id' => $wastageSummaryGroup['grouping_id'],
            //         'type' => 1 ,
            //     ]);
            // }
 
            // if(!WastegeConsumptionRelation::
            // where('wastage_summary_group_id',$wastageSummaryGroup['id'])
            // ->where('wastage_product_code',$row['wastage_group'])
            // ->where('consumtion_product_code', $product_info['id'])->exists()){ 
            //    $insert_data =   WastegeConsumptionRelation::create([
            //     'wastage_summary_group_id'    =>  $wastageSummaryGroup['id'] , 
            //     'wastage_product_code'        =>  $wastageSummaryGroup['scrap_material'], 
            //     'consumtion_product_code'     =>  $product_info['id'] ,
            //     'consumtion_product'        =>   $product_info['material_code'] ,
            //     //'created_by' => Auth::user()->id ,     
            //     //'updated_by' => Auth::user()->id ,
            // ]);  
            // }
            
        } 

        return $product_info ; 
        //return $product_info ;
    }  
}
