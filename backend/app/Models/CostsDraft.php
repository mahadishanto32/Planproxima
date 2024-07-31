<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CostsDraft
 * @package App\Models
 * @version June 6, 2021, 5:40 am UTC
 *
 * @property  $factory_code
 * @property  $cost
 * @property  $remarks
 * @property  $cost_center
 * @property string $error_note
 * @property string $gl_code
 * @property string $data
 */
class CostsDraft extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'costs_drafts';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'factory_code',
        'cost',
        'status',
        'remarks',
        'cost_center',
        'error_note',
        'gl_code',
        'date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'error_note' => 'string',
        'gl_code' => 'string',
        'date' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'factory_code' => 'required',
        'cost' => 'required',
        'cost_center' => 'required',
        'gl_code' => 'required',
        'date' => 'required'
    ];

    
}
