<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Daily_schedule_comment
 * @package App\Models
 * @version July 17, 2021, 6:34 am UTC
 *
 * @property integer $daily_schedule_id
 * @property integer $user_id
 * @property string $comment
 */
class Daily_schedule_comment extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'daily_schedule_comments';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'daily_schedule_id',
        'user_id',
        'user_name',
        'comment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'daily_schedule_id' => 'integer',
        'user_id' => 'integer',
        'comment' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
