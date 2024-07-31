<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Wastege_relation
 * @package App\Models
 * @version September 30, 2021, 4:13 am UTC
 *
 * @property integer $wastage_summary_group_id
 * @property integer $product_id
 */
class Wastege_relation extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'wastege_relations';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'wastage_summary_group_id',
        'product_id',
        'summary_group_id',
        'plant_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'wastage_summary_group_id' => 'integer',
        'product_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
