<?php

namespace App\Console\Commands; 
use Illuminate\Console\Command;  
use App\Models\Department;

use App\Models\PriorityTask; 
use App\Models\PriorityTaskItem; 

class PriorityTasksQuarterly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'priority_tasks_quarter_sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Priority Tasks Copy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //Department::
        $quarter = $this->getCurrentQuarterId();
        $year  = '2024' ; 
        $depts = $this->priority_task_not_update($year,  $quarter );

        foreach ($depts as $key => $dept) { 
                $priorityTask  = PriorityTask::where('status', 1)
                ->where('year', $year)
                ->where('quarter_id', $quarter -1 )->get();  
                foreach ($priorityTask  as $key2 => $item) { 
                   $newTask =  PriorityTask::create([ 
                        'dept_id' => $item->dept_id, 
                        'quarter_id' => $quarter ,
                        'status' => 1,
                        'year' => $year ,  
                        'created_by' => $item->created_by
                    ]); 

                   $taskItems =  PriorityTaskItem::where('priority_task_id', $item->id)->get();
                   foreach ($taskItems  as $key3 => $row) { 
                       PriorityTaskItem::create([
                        'priority_task_id' => $newTask->id, 
                        'task' => $row->task,
                        'jan' => 0,
                        'feb' => 0,
                        'mar' => 0,
                        'apr' => 0,
                        'may' => 0,
                        'jun' => 0,
                        'jul' => 0,
                        'aug' => 0,
                        'sep' => 0,
                        'oct' => 0,
                        'nov' => 0,
                        'dec' => 0,
                        'quarter_weightage' => 0 ,
                        'quarter_achiv' =>  $row->quarter_achiv ,
                        'half_year_weightage'=> 0,
                        'year_weightage'=> 0,
                        'priority_value'=> $key3, 
                        'created_by' => $row->created_by
                        
                    ]);
                     
                   } 
                } 
             
        }
      
    }








    public function priority_task_not_update($year,$quarter){
        $q = Department::select('departments.*');
            $q->where('departments.status', 1);
            $q->groupBy('departments.id');  
            $q->whereNotIn('departments.id', function ($query) use ($year , $quarter) {
                $query->from('priority_tasks')
                    ->select('dept_id')
                    ->where('status', 1)
                    ->where('year', $year)
                    ->where('quarter_id', $quarter);
            }); 
            $result = $q->get(); 
           return $result->toArray(); 
    }

    function getCurrentQuarterId() {
        $currentDate = new \DateTime();  
        $currentMonth = $currentDate->format('n');  
        $quarterId;
        if ($currentMonth >= 1 && $currentMonth <= 3) {
            $quarterId = 3;
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            $quarterId = 4;
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            $quarterId = 1;
        } else {
            $quarterId = 2;
        } 
        return $quarterId;
    }
    
}
