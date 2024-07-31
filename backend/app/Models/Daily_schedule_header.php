<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Daily_schedule_header
 * @package App\Models
 * @version August 2, 2021, 3:59 am UTC
 *
 * @property string $headname
 * @property integer $dept_id
 */
class Daily_schedule_header extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'daily_schedule_headers';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'headname',
        'active',
        'serialno',
        'dept_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'headname' => 'string',
        'dept_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'dept_id' => 'is_active integer'
    ];

    
}
