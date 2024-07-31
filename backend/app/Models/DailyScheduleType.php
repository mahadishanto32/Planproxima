<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DailyScheduleType
 * @package App\Models
 * @version June 28, 2022, 3:27 am UTC
 *
 * @property string $name
 * @property integer $status
 */
class DailyScheduleType extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'daily_schedule_types';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'view_in_list',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'status' => 'required'
    ];

    
}
