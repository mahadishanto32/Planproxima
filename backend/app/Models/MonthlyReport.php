<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MonthlyReport
 * @package App\Models
 * @version April 28, 2021, 8:56 am UTC
 *
 * @property integer $dept_id
 * @property string $task_name
 * @property string $monthly_work
 * @property string $topforcurrentmonth
 * @property string $valueadd
 * @property string $reason
 * @property integer $month
 * @property integer $year
 * @property string $date
 * @property string $attach1
 * @property string $attach2
 * @property string $attach3
 * @property string $attach4
 * @property string $attach5
 * @property string $attach6
 * @property string $attach7
 * @property string $attach8
 * @property string $attach9
 * @property string $attach10
 * @property integer $worktype
 * @property integer $user_id
 */
class MonthlyReport extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'monthly_reports';
     
    protected $dates = ['deleted_at'];
 
    public $fillable = [
        'dept_id',
        'task_name',
        'monthly_work',
        'topforcurrentmonth',
        'man_power_efficiency',
        'valueadd',
        'reason',
        'month',
        'role_id',
        'kra_id',
        'custom_kra',
        'kpi_id',
        'year',
        'date', 
        'worktype',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dept_id' => 'integer',
        'kra_id' => 'integer',
        'kpi_id' => 'integer',
        'task_name' => 'string',
        'monthly_work' => 'string',
        'topforcurrentmonth' => 'string',
        'man_power_efficiency' => 'string',
        'valueadd' => 'string',
        'reason' => 'string',
        'date' => 'date', 
        'worktype' => 'string',
        'user_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [  
        'worktype' => 'required' 
    ];

    public function krajoin()
    { 
        return $this->belongsTo(KRA::class, 'kra_id');
    }
    public function kpijoin()
    { 
        return $this->belongsTo(KPI::class, 'kpi_id');
    }
}
