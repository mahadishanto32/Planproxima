<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Team
 * @package App\Models
 * @version March 16, 2022, 5:51 am UTC
 *
 * @property varchar(100) $team_name
 * @property int(11) $team_leader
 * @property int(11) $wings_id
 */
class Team extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'teams';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'team_name',
        'team_leader',
        'wings_id',
        'dept_id'
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
        'team_name' => 'string',
        'team_leader' => 'integer',
        'wings_id' => 'integer',
        'dept_id' => 'integer'
    ];

    public function wingJoin()
    {
        return $this->hasOne(Wing::class, 'id', 'wings_id');
    }    
    public function userJoin()
    {
        return $this->hasOne(User::class, 'id','team_leader' )->where('status',1);
    }    
    public function deptjoin()
    {
        return $this->hasOne(Department::class, 'id','dept_id');
    }    

    public function teamMember()
    {
        return $this->hasMany(TeamMember::class,);
    }     
    
}
