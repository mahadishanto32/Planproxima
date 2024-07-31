<?php

namespace App\Models;

use Eloquent as Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
     
    use HasFactory;
    public $table = 'roles'; 
    
    protected $fillable = [
        'name', 
        'title', 
        'guard_name',
        
    ];
 
}
