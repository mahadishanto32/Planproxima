<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UserManualFile
 * @package App\Models
 * @version July 25, 2022, 6:23 am UTC
 *
 * @property integer $user_manual_id
 * @property string $file_name
 * @property integer $order_by
 */
class UserManualFile extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'user_manual_files';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_manual_id',
        'file_name',
        'order_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_manual_id' => 'integer',
        'file_name' => 'string',
        'order_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
