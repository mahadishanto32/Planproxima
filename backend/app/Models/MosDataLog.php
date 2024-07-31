<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MosDataLog
 * @package App\Models
 * @version June 1, 2022, 6:39 am UTC
 *
 * @property integer $mos_data_id
 * @property string $type
 * @property integer $january
 * @property integer $february
 * @property integer $march
 * @property integer $april
 * @property integer $may
 * @property integer $june
 * @property integer $july
 * @property integer $august
 * @property integer $september
 * @property integer $october
 * @property integer $november
 * @property integer $december
 * @property string $year
 * @property integer $total
 * @property string $insert_type
 */
class MosDataLog extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'mos_data_logs';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'mos_data_id',
        'mos_id',
        'type',
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
        'permission_months',
        'year',
        'total',
        'created_by',
        'insert_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'mos_data_id' => 'integer',
        'type' => 'string',
        'january' => 'integer',
        'february' => 'integer',
        'march' => 'integer',
        'april' => 'integer',
        'may' => 'integer',
        'june' => 'integer',
        'july' => 'integer',
        'august' => 'integer',
        'september' => 'integer',
        'october' => 'integer',
        'november' => 'integer',
        'december' => 'integer',
        'year' => 'string',
        'total' => 'integer',
        'insert_type' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mos_data_id' => 'required',
        'type' => 'required'
    ];

    public function mosjoin()
    {
        return $this->belongsTo(MOS::class, 'mos_id');
    }
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'created_by')->select('users.name','users.employee_id','users.role_id' ,'roles.title')->join('roles','roles.id','users.role_id');
    }
}
