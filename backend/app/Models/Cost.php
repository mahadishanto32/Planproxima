<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\CostCenter;
use App\Models\Factory;
use App\Models\SummaryGroup;
use App\Models\CostGl;
use App\Models\User; 
class Cost extends Model
{
    protected $table = "costs";
    protected $fillable = array('id', 
    'cost_center_id', 
    'factory_id',
    'created_by',
    'updated_by', 
    'created_at',
    'updated_at',
    'factory_code',
    'pg_code',
    'date' ,
    'status',
    'cost_gl_id', 
    'cost',
    'summary_group_id',
    'unit',
    'remarks');

    public function costcenterjoin()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }
     
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function factoryjoin()
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }
    public function summary_groupjoin()
    {
        return $this->belongsTo(SummaryGroup::class, 'summary_group_id');
    }
    
    public function gljoin()
    {
        return $this->belongsTo(CostGl::class, 'cost_gl_id');
    }
    
    public function costgljoin($cost_gl_id)
    {
        return CostGl::find($cost_gl_id);
        //return $cost_gl_id  ;
        // $this->belongsTo(CostGl::class, 'cost_gl_id');
    }
    public function averageCost($request_data , $item , $standard , $type ){
        return '0';
    }
   
    public function averagePerUnit($request_data , $item , $standard , $type){
        return '0';
    }

}
 