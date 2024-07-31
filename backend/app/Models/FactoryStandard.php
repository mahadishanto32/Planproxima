<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\SummaryGroup;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class FactoryStandard
 * @package App\Models
 * @version June 6, 2021, 9:00 am UTC
 *
 * @property string $year
 * @property string $type
 * @property string $cost_center
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
 * @property string $gl_code
 * @property string $gl_text
 * @property string $cost_amount
 * @property  $cost_center_id
 */
class FactoryStandard extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'factory_standards';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'year',
        'type',
        'report_type',
        'cost_center',
        'gl_code',
        'gl_text',
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
        'cost_amount',
        'cost_center_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'year' => 'string',
        'type' => 'string',
        'cost_center' => 'string',
        'gl_text' => 'string',
        'cost_amount' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'year' => 'required',
        'type' => 'required',
        'cost_center' => 'required',
        'gl_code' => 'required',
        'gl_text' => 'required',
        'cost_amount' => 'required',
        'cost_center_id' => 'required'
    ];

    public function product_group(){ 
       return SummaryGroup::select('summary_group.description')
        ->where('cost_centers.id',$this->cost_center_id)
        ->join('cost_centers','cost_centers.summary_group_id','=','summary_group.id') 
        ->first(); 
    }

    
}
