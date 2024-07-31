<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Wing
 * @package App\Models
 * @version May 26, 2021, 3:54 am UTC
 *
 * @property integer $dept_id
 * @property string $wing_title
 */
class Wing extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'wings';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'dept_id',
        'status',
        'user_id',
        'wing_user',
        'wing_title'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'dept_id' => 'integer',
        'wing_title' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'dept_id' => 'required',
        'wing_title' => 'required'
    ];

    //deptjoin
    public function deptjoin()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
    //deptjoin
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->where('status',1);
    }

    public function team()
    {
        return $this->hasMany(Team::class,'wings_id');
    } 
    
}
