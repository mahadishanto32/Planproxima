<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PriorityTaskItem
 * @package App\Models
 * @version November 2, 2023, 11:04 am +06
 *
 * @property integer $priority_task_id
 */
class PriorityTaskItem extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'priority_task_items';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'priority_task_id',
        'task',
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
        'quarter_weightage',
        'half_year_weightage',
        'year_weightage',
        'priority_value',
        'quarter_achiv',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'priority_task_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
