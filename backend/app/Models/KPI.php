<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class KPI
 * @package App\Models
 * @version April 19, 2021, 5:54 pm UTC
 *
 * @property integer $dept_id
 * @property integer $kra_id
 * @property string $kpi_name
 * @property integer $kpi_weight
 */
class KPI extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'k_p_i_s'; 
    protected $dates = ['deleted_at']; 

    public $fillable = [
        'dept_id',
        'kra_id',
        'kpi_name',
        'rep_id',
        'year',
        'previous_id',
        'kpi_weight'
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
        'kpi_name' => 'string',
        'kpi_weight' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [ 
        'kpi_name' => 'required',
        'kpi_weight' => 'required'
    ];
    public function krauser()
    {
        return $this->belongsTo(KRA::class, 'kra_id')
        ->join('users','users.id','k_r_a_s.user_id')
        ->select('users.name','users.employee_id');
    }
    
    public function krajoin()
    {
        return $this->belongsTo(KRA::class, 'kra_id');
    }
    public function  mosnumber(){
        return $this->belongsTo(MOS::class, 'id' , 'kpi_id')->count();
    }
    public function mos(){
        return $this->hasMany(MOS::class, 'kpi_id', 'id' );
    }

}
