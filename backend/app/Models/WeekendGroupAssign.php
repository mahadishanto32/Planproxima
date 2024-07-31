<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekendGroupAssign extends Model
{
    use HasFactory;
    protected $table = "weekendg_assign";
    protected $fillable = array(
        'department_id', 
        'group_id',   
    );    
}
