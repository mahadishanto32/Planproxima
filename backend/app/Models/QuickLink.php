<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TourPlanBusinessType
 * @package App\Models
 * @property string $title
 * @property string $description

 */
class QuickLink extends Model
{
    use HasFactory;
    public $table = 'quick_links';
    public $timestamps = false;

    public $fillable = [
        'title',
        'module',
        'route',
        'status',
        'user_id'
    ];

}
