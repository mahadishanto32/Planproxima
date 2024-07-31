<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PriorityTaskComments
 * @package App\Models
 * @version November 6, 2023, 12:01 pm +06
 *
 */
class PriorityTaskComments extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'priority_task_comments';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        "comment",                       
        "created_by",                          
        "priority_item_task_id",         
        "is_read",      
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
