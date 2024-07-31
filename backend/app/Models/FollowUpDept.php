<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FollowUpDept
 * @package App\Models
 * @version August 23, 2021, 7:52 am UTC
 *
 * @property integer $dept_id
 * @property integer $activity_id
 * @property string $users
 * @property integer $users_id
 */
class FollowUpDept extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'follow_up_depts';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'dept_id',
        'activity_id',
        'users',
        'users_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'dept_id' => 'integer',
        'activity_id' => 'integer',
        'users' => 'string',
        'users_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
