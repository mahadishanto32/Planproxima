<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class projects
 * @package App\Models
 * @version March 1, 2023, 11:10 am +06
 *
 * @property string $name
 * @property int $dep
 * @property wings $wings
 */
class projects extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'projects';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name',
        'dept_id',
        'wing_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    public function wingjoin()
    { 
        return $this->belongsTo(Wing::class, 'kra_id');
    }
    
    public function deptjoin()
    { 
        return $this->belongsTo(Department::class, 'kpi_id');
    }
    
}
