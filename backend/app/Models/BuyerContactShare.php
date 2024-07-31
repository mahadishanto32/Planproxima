<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BuyerContactShare
 * @package App\Models
 * @version March 30, 2023, 10:24 am +06
 *
 * @property int $b_id
 * @property int $user_id
 */
class BuyerContactShare extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'buyer_contact_shares';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'b_id',
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
        
    ];

    
}
