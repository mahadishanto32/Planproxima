<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MOSAchievementPermission
 * @package App\Models
 * @version September 5, 2022, 7:15 am UTC
 *
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $mos_id
 * @property integer $jan
 * @property integer $feb
 * @property integer $mar
 * @property integer $apr
 * @property integer $may
 * @property integer $jun
 * @property integer $jul
 * @property integer $aug
 * @property integer $sep
 * @property integer $oct
 * @property integer $nov
 * @property integer $dec
 * @property integer $dept_id
 * @property integer $year
 * @property string $type
 * @property string $start_date
 * @property string $end_date
 */
class MOSAchievementPermission extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'm_o_s_achievement_permissions';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'role_id',
        'mos_id',
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',
        'dept_id',
        'year',
        'type',
        'start_date',
        'request_status',
        'end_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'role_id' => 'integer',
        'mos_id' => 'integer',
        'jan' => 'integer',
        'feb' => 'integer',
        'mar' => 'integer',
        'apr' => 'integer',
        'may' => 'integer',
        'jun' => 'integer',
        'jul' => 'integer',
        'aug' => 'integer',
        'sep' => 'integer',
        'oct' => 'integer',
        'nov' => 'integer',
        'dec' => 'integer',
        'dept_id' => 'integer',
        'year' => 'integer',
        'type' => 'string',
        'start_date' => 'string',
        'end_date' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
