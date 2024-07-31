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
class TourRouteDetails extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'tour_route_details';
    

    protected $dates = ['deleted_at'];
 
    public $fillable = [
        'tour_id',
        'route_id',
        'route_name',
        'point_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tour_id' => 'integer',
        'route_id' => 'integer',
        'route_name' => 'string',
        'point_id' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tour_id' => 'required',
        'route_id' => 'required',
        'route_name' => 'required',
        'point_id' => 'required',
    ];

    public function userjoin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
