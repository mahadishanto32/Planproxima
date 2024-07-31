<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DailyScheduleItem
 * @package App\Models
 * @version June 28, 2022, 7:36 am UTC
 *
 * @property integer $daily_schedules_id
 * @property integer $schedule_type_id
 * @property string $schedule_details
 */
class DailyScheduleItem extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'daily_schedule_items';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'schedule_type_id', 
        'daily_schedules_id', 
        'schedule_type_id', 
        'schedule_details', 
        'task', 
        'start_time', 
        'end_time', 
        'duration', 
        'top_priority', 
        'status',
        'project_id',
        'work_type',
        'task_type',
        'department'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'daily_schedules_id' => 'integer',
        'schedule_type_id' => 'integer',
        'schedule_details' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'daily_schedules_id' => 'required',
        'schedule_type_id' => 'required'
    ];

    
}
