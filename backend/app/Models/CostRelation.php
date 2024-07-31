<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CostRelation
 * @package App\Models
 * @version January 17, 2022, 4:30 am UTC
 *
 * @property integer $summary_group_id
 * @property integer $plant_id
 * @property string $cost_center
 */
class CostRelation extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cost_relations';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'summary_group_id',
        'plant_id',
        'cost_center'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'summary_group_id' => 'integer',
        'plant_id' => 'integer',
        'cost_center' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
