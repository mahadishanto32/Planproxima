<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;    
class Ordertype extends Model
{
    protected $table = "order_types";
    protected $fillable = array(
        'id', 
        'cost_center_id', 
        'wastage_summary_group_id',
        'order_type',
        'summary_id'  
    ); 
}
 