<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Materialgroup;
use DB ;
class Product extends Model
{
    protected $table = "products";
    protected $fillable = array(
        'id',
        'created_by',
        'updated_by', 
        'created_at',
        'updated_at',
        'segment',
        'plant',
        'plant_id' , 
        'brand' ,
        'product_group',
        'material_code',
        'description',
        'channel',
        'base_unit_of_measure',
        'material_group_id',
        'summary_group_id',
        'material_type',
        'product_type',
        'parent_material'
    );
    public function materialgroupjoin()
    {
        return $this->belongsTo(Materialgroup::class, 'material_group_id');
    }

    public function wastage_summary_group(){
        if($this->product_type == 'wastage'){
            $proQ =  DB::table('wastege_relations')
            ->where('product_id',$this->id) 
            ->join('wastage_summary_group','wastage_summary_group.id','wastege_relations.wastage_summary_group_id')
            ->select('group_name') ;
            $pro = $proQ->first() ;
            if($proQ->exists()){ 
                return $pro->group_name ;
            }
        }else if($this->product_type == 'consumption'){
            $proQ =  DB::table('consumption_relations')
            ->where('product_id',$this->id) 
            ->join('wastage_summary_group','wastage_summary_group.id','consumption_relations.wastage_summary_group_id')
            ->select('group_name') ;
            $pro = $proQ->first() ;
             if($proQ->exists()){ 
                 return $pro->group_name  ;
             }
        }else{
            return  '';
        }
        
    }
 
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function plantjoin()
    {
        return $this->belongsTo(Factory::class , 'plant_id'); 
    }

    public function summarygroupjoin()
    {
        return $this->belongsTo(SummaryGroup::class , 'summary_group_id'); 
    }
   
}
