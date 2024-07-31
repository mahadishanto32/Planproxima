<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UaerShare
 * @package App\Models
 * @version November 8, 2022, 9:41 am +06
 *
 * @property integer $user_id
 */
class UaerShare extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'uaer_shares';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',
        'dept_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'dept_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [ 
    ];

    
}
