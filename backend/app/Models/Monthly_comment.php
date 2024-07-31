<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Monthly_comment
 * @package App\Models
 * @version September 6, 2021, 7:58 am UTC
 *
 * @property integer $report_id
 * @property integer $user_id
 * @property integer $dept_id
 */
class Monthly_comment extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'monthly_comments';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'report_id',
        'user_id',
        'dept_id',
        'comment'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'report_id' => 'integer',
        'user_id' => 'integer',
        'dept_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'comment string'
    ];

    
}
