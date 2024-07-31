<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProductDraft
 * @package App\Models
 * @version October 7, 2021, 4:57 am UTC
 *
 * @property integer $plant
 * @property string $product_group
 * @property string $wastage_group
 * @property string $material_code
 * @property string $description
 * @property string $material_group
 * @property string $material_type
 * @property string $base_unit_of_measure
 * @property string $product_type
 * @property string $error_note
 * @property integer $status
 */
class ProductDraft extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'product_drafts';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'plant',
        'product_group',
        'wastage_group',
        'material_code',
        'description',
        'material_group',
        'material_type',
        'base_unit_of_measure',
        'product_type',
        'error_note',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'plant' => 'integer',
        'product_group' => 'string',
        'wastage_group' => 'string',
        'material_code' => 'string',
        'description' => 'string',
        'material_group' => 'string',
        'material_type' => 'string',
        'base_unit_of_measure' => 'string',
        'product_type' => 'string',
        'error_note' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
