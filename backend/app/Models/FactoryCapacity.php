<?php

namespace App\Models;
use App\Models\SummaryGroup; 
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FactoryCapacity
 * @package App\Models
 * @version June 6, 2021, 10:02 am UTC
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
 * @property number $total_capacity
 */
class FactoryCapacity extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'factory_capacities';
    

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
        'total_capacity'
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
        'total_capacity' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'summary_group_id' => 'required',
        'year' => 'required'
    ];
    public function summary_groupjoin()
    {
        return $this->belongsTo(SummaryGroup::class, 'summary_group_id');
    }
    
}
