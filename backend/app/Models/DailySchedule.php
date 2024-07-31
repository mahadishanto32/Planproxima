<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use DateTimeInterface;
use Illuminate\Http\Request;
use DB;
use App\Http\Resources\DailyTaskBreakDownResource;
/**
 * Class DailySchedule
 * @package App\Models
 * @version April 24, 2021, 6:55 am UTC
 *
 * @property integer $user_id
 * @property integer $kra_id
 * @property integer $kpi_id
 * @property integer $mos_id
 * @property string $date
 * @property time $start_time
 * @property time $end_time
 * @property string $task
 * @property integer $top_priority
 */
class DailySchedule extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'daily_schedules';
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    protected $dates = ['deleted_at', 'created_at', 'date', 'updated_at'];
    public $fillable = [
        'role_id',
        'user_id',
        'dept_id',
        'wing_id',
        'kra_id',
        'kpi_id',
        'mos_id',
        'factory_format_id',
        'status',
        'date',
        'start_time',
        'end_time',
        'task',
        'top_priority',
        'status',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'kra_id' => 'integer',
        'kpi_id' => 'integer',
        'mos_id' => 'integer',
        'date' => 'date',
        'task' => 'string',
        'top_priority' => 'integer',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'date' => 'required',
        // 'start_time' => 'required',
        // 'end_time' => 'required',
        // 'task' => 'required'
    ];
    public function krajoin()
    {
        return $this->belongsTo(KRA::class, 'kra_id');
    }
    public function kpijoin()
    {
        return $this->belongsTo(KPI::class, 'kpi_id');
    }
    public function mosjoin()
    {
        return $this->belongsTo(MOS::class, 'mos_id');
    }
    public function userjoin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function comments()
    {
        return Daily_schedule_comment::where('daily_schedule_id', $this->id)->get();

        $this->belongsToMany(Daily_schedule_comment::class, 'id', 'daily_schedule_id');
    }
    public function deptjoin()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
    public function factory_formatjoin()
    {
        return $this->belongsTo(Daily_schedule_header::class, 'factory_format_id');
    }
    public function schedule_items()
    {
        //->with('scheduletypename')
        $result = DailyScheduleItem::select('schedule_type_id', 'schedule_details', 'name')
            ->where('daily_schedules_id', $this->id)
            ->join('daily_schedule_types', 'daily_schedule_types.id', 'daily_schedule_items.schedule_type_id')
            ->get();
        return $result;

        //return $this->belongsToMany(DailyScheduleItem::class, 'id','daily_schedules_id');
    }
    public function tasks(Request $request , $id , $user)
    {
        $resultQuery = DailyScheduleItem::leftJoin('projects', 'projects.id', 'daily_schedule_items.project_id');
        $resultQuery->leftJoin('departments' , 'departments.id' , 'daily_schedule_items.department');
        $resultQuery->select('daily_schedule_items.*' ,
        'projects.name as project', 
        DB::raw("'" . $user . "' as user_id") , 
        'departments.name as department_list');

        if($request->task_type){
            $resultQuery->where('task_type', 1);
        }

        if($request->work_type){
            $resultQuery->where('work_type', 1);
        }  
        
        $resultQuery->where('daily_schedules_id', $id);
        $result = $resultQuery->get();

        if(count($result) == 0){
            $resultQuery = DailyScheduleItem::select('*' , DB::raw("'" . $user . "' as user_id") , 'departments.name as department_list')
            ->join('departments' , 'departments.id' , 'daily_schedule_items.department');
            $resultQuery->where('daily_schedules_id', $this->id);
            if($request->task_type){
                $resultQuery->where('task_type', 1);
            }
            if($request->work_type){
                $resultQuery->where('work_type', 1);
            }              
            // $result = $resultQuery->get();  
            $result = $resultQuery->get(['daily_schedule_items.*', 'projects.name as project', 'user_id']);    
        }  
        return DailyTaskBreakDownResource::collection($result) ;
        // return $this->belongsToMany(DailyScheduleItem::class, 'id','daily_schedules_id');
    }

    public function customData()
    {
        return $dates = array(
            'task' => $this->task,
            'items' => DailyScheduleItem::select('*')
                ->where('daily_schedules_id', $this->id)
                ->first()
        );
    }
}
