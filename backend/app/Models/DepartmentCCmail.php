<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DepartmentCCmail
 * @package App\Models
 * @version May 16, 2022, 3:48 am UTC
 *
 * @property integer $dept_id
 * @property integer $user_id
 */
class DepartmentCCmail extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'department_c_cmails';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'dept_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'dept_id' => 'integer',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
