<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDailyScheduleAPIRequest;
use App\Http\Requests\API\UpdateDailyScheduleAPIRequest;
use App\Models\DailySchedule;
use App\Models\DepartmentAssign;
use App\Models\Department;
use App\Models\Daily_schedule_header;
use App\Models\DailyScheduleItem;
use App\Http\Resources\DailyScheduleResource;
use App\Http\Resources\TaskReportResource;
use App\Repositories\DailyScheduleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth, DB;
use App\Models\User;
use App\Models\projects;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
use App\Http\Resources\TaskMonthlyResource;
use App\Http\Resources\DailyTaskResource;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersTaskExport;
/**
 * Class DailyScheduleController
 * @package App\Http\Controllers\API
 */

class DailyScheduleAPIController extends AppBaseController
{
    /** @var  DailyScheduleRepository */
    private $dailyScheduleRepository;

    public function __construct(DailyScheduleRepository $dailyScheduleRepo)
    {
        $this->dailyScheduleRepository = $dailyScheduleRepo;
    }

    public function download_file(Request $request){
        $data = array(
            'user_id' => $request->user_id,
            'formDate' => date('Y-m-d' , strtotime($request->date)),
            'todate' => date('Y-m-d' , strtotime($request->todate)),
        );

        return Excel::download(new UsersTaskExport($data), 'users.xlsx');
    }
    /**
     * Display a listing of the DailySchedule.
     * GET|HEAD /dailySchedules
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user();
        $dept_info = Department::find($user_data->dept_id);
        $taskQ = DailySchedule::limit('200');
        $taskQ->select('daily_schedules.*');
        if ((isset($request['date']) && $request['date'] != '') and (isset($request['toDate']) && $request['toDate'] != '')) {
            $taskQ->whereBetween('date', [$request['date'], $request['toDate']]);
        } else {
            $taskQ->whereBetween('date', date('Y-m-d'), date('Y-m-d'));
        }
        if ($user_data->role_id == 5 || $user_data->role_id == 6 || $user_data->role_id == 7) {
            if ($dept_info->is_factory == 1) {
                $taskQ->leftjoin('daily_schedule_headers', 'daily_schedule_headers.id', '=', 'daily_schedules.factory_format_id');
                $taskQ->orderBy('daily_schedule_headers.serialno', 'ASC');
            }
            $taskQ->orderBy('role_id', 'ASC');
            $taskQ->orderBy('top_priority', 'DESC');
            // $user_data 
            if ($request->dept_id) {
                $taskQ->where('daily_schedules.dept_id', $request->dept_id);
            } else {
                $taskQ->where('daily_schedules.dept_id', $user_data->dept_id);
            }

            if ($user_data->role_id == 5) {

                if ($request['wing_id'] && !$request['user_id']) {
                    $taskQ->where('wing_id', $request['wing_id']);
                }
                if ($request['user_id']) {
                    $taskQ->where('user_id', $request['user_id']);
                }
            } else if ($user_data->role_id == 6) {
                $user_data->wing_id;
                $taskQ->where('wing_id', $user_data->wing_id);
                //$request['wing_id'] = $user_data->wing_id ;
            } else if ($user_data->role_id == 7) {
                // $request['user_id'] = $user_data->id ; 
                $taskQ->where('user_id', $user_data->id);
            }
        } else if ($user_data->role_id == 3 || $user_data->role_id == 8 || $user_data->role_id == 1 || $user_data->role_id == 2) {
            $taskQ->orderBy('top_priority', 'DESC');
            if (!$request['dept_id'] && !$request['wing_id']  &&  !$request['user_id']) {
                $taskQ->where('user_id', $user_data->id);
            }

            if ($request['dept_id'] && !$request['wing_id']) {
                if ($request['dept_id'] == 29) { //Note: this condition is for ssg Agro department
                    $taskQ->whereIn('role_id', [1, 2, 3, 4, 5, 6, 8]);
                } else {
                    $taskQ->whereIn('role_id', [1, 2, 3, 4, 5, 8]);
                }
                if ($request['dept_id'] != 'all') {
                    $taskQ->where('daily_schedules.dept_id', $request['dept_id']);
                }
            }
            if ($request['wing_id'] && !$request['user_id']) {

                // $request['role_id'] = 6  ;
                $taskQ->where('daily_schedules.wing_id', $request['wing_id']);
                $taskQ->where('role_id', 6);
            }
            if ($request['user_id']) {
                // $request['role_id'] = 7  ; 
                $taskQ->where('user_id', $request['user_id']);
            }
            if ($user_data->id == 1027) {
                //echo  'Test'; 
                //$task =  DepartmentAssign::select('dept_id')->where('user_id',$user_data->id)->get()->toArray() ; 
                $taskQ->whereIn('daily_schedules.dept_id', DepartmentAssign::select('dept_id')->where('user_id', $user_data->id)->get()->toArray());
            }
        } else {
            $taskQ->where('user_id', $user_data->id);
        }

        //daily_schedule_items
        if ($request->schedule_type) {
            $schedule_type = explode(',', $request->schedule_type);
            if (count($schedule_type)) {
                $taskQ->rightjoin('daily_schedule_items', 'daily_schedule_items.daily_schedules_id', 'daily_schedules.id');
                $taskQ->whereIn('daily_schedule_items.schedule_type_id', $schedule_type);
                $taskQ->groupBy('daily_schedules.id');

                // $taskQ->orderBy('daily_schedule_items.serialno', 'ASC');
            }
        }


        //$task = $taskQ->get();

        $task = $taskQ->orderBy('date', 'DESC')->get();
        // return $task;
        $data_return  =   DailyScheduleResource::collection($task);
        return $this->sendResponse($data_return, 'Schedule retrieved successfully');

        // return $this->sendResponse($dailySchedules->toArray(), 'Daily Schedules retrieved successfully');
    }

    public function daily_task_list(Request $request)
    {
      
        $user_data = Auth::user();
        $dept_info = Department::find($user_data->dept_id);
        $taskQ = DailySchedule::limit('200');
        $taskQ->select('daily_schedules.*');
        $taskQ->whereBetween('date', [$request['date'], $request['toDate']]);
 
        if ($request->dept_id) {
           
                if ($request->dept_id == 'all') {
                    $taskQ->whereIn('daily_schedules.dept_id', DepartmentAssign::select('dept_id')->where('user_id', $user_data->id)->get()->toArray());
                   // $taskQ->where('daily_schedules.dept_id', $request->dept_id);
                }else{
                    $taskQ->where('daily_schedules.dept_id', $request->dept_id);
                }
           
        }else {
            //$taskQ->where('user_id', $user_data->id);
            if ($user_data->dept_id && $user_data->dept_id != 0) {
                 $taskQ->where('daily_schedules.dept_id', $user_data->dept_id);
            }
 
          
            if ($user_data->role_id == 5) {
                if ($request->wing_id) {
                    $taskQ->where('wing_id', $request->wing_id);
                    $taskQ->OrWhere('user_id', $user_data->id);
                }                  
                if ($request['wing_id'] && !$request['user_id']) {
                    $taskQ->where('wing_id', $request['wing_id']);
                }
                if ($request['user_id']) {
                    $taskQ->where('daily_schedules.user_id', $request['user_id']);
                }
            } else if ($user_data->role_id == 6) {
                $user_data->wing_id;
                $taskQ->where('wing_id', $user_data->wing_id);
            } else if ($user_data->role_id == 7) {
                $taskQ->where('daily_schedules.user_id', $user_data->id);
            }
        }

        if ($request->schedule_type) {
            $schedule_type = explode(',', $request->schedule_type);
            if (count($schedule_type)) {
                $taskQ->rightjoin('daily_schedule_items', 'daily_schedule_items.daily_schedules_id', 'daily_schedules.id');
                $taskQ->whereIn('daily_schedule_items.schedule_type_id', $schedule_type);
                $taskQ->groupBy('daily_schedules.id');
            }
        }
        
        $taskQ->orderBy('daily_schedules.role_id', 'ASC');
        $taskQ->orderBy('daily_schedules.wing_id', 'ASC');
        $taskQ->orderBy('top_priority', 'DESC');
        $taskQ->orderBy('user_id', 'desc');
        $task = $taskQ->orderBy('date', 'DESC')->get();
        if(count($task)>0){
            $task = DailyTaskResource::collection($task);
        } 
        return $this->sendResponse($task, 'Schedule retrieved successfully');
    }


    public function today_task_list(Request $request)
    {
        $date =  date('Y-m-d' ,strtotime($request->date));
        $user_data = Auth::user();
        $dept_info = Department::find($user_data->dept_id);
        $taskQ = DailySchedule::limit('1000');
        $taskQ->select('daily_schedules.*');
        $taskQ->where('daily_schedules.user_id', $user_data->id);
        $taskQ->where('date', $date);
        $task = $taskQ->first();
        $data_return = new DailyTaskResource($task);
        return $this->sendResponse($data_return, 'Schedule retrieved successfully');
    }

    public function daily_schedules_list(Request $request)
    {
        $user_data = Auth::user();
        $request->month < 10 ? '0' . $request->month : $request->month;
        $month = $request->month < 10 ? '0' . $request->month : $request->month;
        $date =  $request->year . '-';
        $dept_id =  $request->dept_id;
        $viewQuery =  DailySchedule::select('daily_schedules.id', 'daily_schedules.date', 'daily_schedules.task');

        if ($date) {
            $viewQuery->where('daily_schedules.date', 'like', '%' . $date . '%');
        }
        if ($request->user_id) {
            $viewQuery->where('user_id', $request->user_id);
        }
        if($dept_id){
            $viewQuery->where('dept_id', $dept_id);
        }else {
            $viewQuery->where('user_id', $user_data->id);
        }
        $viewQuery->orderBy('id', 'DESC');
        $viewQuery->groupBy('date');
        $viewQuery->limit(1000);
        $viewList = $viewQuery->get();
        $viewList = TaskMonthlyResource::collection($viewList);
        return $this->sendResponse($viewList, 'Schedule retrieved successfully');
    }
    public function my_daily_schedules(Request $request)
    {
        // $dailySchedules = $this->dailyScheduleRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );
        $user_data = Auth::user();

        $request['user_id'] = $user_data->id;
        if (!$request['date']) {
            $request['date'] =   date('Y-m-d');
        }

        $task = $this->dailyScheduleRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        $data_return  =   DailyScheduleResource::collection($task);
        return $this->sendResponse($data_return, 'Schedule retrieved successfully');


        // return $this->sendResponse($dailySchedules->toArray(), 'Daily Schedules retrieved successfully');
    }

    public function daliy_task_report(Request $request)
    {
        $userList = User::select('users.id', 'users.employee_id', 'users.designation', 'users.name as user_name', 'departments.name as dept_name')->where('users.role_id', 5)
            ->join('departments', 'departments.id', 'users.dept_id')
            ->where('departments.status', 1)
            ->where('users.status', 1)
            ->where('departments.mail_allow', 1)
            ->orderBy('departments.name', 'ASC')
            ->get();
        $data_return = TaskReportResource::collection($userList);
        return $this->sendResponse($data_return, 'Daliy not update List' . $request->date);
    }

    public function daliy_not_update(Request $request)
    {
        $to_date = date('Y-m-d', strtotime($request->date)); // ($request->date != '' ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d'));
        $user_data = Auth::user();
        $taskQ = DailySchedule::limit('200');
        $taskQ->select('daily_schedules.*');

        // $q = User::where('status', 1);
        // $q->where('role_id', 5);
        // if($request->dept_id){
        //     $q->where('dept_id', $request->dept_id);
        // } 
        // $q->where('wing_id', 0);
        // $q->whereNotIn('id', function ($query) use ($to_date) {
        //         $query->select('user_id')->where('date', $to_date)->from('daily_schedules');
        //     });

        // $q->with('deptjoin');
        // //if($user_data->id == 1027){ 
        // $q->whereIn('dept_id', DepartmentAssign::select('dept_id')->where('user_id',$user_data->id)->get()->toArray()); 
        // //} 
        // $data = $q->get();
        $dept = DailySchedule::select('dept_id')->where('date', $to_date)->where('role_id', 5)->get()->toArray();
        $q = DepartmentAssign::select('dept_id')->where('user_id', $user_data->id);
        $q->whereNotIn('dept_id', $dept);
        // function ($query) use ($to_date) {
        //   //  return $this->sendResponse($to_date, 'Daliy not update List');

        //     // $query->select('dept_id')
        //     //     ->where('month', $month)
        //     //     ->where('year', $year)
        //     //     ->from('monthly_reports');
        //    // DailySchedule::select('dept_id')->where('date', $to_date)->where('role_id', 5 )->get();
        //     $query->select('dept_id')->where('date', $to_date)->where('role_id', 5 )->from('daily_schedules');
        // });
        $q->with('deptjoin');
        $data = $q->get();


        return $this->sendResponse($data->toArray(), 'Daliy not update List');
    }

    public function daliy_mail(Request $request)
    {
        $to_date = ($request->to_date != '' ? date('Y-m-d', strtotime($request->to_date)) : date('Y-m-d'));

        // if ($request->dept_selects) {
        // $departments = User::where('status', 1)
        //     ->where('role_id', 5)
        //     ->whereNotIn('id', function ($query) use ($to_date) {
        //         $query->select('user_id')->where('date', $to_date)->from('daily_schedules');
        //     })
        //     ->get();
        $departments =  $request->dept_selects;
        foreach ($departments as $key => $value) {
            $phpMail = new PHPMailer();
            $message = "";
            //print_r($value['deptjoin']['hod_email']);
            // echo  $value->deptjoin->hod_name ;
            // exit; 
            $phpMail->AddAddress($value['deptjoin']['hod_email'],  $value['deptjoin']['hod_name']);
            //$phpMail->AddAddress("sayed@ssgbd.com", "Sayem islam");
            if ($request->mailcc1 != "") {
                $phpMail->AddCC($request->mailcc1, "System CC");
            }
            if ($request->mailcc2 != "") {
                $phpMail->AddCC($request->mailcc2, "System CC");
            }
            if ($request->mailcc3 != "") {
                $phpMail->AddCC($request->mailcc3, "System CC");
            }

            $nextmonth = "";


            $data['nextmonth'] = $to_date;
            $data['all_dept_comm'] = $request->all_dept_comm;
            $message = view('mail.daily_mail')->with(['data' => $data]);

            $user = "Management Desk";
            $user_email = "management.desk@ssgbd.com";

            $phpMail->AddReplyTo("management.desk@ssgbd.com", "Management Desk");

            $msg = nl2br($message);

            $phpMail->FromName = $user;
            $phpMail->From = "management.desk@ssgbd.com";
            $phpMail->Sender = $user_email;
            $phpMail->IsHTML(true);
            $phpMail->Host = "mail.ssgbd.com:25";
            $phpMail->IsSMTP();
            $phpMail->Mailer  = "smtp";
            $phpMail->Subject = "Comment on Daily Task";
            $phpMail->Body = $msg;
            $phpMail->SMTPAuth = false;


            if (!$phpMail->Send()) {
                echo "Message could not be sent.";
                echo "Mailer Error: " . $phpMail->ErrorInfo;
                exit;
            }

            $phpMail->ClearAddresses();
            $phpMail->ClearAttachments();
        }
        // } else {

        //     $departments = User::where('status', 1)
        //         ->where('role_id', 5)
        //         ->whereNotIn('id', function ($query) use ($to_date) {
        //             $query->select('user_id')->where('date', $to_date)->from('daily_schedules');
        //         })
        //         ->get();
        //     foreach ($departments as $key => $value) {
        //         if (isset($request->dept_check[$value->id])) {

        //             $phpMail = new PHPMailer();
        //             $message = "";
        //             $phpMail->AddAddress($value->ad_mail, $value->name);
        //             $phpMail->AddAddress("sazzadul.islam@ssgbd.com", "Sazzadul islam");
        //             if ($request->mailcc1 != "") {
        //                 $phpMail->AddCC($request->mailcc1, "System CC");
        //             }
        //             if ($request->mailcc2 != "") {
        //                 $phpMail->AddCC($request->mailcc2, "System CC");
        //             }
        //             if ($request->mailcc3 != "") {
        //                 $phpMail->AddCC($request->mailcc3, "System CC");
        //             }
        //             $data['nextmonth'] = $to_date;
        //             $data['all_dept_comm'] = $request->comm[$value->id];
        //             $message = view('mail.daily_mail')->with(['data' => $data]);

        //             $user = "Management Desk";
        //             $user_email = "management.desk@ssgbd.com";

        //             $phpMail->AddReplyTo("management.desk@ssgbd.com", "Management Desk");

        //             $msg = nl2br($message);

        //             $phpMail->FromName = $user;
        //             $phpMail->From = "management.desk@ssgbd.com";
        //             $phpMail->Sender = $user_email;
        //             $phpMail->IsHTML(true);
        //             $phpMail->Host = "mail.ssgbd.com:25";
        //             $phpMail->IsSMTP();
        //             $phpMail->Mailer  = "smtp";
        //             $phpMail->Subject = "Comment on Daily Task";
        //             $phpMail->Body = $msg;
        //             $phpMail->SMTPAuth = false;


        //             if (!$phpMail->Send()) {
        //                 echo "Message could not be sent.";
        //                 echo "Mailer Error: " . $phpMail->ErrorInfo;
        //                 exit;
        //             }

        //             $phpMail->ClearAddresses();
        //             $phpMail->ClearAttachments();
        //         }

        //     }
        // }
        return $this->sendResponse([], 'Mail send successfully');
    }

    public function getTask()
    {
        return 9;
    }

    /**
     * Store a newly created DailySchedule in storage.
     * POST /dailySchedules
     *
     * @param CreateDailyScheduleAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDailyScheduleAPIRequest $request){        
        $user_data = Auth::user();
        $request['user_id'] = $user_data->id;
        // return $request->all();
        if (!$request['date']) {
            $request['date'] = date("Y-m-d");
        }

        if ($user_data->role_id == 6 || $user_data->role_id == 7) {
            $request['wing_id'] = $user_data->wing_id;
        }

        if ($user_data->role_id == 6 || $user_data->role_id == 7) {
            $request['wing_id'] = $user_data->wing_id;
        }

        $request['dept_id'] = $user_data->dept_id ? $user_data->dept_id : null;
        $request['user_id'] = $user_data->id;
        $request['role_id'] = $user_data->role_id;

        $input = $request->all();
        $data = [];
        $dailySchedule = $this->dailyScheduleRepository->create($input);
        if ($user_data->role_id == 1 || $user_data->role_id == 2 || $user_data->role_id == 3 || $user_data->role_id == 4 
        || $user_data->role_id == 5 || $user_data->role_id == 6 || $user_data->role_id == 7) {
            if ($request->tasks) {
                $tasks = $request->tasks;
                for ($i = 0; $i < count($tasks); $i++) {
                    $tasks[$i]['daily_schedules_id'] = $dailySchedule->id;
                    //return $this->sendResponse( $othersSchedules , 'Test Daily Schedule saved successfully');
                    if ($tasks[$i]['task'] != '') {
                        if($user_data->role_id == 5){
                            $work_type = 0;
                            $task_type = 0;
                        }else{
                            $work_type = (isset($tasks[$i]['work_type']) && ($tasks[$i]['work_type']) == 1)?1:0;
                            $task_type = (isset($tasks[$i]['task_type']) && ($tasks[$i]['task_type']) == 1)?1:0;
                        }
                        $currentTime = strtotime('08:30 am');
                        $formattedTime = date('h:i a', $currentTime);

                        $data[$i] = DailyScheduleItem::create([
                            'daily_schedules_id' => $dailySchedule->id,
                            'schedule_type_id' => $tasks[$i]['schedule_type_id'],
                            'task' => $tasks[$i]['task'],
                            'start_time' => isset($tasks[$i]['start_time'])?$tasks[$i]['start_time']:$formattedTime,
                            'end_time' => isset($tasks[$i]['end_time'])?$tasks[$i]['end_time']:$formattedTime,
                            'duration' => $tasks[$i]['duration'],
                            'top_priority' => $tasks[$i]['top_priority'],
                            'project_id' => isset($tasks[$i]['project_id'])?$tasks[$i]['project_id']:0,
                            'department' => isset($tasks[$i]['department'])?$tasks[$i]['department']:0,
                            'work_type' => $work_type,
                            'task_type' => $task_type,
                        ]);
                    }
                    // return $this->sendResponse( $othersSchedules , 'Test Daily Schedule saved successfully');
                }
            }
        }
        return $this->sendResponse($data, 'Daily Schedule saved successfully');
    }

    /**
     * Display the specified DailySchedule.
     * GET|HEAD /dailySchedules/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DailySchedule $dailySchedule */
        $dailySchedule = $this->dailyScheduleRepository->find($id);

        if (empty($dailySchedule)) {
            return $this->sendError('Daily Schedule not found');
        }
        $dailySchedule = new DailyTaskResource($dailySchedule);
        return $this->sendResponse($dailySchedule, 'Daily Schedule retrieved successfully');
    }

    /**
     * Update the specified DailySchedule in storage.
     * PUT/PATCH /dailySchedules/{id}
     *
     * @param int $id
     * @param UpdateDailyScheduleAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDailyScheduleAPIRequest $request)
    {
        // return $request->all();
        $input = $request->all();
        unset($input['date']);

        /** @var DailySchedule $dailySchedule */
        $dailySchedule = $this->dailyScheduleRepository->find($id);

        if (empty($dailySchedule)) {
            return $this->sendError('Daily Schedule not found');
        }
        if ($request->tasks) {
            $tasks = $request->tasks;
            for ($i = 0; $i < count($tasks); $i++) {
                $tasks[$i]['daily_schedules_id'] = $dailySchedule->id;

                if ($tasks[$i]['task'] != '') {
                    $dataTask = [
                        'daily_schedules_id' => $dailySchedule->id,
                        'schedule_type_id' => $tasks[$i]['schedule_type_id'],
                        'task' => $tasks[$i]['task'],
                        'start_time' => $tasks[$i]['start_time'],
                        'end_time' => $tasks[$i]['end_time'],
                        'duration' => $tasks[$i]['duration'],
                        'top_priority' => $tasks[$i]['top_priority'],
                        'project_id' => isset($tasks[$i]['project_id'])?$tasks[$i]['project_id']:0,
                        'work_type' => (isset($tasks[$i]['work_type']) && ($tasks[$i]['work_type']) == 1)?1:0,
                        'task_type' => (isset($tasks[$i]['task_type']) && ($tasks[$i]['task_type']) == 1)?1:0,                    
                    ]; 

                    if (isset($tasks[$i]['id'])) {
                        DailyScheduleItem::where("id", $tasks[$i]['id'])->update($dataTask);
                    } else {
                        DailyScheduleItem::create($dataTask);
                    }
                }
                // return $this->sendResponse( $othersSchedules , 'Test Daily Schedule saved successfully');

            }
        }

        $dailySchedule = $this->dailyScheduleRepository->update($input, $id);

        return $this->sendResponse($dailySchedule->toArray(), 'DailySchedule updated successfully');
    }

    /**
     * Remove the specified DailySchedule from storage.
     * DELETE /dailySchedules/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DailySchedule $dailySchedule */
        $dailySchedule = $this->dailyScheduleRepository->find($id);
        if (empty($dailySchedule)) {
            return $this->sendError('Daily Schedule not found');
        }
        $dailySchedule->delete();
        $dataDelete = DailyScheduleItem::where('daily_schedules_id' , $id)->delete();
        return $this->sendSuccess('Daily Schedule deleted successfully');
    }
}
