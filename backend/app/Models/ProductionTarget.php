<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProductionTarget
 * @package App\Models
 * @version June 7, 2021, 3:25 am UTC
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
 * @property integer $summary_group_id
 * @property string $year
 * @property string $type
 * @property string $material_code
 * @property number $production_target
 * @property integer $created_by
 * @property integer $updated_by
 */
class ProductionTarget extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'production_targets';
    

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
        'material_code',
        'production_target',
        'created_by',
        'updated_by'
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
        'material_code' => 'string',
        'production_target' => 'double',
        'created_by' => 'integer',
        'updated_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nov' => 'dec double',
        'summary_group_id' => 'requeird',
        'year' => 'requeird'
    ];

    public function productjoin()
    {
        return $this->belongsTo(Product::class, 'material_code' , 'material_code' );
    }
    public function summarygroupjoin()
    {
        return $this->belongsTo(SummaryGroup::class, 'summary_group_id' );
    }
}
