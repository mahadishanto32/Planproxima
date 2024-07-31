<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourUserProfile extends Model
{
    use SoftDeletes;
    use HasFactory;
    public $table = 'tour_user_profiles';
    protected $dates = ['deleted_at'];
    public $timestamps = false;
    public $fillable = [
        'user_id',
        'base_station_address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function userjoin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
