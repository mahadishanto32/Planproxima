<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UserManual
 * @package App\Models
 * @version July 25, 2022, 6:19 am UTC
 *
 * @property string $title
 * @property string $details
 * @property integer $status
 */
class UserGroup extends Model
{
    //use SoftDeletes;

    use HasFactory;

    public $table = 'roles';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'title',
        'guard_name', 
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'title' => 'string',
        'guard_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
