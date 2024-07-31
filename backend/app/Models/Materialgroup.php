<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model; 
class Materialgroup extends Model
{
    protected $table = "material_groups";
    protected $fillable = array('id',  
    'material_group',
    'material_group_desc',
    'material_group_desc2'); 
}
 