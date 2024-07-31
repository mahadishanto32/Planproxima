<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TourEntry
 * @package App\Models
 * @version June 16, 2021, 4:52 am UTC
 *
 * @property integer $user_id
 */
class TourEntry extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'tour_entries';
    

    protected $dates = ['deleted_at'];
 
    public $fillable = [
        'user_id',
        'dept_id',
        'point',
        'territory',
        'point_id',
        'sap_code',
        'objective_id',
        'route_name',
        'objectives',
        'specia_objective',
        'contactperson',
        'hq',
        'approval',
        'remarks',
        'feedback',
        'issues',
        'status',
        'work_with_id',
        'work_with',
        'work_station',
        'date',
        'hos',
        'sm', 
        'asm',
        'dsm',
        'adsm',
        'rsm' ,
        'approval_count',
        'last_approval_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'point' => 'string',
        'territory' => 'string',
        'route_name' => 'string',
        'objectives' => 'string',
        'specia_objective' => 'string',
        'contactperson' => 'string',
        'hq' => 'string',
        'remarks' => 'string',
        'feedback' => 'string',
        'status' => 'integer',
        'approval' => 'integer',
        'approval_count' => 'integer',
        'last_approval_date' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'point' => 'required',
        'route_name' => 'required',
        'objectives' => 'required'
    ];
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'user_id');
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
    public function touruser(){
        return $this->belongsTo(TourUser::class,'user_id','user_id')
            ->with(['businesstype' => function($query){
                $query->select('id','title');
            }])->with(['division' => function($query){
                $query->select('id','name');
            }]);
    }
    
    public function routejoin()
    {
        return $this->hasMany(TourRouteDetails::class, 'tour_id');
    }
    public function territoryjoin()
    {
        return $this->hasMany(TourTerritoryDetails::class, 'tour_id');
    }

    public function pointjoin()
    {
        return $this->hasMany(TourPointDetails::class, 'tour_id');
    }

    public function fojoin()
    {
        return $this->hasMany(TourFoDetails::class, 'tour_id');
    }

    public function customData(){
        return $dates = array(  
            'point' => $this->point ,
            'route_name' => $this->route_name ,
            'objectives' => $this->objectives ,
            'specia_objective' => $this->specia_objective ,
            'contactperson' => $this->contactperson ,
            'hq' => $this->hq ,
            'approval' => $this->approval ,
            'remarks' => $this->remarks ,
            'feedback' => $this->feedback ,
            'issues' => $this->issues ,
            'status' => $this->status ,
            'work_with' => $this->work_with ,
            'work_station' => $this->work_station ,
            'date'  => $this->date
        );
    }

    public function objectiveItem(){
        return  TourEntrieObjective::where('id', $this->objective_id)->get() ;
       // return $this->belongsToMany(TourEntrieObjective::class,  'tour_entrie_id' ,'id');
    }
}
