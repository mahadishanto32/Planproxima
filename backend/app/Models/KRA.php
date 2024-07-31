<?php

namespace App\Models;
use App\Models\KPI;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class KRA
 * @package App\Models
 * @version April 19, 2021, 5:36 pm UTC
 *
 * @property string $kra_name
 * @property integer $dept_id
 * @property integer $year
 * @property integer $kra_weight
 */
class KRA extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'k_r_a_s';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'kra_name',
        'dept_id',
        'year',
        'user_id',
        'role_id',
        'wing_id',
        'rep_id',
        'report_type',
        'previous_id',
        'kra_weight'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'kra_name' => 'string',
        'dept_id' => 'integer',
        'year' => 'integer',
        'kra_weight' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'kra_name' => 'required', 
        'kra_weight' => 'required|numeric'
    ];
    public function kpijoin()
    {
        return $this->belongsTo(KPI::class, 'id' , 'kra_id');
    }
    public function kpiandmosnumber(){
        return $this->belongsTo(MOS::class, 'id' , 'kra_id')->count();
    }

    public function userJoin(){
        return $this->belongsTo(User::class, 'user_id' , 'id');
    }

    public function mos_join(){
        return $this->belongsTo(MOS::class, 'id' , 'kra_id');
    }    
}
