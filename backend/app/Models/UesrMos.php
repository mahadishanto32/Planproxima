<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UesrMos extends Model
{
    public $timestamps = false;
    public $table = 'user_mos';
    
    public $fillable = [
        'user_id',
        'mos_id'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'mos_id' => 'required'
    ];

    
}
