<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Production_product_name
 * @package App\Models
 * @version August 5, 2021, 10:05 am UTC
 *
 * @property string $product_name
 * @property integer $type
 * @property integer $factory_id
 * @property integer $user_id
 * @property integer $active
 */
class Production_product_name extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'production_product_names';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'product_name',
        'type',
        'factory_id',
        'user_id',
        'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'product_name' => 'string',
        'type' => 'integer',
        'factory_id' => 'integer',
        'user_id' => 'integer',
        'active' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'product_name' => 'factory_id integer'
    ];

    
}
