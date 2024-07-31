<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department_transfer extends Model
{
    use HasFactory;
    protected $table = "department_transfer";
    protected $fillable = array(
        "id", 
        "user_id", 
        "current_dept", 
        "new_dept", 
        "kra_data", 
        "dailyentry_data", 
        "tour_data",
        "created_by", 
        "updated_by", 
        "created_at", 
        "updated_at");    
}
