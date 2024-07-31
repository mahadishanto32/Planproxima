<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityTaskItemsLog extends Model
{
    use HasFactory;

    public $table = 'priority_task_items_logs';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'priority_task_id',
        'priority_task_item_id',
        'action_type',
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
}
