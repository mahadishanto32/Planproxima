<?php

namespace App\Models;
use App\Models\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TourUser
 * @package App\Models
 * @version May 19, 2021, 6:37 am UTC
 *
 * @property integer $user_id
 * @property integer $employee_id
 * @property string $designation
 * @property integer $business_type
 * @property integer $head_of_sales
 * @property integer $division_head
 * @property integer $sm
 * @property integer $dsm
 * @property integer $asm
 * @property integer $adsm
 * @property integer $rsm
 */
class TourUser extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'tour_users';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id',  
        'business_type',
        'head_of_sales',
        'division_head',
        'base_station_address',
        'dmd_tour',
        'division_id',
        'sm',
        'dsm',
        'asm',
        'adsm',
        'rsm'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',  
        'business_type' => 'integer',
        'head_of_sales' => 'integer',
        'division_head' => 'integer',
        'sm' => 'integer',
        'dsm' => 'integer',
        'asm' => 'integer',
        'adsm' => 'integer',
        'rsm' => 'integer'
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [  
    ];
  
    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id')
            ->with(['division' => function($query){
                $query->select('id','name');
            }]);
    }
     
    public function businesstype(){
        return $this->belongsTo(TourPlanBusinessType::class,'business_type','id');
    }
    public function division(){
        return $this->belongsTo(Division::class,'division_id','id');
    }
}
