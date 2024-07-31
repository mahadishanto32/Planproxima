<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentTemplates extends Model
{
    use HasFactory;
    public $table = 'department_templates';

    protected $dates = ['deleted_at'];

}
