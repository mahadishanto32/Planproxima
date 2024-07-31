<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class buyerEnquiryColumn
 * @package App\Models
 * @version April 11, 2023, 2:09 pm +06
 *
 * @property bigint $buyer_enquiry_id
 * @property string $column_name
 */
class buyerEnquiryColumn extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'buyer_enquiry_columns';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'buyer_enquiry_id',
        'column_name',
        'column_value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'column_name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'column_name' => 'column_value string'
    ];

    
}
