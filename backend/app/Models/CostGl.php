<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;  
class CostGl extends Model
{
    protected $table = "cost_gl";
    protected $fillable = array('id', 
    'gl_name',
    'gl_code',
    'type'
  );
 
}
 