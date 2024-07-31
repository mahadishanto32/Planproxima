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
class TourPointDetails extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'tour_point_details';
    

    protected $dates = ['deleted_at'];
 
    public $fillable = [
        'business_type_id',
        'global_company_id',
        'is_depot',
        'point_division',
        'point_id',
        'point_name',
        'point_status',
        'sap_code',
        'territory_id',
        'territory_name',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'tour_id' => 'integer',
        // 'route_id' => 'integer',
        // 'route_name' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'tour_id' => 'required',
        // 'route_id' => 'required',
        // 'route_name' => 'required'
    ];

    public function userjoin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
