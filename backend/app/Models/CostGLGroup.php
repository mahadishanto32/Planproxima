<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostGLGroup extends Model
{
    use HasFactory;
    protected $table = "costgl_group";
    protected $fillable = array(
        'id', 
        'name',
    );
    
}
