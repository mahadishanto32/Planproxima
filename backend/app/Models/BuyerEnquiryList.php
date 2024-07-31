<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BuyerEnquiryList
 * @package App\Models
 * @version March 22, 2023, 4:46 pm +06
 *
 * @property string $company
 * @property string $productType
 * @property string $country
 * @property string $designation
 */
class BuyerEnquiryList extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'buyer_enquiry_lists';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'company',
        'contact_person',
        'country_origin',
        'designation',
        'email',
        'mobile_number',
        'product_type',
        'project',
        'season',
        'season',
        'user_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company' => 'string',
        'productType' => 'string',
        'country' => 'string',
        'designation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'designation' => 'contact_person string'
    ];

    public function BuyerColumnjoin()
    { 
        return $this->hasMany(buyerEnquiryColumn::class,'buyer_enquiry_id' ,'id');
    }    
}
