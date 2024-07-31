<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePriorityTaskAPIRequest;
use App\Http\Requests\API\UpdatePriorityTaskAPIRequest;
use App\Models\PriorityTask;
use App\Models\DepartmentAssign;
use App\Models\PriorityTaskItem;
use App\Models\PriorityTaskItemsLog;
use App\Repositories\PriorityTaskRepository;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\PriorityTaskResource;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth ;

/**
 * Class PriorityTaskController
 * @package App\Http\Controllers\API
 */

class PriorityTaskAPIController extends AppBaseController
{
    /** @var  PriorityTaskRepository */
    private $priorityTaskRepository;

    public function __construct(PriorityTaskRepository $priorityTaskRepo)
    {
        $this->priorityTaskRepository = $priorityTaskRepo;
    }

    /**
     * Display a listing of the PriorityTask.
     * GET|HEAD /priorityTasks
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user();

        if($user_data->role_id >  5){
            return $this->sendResponse(0, 'Priority Task not found');
        } 
        
        $query = PriorityTask::query();
        
        $query->whereIn('dept_id', DepartmentAssign::select('dept_id')->where('user_id', $user_data->id)->get()->toArray());
        if($request->dept_id){
            $query->where('dept_id',$request->dept_id);
        }
       
        
        $query->where('quarter_id',$request->quarter);
        $query->where('year',$request->year);
        $priorityTasks = $query->get(); 

        $data_return  =   PriorityTaskResource::collection($priorityTasks);
       
        return $this->sendResponse($data_return, 'Priority Tasks retrieved successfully');
    }

    public function priority_task_not_update(Request $request){
        $user_data = Auth::user();

        $q = Department::select('departments.*');
          
            $q->where('departments.status', 1);
            $q->groupBy('departments.id');
            $q->whereIn('departments.id', function ($query) use ($user_data) {
                $query->from('department_assigns')
                    ->select('dept_id')
                    ->where('user_id', $user_data->id);
            });
            if($request->status == 'No'){
                $q->whereNotIn('departments.id', function ($query) use ($request) {
                    $query->from('priority_tasks')
                        ->select('dept_id')
                        ->where('status', 1)
                        ->where('year', $request->year)
                        ->where('quarter_id', $request->quarter);
                });
            }else{
                $q->whereIn('departments.id', function ($query) use ($request) {
                    $query->from('priority_tasks')
                        ->select('dept_id')
                        ->where('status', 1)
                        ->where('year', $request->year)
                        ->where('quarter_id', $request->quarter);
                });
            }
            

            
            $result = $q->get(); 
    
        return $this->sendResponse($result->toArray(), 'Priority Tasks retrieved successfully');
    }
    

    public function  priority_task_logs(Request $request){

        $data  =  PriorityTaskItemsLog::where('priority_task_item_id', $request->priority_task_item_id)->get();
 
        return $this->sendResponse( $data->toArray() , 'Priority Tasks retrieved successfully');
    }

    /**
     * Store a newly created PriorityTask in storage.
     * POST /priorityTasks
     *
     * @param CreatePriorityTaskAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePriorityTaskAPIRequest $request)
    {
        $year  =  2024 ;
        $user_data = Auth::user();

        if(PriorityTask::where('dept_id', $user_data->dept_id)
        ->where('quarter_id', $request->quarter)
        ->where('year', $year)
        ->exists()){
            return $this->sendResponse( 0 , 'This quarterly priority task already exists');

        }
        
        $input = $request->all(); 
        
        $priorityTask =  PriorityTask::create([ 
            'dept_id' => $user_data->dept_id, 
            'quarter_id' => $request->quarter ,
            'status' => 1,
            'year' => $year , //$request->year ? $request->year : 2024,
            'created_by' => $user_data->id
        ]);

        if ($request->tasks) {
                $tasks = $request->tasks;
                for ($i = 0; $i < count($tasks); $i++) {
                   // $tasks[$i]['daily_schedules_id'] = $dailySchedule->id;
                    //return $this->sendResponse( $othersSchedules , 'Test Daily Schedule saved successfully');
                    
                    if ($tasks[$i]['task'] != '') {
                        
                        $data[$i] = PriorityTaskItem::create([
                            'priority_task_id' => $priorityTask->id, 
                            'task' => $tasks[$i]['task'],
                            'jan' => $tasks[$i]['jan'],
                            'feb' => $tasks[$i]['feb'],
                            'mar' => $tasks[$i]['mar'],
                            'apr' => $tasks[$i]['apr'],
                            'may' => $tasks[$i]['may'],
                            'jun' => $tasks[$i]['jun'],
                            'jul' => $tasks[$i]['jul'],
                            'aug' => $tasks[$i]['aug'],
                            'sep' => $tasks[$i]['sep'],
                            'oct' => $tasks[$i]['oct'],
                            'nov' => $tasks[$i]['nov'],
                            'dec' => $tasks[$i]['dec'],
                            'quarter_weightage' => $tasks[$i]['quarter_weightage'],
                            'quarter_achiv' => $tasks[$i]['quarter_achiv'],
                            'half_year_weightage'=> $tasks[$i]['half_year_weightage'],
                            'year_weightage'=> $tasks[$i]['year_weightage'],
                            'priority_value'=> $i + 1, 
                            'created_by' => $user_data->id 
                            
                        ]);
                    }
                    // return $this->sendResponse( $othersSchedules , 'Test Daily Schedule saved successfully');
                }
            }
       
        return $this->sendResponse($data, 'Task saved successfully'); 
    }
    public function priority_task_items_update(Request $request){
        $user_data = Auth::user();
        
        $input = $request->all(); 
        if ($request->tasks) {
                $tasks = $request->tasks;
                for ($i = 0; $i < count($tasks); $i++) {
                   // $tasks[$i]['daily_schedules_id'] = $dailySchedule->id;
                    //return $this->sendResponse( $othersSchedules , 'Test Daily Schedule saved successfully');
                     
                    if ($tasks[$i]['task'] != '') { 
                        $resData  = [ 
                            'task' => $tasks[$i]['task'],
                            'jan' => $tasks[$i]['jan'],
                            'feb' => $tasks[$i]['feb'],
                            'mar' => $tasks[$i]['mar'],
                            'apr' => $tasks[$i]['apr'],
                            'may' => $tasks[$i]['may'],
                            'jun' => $tasks[$i]['jun'],
                            'jul' => $tasks[$i]['jul'],
                            'aug' => $tasks[$i]['aug'],
                            'sep' => $tasks[$i]['sep'],
                            'oct' => $tasks[$i]['oct'],
                            'nov' => $tasks[$i]['nov'],
                            'dec' => $tasks[$i]['dec'],
                            'quarter_weightage' => $tasks[$i]['quarter_weightage'],
                            'quarter_achiv' => $tasks[$i]['quarter_achiv'],
                            'half_year_weightage'=> $tasks[$i]['half_year_weightage'],
                            'year_weightage'=> $tasks[$i]['year_weightage'],
                            'updated_by' => $user_data->id  
                            
                        ];
                        if ($tasks[$i]['id']  == 0) { 
                            $resData['priority_task_id'] =  $request->id; ;
                            $resData['priority_value'] =   $i + 1; 
                            $resData['created_by'] =   $user_data->id ; 
                            $createRes = PriorityTaskItem::create($resData);
                            $resData['action_type'] = 'create' ;
                            $resData['priority_task_item_id'] =  $createRes->id ;
                            PriorityTaskItemsLog::create($resData);

                            $data[$i] = $createRes;
                        }else{
 
                         
                            $existingData = PriorityTaskItem::find($tasks[$i]['id']);

                            // $existingDataArray = $existingData->toArray();
                            // unset($existingDataArray['id']); // Remove id for comparison
                            // unset($existingDataArray['created_at']); // Remove created_at for comparison
                            // unset($existingDataArray['updated_at']); // Remove updated_at for comparison
        
                            // if ($existingDataArray != $resData) {
                            //     // Create a log entry since there are changes
                            //     PriorityTaskItemsLog::create($resData);
                            // }

                            if ($existingData->task != $resData['task'] ||
                                $existingData->jan != $resData['jan'] ||
                                $existingData->feb != $resData['feb'] ||
                                $existingData->mar != $resData['mar'] ||
                                $existingData->apr != $resData['apr'] ||
                                $existingData->may != $resData['may'] ||
                                $existingData->jun != $resData['jun'] ||
                                $existingData->jul != $resData['jul'] ||
                                $existingData->aug != $resData['aug'] ||
                                $existingData->sep != $resData['sep'] ||
                                $existingData->oct != $resData['oct'] ||
                                $existingData->nov != $resData['nov'] ||
                                $existingData->dec != $resData['dec'] ||
                                $existingData->quarter_weightage != $resData['quarter_weightage'] ||
                                $existingData->quarter_achiv != $resData['quarter_achiv'] ||
                                $existingData->half_year_weightage != $resData['half_year_weightage'] ||
                                $existingData->year_weightage != $resData['year_weightage']
                            ) {
                                // Create a log entry since there are changes
                                $logData  =  $resData ;
                                $logData['priority_task_id'] = $existingData->priority_task_id ;
                                $logData['priority_value'] = $existingData->priority_value ;
                                $logData['created_by'] = $user_data->id ;
                                $logData['priority_task_item_id'] = $existingData->id  ;
                                $logData['action_type'] = 'update' ;   
                                PriorityTaskItemsLog::create($logData);
                            }


                            $data[$i] = PriorityTaskItem::where('id',$tasks[$i]['id'])->update($resData);
                        }
                       
                    }
                    
                }
            }
       
        return $this->sendResponse($data, 'Task saved successfully'); 
    }
     


    /**
     * Display the specified PriorityTask.
     * GET|HEAD /priorityTasks/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show_quarter(Request $request ,$id)
    {
        $user_data = Auth::user();
        $priorityTask = PriorityTask::where('quarter_id',$id)
        ->where('year', 2024)
        ->where('dept_id', $user_data->dept_id)
        ->first();
        //$priorityTask = $this->priorityTaskRepository->find($id);

        if (empty($priorityTask)) {
            return $this->sendError('Priority Task not found');
        }

       
        $data_return = new PriorityTaskResource($priorityTask);
       
        return $this->sendResponse($data_return, 'Priority Tasks retrieved successfully');
 
    }

    public function show($id)
    {
      
        $priorityTask = $this->priorityTaskRepository->find($id);

        if (empty($priorityTask)) {
            return $this->sendError('Priority Task not found');
        }
 
        $data_return = new PriorityTaskResource($priorityTask);
       
        return $this->sendResponse($data_return, 'Priority Tasks retrieved successfully');
 
    }

    /**
     * Update the specified PriorityTask in storage.
     * PUT/PATCH /priorityTasks/{id}
     *
     * @param int $id
     * @param UpdatePriorityTaskAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePriorityTaskAPIRequest $request)
    {
        $input = $request->all();

        /** @var PriorityTask $priorityTask */
        $priorityTask = $this->priorityTaskRepository->find($id);

        if (empty($priorityTask)) {
            return $this->sendError('Priority Task not found');
        }

        $priorityTask = $this->priorityTaskRepository->update($input, $id);

        return $this->sendResponse($priorityTask->toArray(), 'PriorityTask updated successfully');
    }

    /**
     * Remove the specified PriorityTask from storage.
     * DELETE /priorityTasks/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PriorityTask $priorityTask */
        $priorityTask = $this->priorityTaskRepository->find($id);

        if (empty($priorityTask)) {
            return $this->sendError('Priority Task not found');


        }

        $priorityTask->delete();
        PriorityTaskItem::where('priority_task_id', $id)->delete();

        return $this->sendSuccess('Priority Task deleted successfully');
    }
}
