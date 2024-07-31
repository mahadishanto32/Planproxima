<?php

namespace App\Exports;

use App\Models\User;
use App\Models\MOS;
use App\Models\DailySchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB , Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
class UsersTaskExport implements FromCollection , WithHeadings , WithMapping
{
    private $data; 

    public function __construct(array $data = [])
    {
        $this->data = $data; 
    }

    public function collection()
    {
        $user_data = Auth::user();
        $user_id = ''; 
        if($this->data['user_id']){
            $user_id = $this->data['user_id'];
        }else{
            $user_data = Auth::user();
            $user_id =   $user_data->id ; 
        }

        $formDate = $this->data['formDate'];
        $todate = $this->data['todate'];

        $user = User::find($user_id);
        $status = DB::raw("daily_schedules.status");  
        $work_type = DB::raw("(CASE WHEN daily_schedule_items.work_type='1' THEN 'Uplanned' ELSE 'Planned' END) as work_type");
        $task_type = DB::raw("(CASE WHEN daily_schedule_items.task_type='1' THEN 'NON-OPT' ELSE 'OPT' END) as task_type");
        // $dateFormate = DB::raw("DATE_FORMAT(daily_schedules.date, '%Y-%m-%d') as date");
        $dateFormate = DB::raw("DATE_FORMAT(daily_schedules.date, '%Y-%m-%d') as date");

        $query = DailySchedule::select('daily_schedule_items.task as task' , $dateFormate , $status,
        $work_type , 'projects.name as project', $task_type , 'users.name')
        ->join('users' , 'users.id' , 'daily_schedules.user_id')
        ->join('daily_schedule_items' , 'daily_schedules.id' , 'daily_schedule_items.daily_schedules_id')
        ->join('projects' , 'projects.id' , 'daily_schedule_items.project_id');
        if($user_data->role_id == 6){
            $query->where('users.wing_id', $user->wing_id);
        }elseif($user_data->role_id == 7){
            $query->where('users.id', $user->id);
        }else{
            $query->where('users.dept_id', $user->dept_id);
        }
        $query->whereBetween('daily_schedules.date' , [$formDate , $todate]);
        $result = $query->get(); 

        return $result;
        
        
        
    }
    public function map($item): array
    {
        return [
            $item->task,
            $item->date->format('Y-m-d'), // Format the date here
            $item->status = $item->status? 'Done':'Undone',
            $item->work_type,
            $item->project,
            $item->task_type,
            $item->name,
        ];
    } 

    public function headings(): array
    {
        return [
            'Task',
            'Date',
            'Status(Undone|Done|Ongoing|Pending)' , 
            'Planned|Unplanned',
            'Project Name',
            'Task Type (OPT|Non-OPT)',
            'Name',
        ];
    }
}
