<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ProductionFeedback
 * @package App\Models
 * @version July 14, 2021, 5:34 am UTC
 *
 * @property integer $factory_id
 * @property integer $summary_group_id
 * @property string $production_type
 * @property integer $section
 * @property string $section_name
 * @property string $comments
 * @property string $type
 * @property string $start_date
 * @property string $end_date
 */
class ProductionFeedback extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'production_feedbacks';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'factory_id',
        'summary_group_id',
        'production_type',
        'section',
        'section_name',
        'comments',
        'user_id',
        'type',
        'start_date',
        'end_date'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'factory_id' => 'integer',
        'summary_group_id' => 'integer',
        'production_type' => 'string',
        'section' => 'integer',
        'section_name' => 'string',
        'comments' => 'string',
        'type' => 'string',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function userInfo(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    
}
