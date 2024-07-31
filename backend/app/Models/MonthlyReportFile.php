<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class MonthlyReportFile
 * @package App\Models
 * @version June 8, 2021, 10:04 am UTC
 *
 * @property integer $report_id
 * @property string $file_name
 */
class MonthlyReportFile extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'monthly_report_files';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'report_id',
        'file_caption',
        'file_type',
        'file_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'report_id' => 'integer',
        'file_name' => 'string',
        'file_type' => 'string',
        'file_caption' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'report_id' => 'required',
        'file_name' => 'requierd'
    ];

    
}
