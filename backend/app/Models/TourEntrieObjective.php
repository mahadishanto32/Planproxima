<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TourEntrieObjective
 * @package App\Models
 * @version June 30, 2022, 4:23 am UTC
 *
 * @property integer $tour_entrie_id
 * @property string $objective
 */
class TourEntrieObjective extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'tour_entrie_objectives';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'user_id', 
        'month',
        'objective'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'objective' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
