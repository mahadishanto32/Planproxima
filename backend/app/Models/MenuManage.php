<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB ;
use Illuminate\Http\Request;
class MenuManage extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'menu_manage';

    // Define the primary key
    protected $primaryKey = 'id';

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'parent_id',
        'menu_name',
        'menu_url',
        'sort',
        'status',
        'menu_hints',
        'created_by',
        'created_at',
        'updated_by',
    ];

    // Disable the timestamps if you don't want Laravel to manage created_at and updated_at columns automatically
    public $timestamps = false;

    // If you want to customize the date format
    protected $dates = ['created_at', 'updated_at'];

    public function submenu()
    {
        return $this->hasMany(MenuManage::class, 'parent_id')->select([
            'id',
            'parent_id',
            'menu_name',
            'menu_url',
            'sort',
            'status',
            'menu_hints',
        ])->orderBy('sort','asc');
    }

    public function submenu_permission($id,  $user_type)
    {
        // $subQuery = DB::table('menu_permission')
        //     ->select('view')
        //     ->whereColumn('menu_permission.menu_id', 'menu_manage.id')
        //     ->where('menu_permission.role_id', $id)
        //     ->limit(1);


        

            if ($user_type ==  'user') {
                if (!DB::table('menu_permission')->where('user_id', $id)->exists()) {
                    $user =  User::where('id', $id)->first();
                    $id =  $user->role_id;
                    $user_type  =  'role';
                }
            }
    
            $subQuery = DB::table('menu_permission')
                ->select('view')
                ->whereColumn('menu_permission.menu_id', 'menu_manage.id');
    
            if ($user_type == 'user') {
                $subQuery->where('user_id', $id);
            } else {
                $subQuery->where('role_id', $id);
            }
    
            $subQuery->limit(1);


    
        return MenuManage::select(
            'menu_manage.id',
            'menu_manage.parent_id',
            'menu_manage.menu_name',
            'menu_manage.menu_url',
            DB::raw('COALESCE((' . $subQuery->toSql() . '), 0) as view')
        )
        ->mergeBindings($subQuery)
        ->where('menu_manage.parent_id', $this->id)
        ->get();
    }
    

    
}
