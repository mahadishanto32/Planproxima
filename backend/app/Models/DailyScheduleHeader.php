<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class DailyScheduleHeader
 * @package App\Models
 * @version June 29, 2022, 5:15 pm UTC
 *
 * @property string $headname
 * @property integer $dept_id
 * @property integer $active
 * @property integer $serialno
 */
class DailyScheduleHeader extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'daily_schedule_headers';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'headname',
        'dept_id',
        'active',
        'serialno'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'headname' => 'string',
        'dept_id' => 'integer',
        'active' => 'integer',
        'serialno' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
