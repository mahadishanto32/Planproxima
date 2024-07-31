<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProductionPlans
 * @package App\Models
 * @version June 6, 2021, 10:56 am UTC
 *
 * @property number $jan
 * @property number $feb
 * @property number $mar
 * @property number $apr
 * @property number $may
 * @property number $jun
 * @property number $jul
 * @property number $aug
 * @property number $sep
 * @property number $oct
 * @property number $nov
 * @property number $dec
 * @property integer $summary_group_id
 * @property string $year
 * @property string $type
 * @property integer $created_by
 * @property integer $updated_by
 * @property number $production_plan
 * @property string $material_code
 */
class ProductionPlans extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'production_plans';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sep',
        'oct',
        'nov',
        'dec',
        'summary_group_id',
        'year',
        'type',
        'created_by',
        'updated_by',
        'production_plan',
        'material_code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'jan' => 'double',
        'feb' => 'double',
        'mar' => 'double',
        'apr' => 'double',
        'may' => 'double',
        'jun' => 'double',
        'jul' => 'double',
        'aug' => 'double',
        'sep' => 'double',
        'oct' => 'double',
        'nov' => 'double',
        'dec' => 'double',
        'summary_group_id' => 'integer',
        'year' => 'string',
        'type' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'production_plan' => 'double',
        'material_code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'summary_group_id' => 'required',
        'year' => 'required',
        'type' => 'required'
    ];

    public function projoin()
    {
        return $this->belongsTo(Product::class, 'material_code' , 'material_code');
    }
    public function summary_groupjoin()
    {
        return $this->belongsTo(SummaryGroup::class, 'summary_group_id');
    }
}
