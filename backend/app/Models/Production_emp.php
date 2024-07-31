<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Production_emp
 * @package App\Models
 * @version August 5, 2021, 9:17 am UTC
 *
 * @property integer $factory_id
 * @property integer $product_id
 * @property integer $week
 * @property integer $month
 * @property integer $year
 * @property integer $number_of_join
 * @property integer $number_of_resig
 * @property integer $begining_emp
 * @property integer $ending_emp
 * @property string $remarks
 * @property integer $user_id
 * @property integer $active
 */
class Production_emp extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'production_emps';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'factory_id',
        'product_id',
        'week',
        'month',
        'year',
        'number_of_join',
        'number_of_resig',
        'begining_emp',
        'ending_emp',
        'remarks',
        'user_id',
        'active'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'factory_id' => 'integer',
        'product_id' => 'integer',
        'week' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'number_of_join' => 'integer',
        'number_of_resig' => 'integer',
        'begining_emp' => 'integer',
        'ending_emp' => 'integer',
        'remarks' => 'string',
        'user_id' => 'integer',
        'active' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'factory_id' => 'required'
    ];

    public function  deptjoin()
    {
        return $this->belongsTo(Department::class, 'factory_id');
    }
    public function projoin()
    {
        return $this->belongsTo(Production_product_name::class, 'product_id');
    }
}
