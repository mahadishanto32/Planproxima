<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TourPlanBusinessType
 * @package App\Models
 * @property string $title
 * @property string $description

 */
class TourPlanBusinessType extends Model
{
    use SoftDeletes;
    use HasFactory;
    public $table = 'tour_plan_business_types';
    protected $dates = ['deleted_at'];
 
    public $fillable = [
        'title',
        'description',
        'created_by',
        'dept_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required',
    ];
    
}
