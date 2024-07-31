<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;    
class YearlyWastage extends Model
{
    protected $table = "yearly_wastage_avg";
    protected $fillable = array(
        'id', 
        'your', 
        'wastege_summary_group_id', 
        'summary_group_id', 
        'yearly_wastage_avg',
        'avg'  
    ); 
}
 