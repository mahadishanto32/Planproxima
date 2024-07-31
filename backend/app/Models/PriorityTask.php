<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Auth ;
use App\Models\Department ;
use App\Models\User;
use Carbon\Carbon;
/**
 * Class PriorityTask
 * @package App\Models
 * @version November 2, 2023, 11:01 am +06
 *
 * @property integer $dept_id
 * @property integer $quarter_id
 * @property integer $status
 */
class PriorityTask extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'priority_tasks';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'dept_id',
        'quarter_id',
        'half_year_id',
        'status',
        'year',
        'created_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'dept_id' => 'integer',
        'quarter_id' => 'integer',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
    public function tasks(){
        // Get the current user
        $currentMonth  = strtolower(Carbon::now()->format('M'));
        $user = Auth::user(); 

        return PriorityTaskItem::select('priority_task_items.*')
        ->addSelect(\DB::raw('(SELECT COUNT(*) FROM priority_task_comments WHERE priority_task_comments.priority_item_task_id = priority_task_items.id) as msmcount'))
        ->addSelect(\DB::raw('(SELECT COUNT(*) FROM priority_task_comments WHERE priority_task_comments.is_read = 0 and priority_task_comments.created_by != ' . $user->id . ' AND priority_task_comments.priority_item_task_id = priority_task_items.id) as upread'))
        ->where('priority_task_id', $this->id) 
        ->orderByRaw('CASE WHEN quarter_achiv = 100 THEN 1 ELSE 0 END, `'.$currentMonth.'` DESC')
        ->get();

    
    }

    public function dept(){
        return  Department::where('id',$this->dept_id)->first();
        //dept
    }
    public function user()
    { 
        $user = User::where('dept_id', $this->dept_id)
                    ->where('role_id', 5)
                    ->first(); 
        if (!$user) {
            $user = User::where('id', $this->created_by)->first();
        }

        return $user;
    }

    
    
    
   
}