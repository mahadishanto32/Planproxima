<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class follow_up
 * @package App\Models
 * @version August 23, 2021, 5:26 am UTC
 *
 * @property string $date
 * @property string $details
 * @property  $dept_id
 * @property string $firstremind
 * @property  $secondremind
 * @property integer $user_id
 * @property integer $status
 * @property integer $active
 * @property integer $dmdactive
 */
class Follow_up extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'follow_ups';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'date',
        'details',
        'dept_id',
        'firstremind',
        'secondremind',
        'user_id',
        'status',
        'active',
        'complete',
        'dmdactive',
        'reminderflag', 
        'reminderflagid', 
        'reminderdate', 
        'remindertime'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
        'details' => 'string',
        'firstremind' => 'date',
        'user_id' => 'integer',
        'status' => 'integer',
        'active' => 'integer',
        'dmdactive' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    public function deptsjoin()
    {
        return FollowUpDept::select('departments.*')
        ->where('follow_up_depts.activity_id',$this->id)
        ->join('departments','departments.id','follow_up_depts.dept_id')
        ->get();
        //return $this->belongsToMane(FollowUpDept::class ,'id', 'activity_id');
    }
    
}
