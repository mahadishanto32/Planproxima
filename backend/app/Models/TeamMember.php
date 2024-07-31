<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TeamMember
 * @package App\Models
 * @version March 20, 2022, 9:06 am UTC
 *
 * @property int $department_id
 * @property int $wings_id
 * @property int $team_id
 * @property int $employee_id
 */
class TeamMember extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'team_members';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'department_id',
        'wings_id',
        'team_id',
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'department_id' => 'int',
        'wings_id' => 'int',
        'team_id' => 'int',
        'user_id' => 'int|unique:team_members,user_id,NULL,id,deleted_at,NULL'
    ];
    public function userJoin()
    {
        return $this->hasOne(User::class, 'id','user_id' )->where('status',1);
    }    
    public function teamJoin()
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }        
    public function wingJoin()
    {
        return $this->hasOne(Wing::class, 'id', 'wings_id');
    }    

    public function deptjoin()
    {
        return $this->hasOne(Department::class, 'id','department_id');
    } 
    
}
