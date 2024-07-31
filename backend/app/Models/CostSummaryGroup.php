<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class CostSummaryGroup
 * @package App\Models
 * @version January 17, 2022, 4:26 am UTC
 *
 * @property string $group_name
 * @property integer $summary_group_id
 * @property integer $plant_id
 */
class CostSummaryGroup extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cost_summary_groups';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'group_name',
        'summary_group_id',
        'plant_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'group_name' => 'string',
        'summary_group_id' => 'integer',
        'plant_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
