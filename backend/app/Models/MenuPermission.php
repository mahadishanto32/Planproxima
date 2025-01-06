<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $table = 'menu_permission';
    
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'role_id', 
        'user_id',
        'menu_id', 
        'view', 
        'add', 
        'update', 
        'delete', 
        'created_by', 
        'created_at', 
        'updated_by'
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
