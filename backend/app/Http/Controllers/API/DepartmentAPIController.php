<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDepartmentAPIRequest;
use App\Http\Requests\API\UpdateDepartmentAPIRequest;
use App\Models\Department;
use App\Models\MosData;
use App\Models\DepartmentCCmail;
use App\Models\MOS;
use App\Models\MonthlyDateRange;
use App\Repositories\DepartmentRepository;
use Illuminate\Http\Request;
use App\Http\Resources\DepartmentActivityResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\MosTreeResource;
use App\Http\Resources\DepartmentTemplatesResource;
use App\Http\Resources\MonthlyDateRangesResource;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\DepartmentAssign;
use Auth;
use DB;

/**
 * Class DepartmentController
 * @package App\Http\Controllers\API
 */

class DepartmentAPIController extends AppBaseController
{
    /** @var  DepartmentRepository */
    private $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepo)
    {
        $this->departmentRepository = $departmentRepo;
    }

    /**
     * Display a listing of the Department.
     * GET|HEAD /departments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user();
        if ($user_data->role_id == 6 ||  $user_data->role_id == 7) {
            $request['id'] = $user_data->dept_id;
        } else {
            $request['id'] = $user_data->dept_id;
        }
        $request['status'] = 1;

        $q = Department::select('departments.*' , 'departments.name as text')
        ->where('status', 1);
        $q->where('iskra', 1);   
        if($user_data->dept_id!=6){ //Note: ignore Hr departments
            if ($user_data->role_id == 6 ||  $user_data->role_id == 7) {
                $q->where('id', $user_data->dept_id);
            } 
            elseif($user_data->role_id != 1 ) {
                $q->whereIn('id', DepartmentAssign::select('dept_id')->where('user_id', $user_data->id)->get()->toArray());
            }
        }     
        $departments = $q->get();

        return $this->sendResponse($departments->toArray(), 'Departments retrieved successfully');
    }
    public function get_departments(Request $request)
    {
        $q = Department::where('status', 1); 
        $departments = $q->limit(2)->get();

        return $this->sendResponse($departments->toArray(), 'Departments retrieved successfully');
    }


    public function department_setting(Request $request)
    {
        $user_data = Auth::user();
        if ($user_data->role_id == 6 ||  $user_data->role_id == 7) {
            $request['id'] = $user_data->dept_id;
        } else {
            $request['id'] = $user_data->dept_id;
        }
        $request['status'] = 1;

        $q = Department::where('status', 1);
        if ($user_data->role_id == 6 ||  $user_data->role_id == 7) {
            $q->where('id', $user_data->dept_id);
        } else {
            $q->whereIn('id', DepartmentAssign::select('dept_id')->where('user_id', $user_data->id)->get()->toArray());
        }
        $departments = $q->get();
        $departments = DepartmentResource::collection($departments);
        return $this->sendResponse($departments, 'Departments retrieved successfully');
    }

    public function department_templates(Request $request)
    {
        $user_data = Auth::user();
        $q = Department::where('status', 1);
        $departments = $q->get();
        $departments = DepartmentTemplatesResource::collection($departments);

        return $this->sendResponse($departments, 'Departments retrieved successfully');
    }

    public function task_templates(Request $request)
    {
        $user_data = Auth::user();
        $q = Department::where('id', $user_data->dept_id);
        $department = $q->first();

        $data = [];
        // return [$department->template_setting , $department->is_factory , $user_data->role_id];
        if($user_data->dept_id == 8 && $user_data->role_id !=5){
            $data['type_name'] = 'Project Wise Task';
            $data['type'] = 3;
        }else if($department->template_setting == 2  && $department->is_factory != 1 && $user_data->role_id == 5 ){
            $data['type_name'] = 'Hod Task ';
            $data['type'] = 2;
        }else if( $department->is_factory == 1 ){ 
            $data['type_name'] = 'Factory Task ';
            $data['type'] = 4;
        }else{
            $data['type_name'] = 'General Task ';
            $data['type'] = 1;
        }
        return $this->sendResponse($data, 'Departments Task Templates Type Retrieved Successfully');
    }    

    public function monthly_date_range(Request $request)
    {
        $user_data = Auth::user();
        $permission_for = $request->permission_for;
        if ($user_data->role_id == 6 ||  $user_data->role_id == 7) {
            $request['id'] = $user_data->dept_id;
        } else {
            $request['id'] = $user_data->dept_id;
        }
        $request['status'] = 1;

        $q = Department::where('status', 1);
        if ($user_data->role_id == 6 ||  $user_data->role_id == 7) {
            $q->where('id', $user_data->dept_id);
        } elseif($user_data->role_id != 1 ) {
            $q->whereIn('id', DepartmentAssign::select('dept_id')->where('user_id', $user_data->id)->get()->toArray());
        }
        $q->with(['monthly_date_ranges'=>function($query)use($permission_for){
            if($permission_for){
                $query->where('permission_for',(int) $permission_for);
            }else{
                $query->where('permission_for',0);
            }
        }]);
        $departments = $q->get();
        $departments = DepartmentResource::collection($departments);
        return $this->sendResponse($departments, 'Departments retrieved successfully');
    }

    public function singel_dept($id, Request $request)
    {
        $department = $this->departmentRepository->find($id);
        $departments = new DepartmentResource($department);
        return $this->sendResponse($departments, 'Department retrieved successfully');
    }

    public function single_permission(Request $request)
    {
        $start_date = date('Y-m-d');    
        $user_data = Auth::user();
        
        $MonthlyDateRange = MonthlyDateRange::select('permission_for')
        ->where('dept_id',$user_data->dept_id)
        ->whereDate('end_date','>=',$start_date)
        ->where('permission_for',1)
        ->groupby('permission_for')
        ->get()->toArray();

        return $this->sendResponse($MonthlyDateRange, 'permission retrieved successfully');
    }

    public function allDept(Request $request)
    {
        $user_data = Auth::user();
        if ($user_data->role_id == 5 || $user_data->role_id == 6 ||  $user_data->role_id == 7) {
            $request['id'] = $user_data->dept_id;
        }
        // $request['status'] = 1 ;

        $departments = $this->departmentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($departments->toArray(), 'Departments retrieved successfully');
    }

    public function dept_report(Request $request)
    {
        $user_data = Auth::user();
        if ($user_data->role_id == 5 || $user_data->role_id == 6 ||  $user_data->role_id == 7) {
            if($user_data->dept_id !=6){
                $request['id'] = $user_data->dept_id;
            } 
        }
        // $request['status'] = 1 ;

        $departments = $this->departmentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($departments->toArray(), 'Departments retrieved successfully');
    }

    public function dept_permission(Request $request)
    {
        $user_data = Auth::user();
        $query =  Department::select('departments.*', 'department_assigns.id as  ass_id');
        $query->where('departments.status', 1);
        $query->where('department_assigns.user_id', $request['user_id']);
        $query->leftjoin('department_assigns', 'department_assigns.dept_id', '=', 'departments.id');
        $departments =  $query->get();
        return $this->sendResponse($departments, 'Departments retrieved successfully');
    }

    public function monthly_activity(Request $request)
    {
        $user_data = Auth::user();
        $q = Department::where('status', 1);
        $q->where('iskra', 1);
        if ($user_data->role_id == 6 ||  $user_data->role_id == 7) {
            $q->where('id', $user_data->dept_id);
        } else {
            $q->whereIn('id', DepartmentAssign::select('dept_id')->where('user_id', $user_data->id)->get()->toArray());
        }

        $departments = $q->get();
        $departments_id =  $departments->pluck('id')->toArray();
        
        $task = MOS::join('k_r_a_s','k_r_a_s.id','m_o_s.kra_id');
        $task->where('k_r_a_s.role_id',5);
        $task->whereIn('m_o_s.dept_id', $departments_id);
        $task->where('m_o_s.year', $request->year);
        $task->orderBy('kra_id', 'ASC');
        $task->orderBy('m_o_s.kpi_id', 'ASC');
        $task->orderBy('m_o_s.id', 'ASC');
        $task->select('m_o_s.*');
        $mos_data = $task->get()->groupBy('dept_id');
      
        foreach ($departments as $key => $value) {
            $result = isset($mos_data[$value->id])?$mos_data[$value->id]:[];
            $data_return = MosTreeResource::collection($result);

            $total_target = 0;
            $total_acheivement = 0;
            $total_score = 0;
            $dept_score = [];

            $monthwise_target_january = 0;
            $monthwise_target_february = 0;
            $monthwise_target_march = 0;
            $monthwise_target_april = 0;
            $monthwise_target_may = 0;
            $monthwise_target_june = 0;
            $monthwise_target_july = 0;
            $monthwise_target_august = 0;
            $monthwise_target_september = 0;
            $monthwise_target_october = 0;
            $monthwise_target_november = 0;
            $monthwise_target_december = 0;



            $monthwise_achievement_january = 0;
            $monthwise_achievement_february = 0;
            $monthwise_achievement_march = 0;
            $monthwise_achievement_april = 0;
            $monthwise_achievement_may = 0;
            $monthwise_achievement_june = 0;
            $monthwise_achievement_july = 0;
            $monthwise_achievement_august = 0;
            $monthwise_achievement_september = 0;
            $monthwise_achievement_october = 0;
            $monthwise_achievement_november = 0;
            $monthwise_achievement_december = 0;

            $monthwise_score_january = 0;
            $monthwise_score_february = 0;
            $monthwise_score_march = 0;
            $monthwise_score_april = 0;
            $monthwise_score_may = 0;
            $monthwise_score_june = 0;
            $monthwise_score_july = 0;
            $monthwise_score_august = 0;
            $monthwise_score_september = 0;
            $monthwise_score_october = 0;
            $monthwise_score_november = 0;
            $monthwise_score_december = 0;


            $monthwise_mos_january = 0;
            $monthwise_mos_february = 0;
            $monthwise_mos_march = 0;
            $monthwise_mos_april = 0;
            $monthwise_mos_may = 0;
            $monthwise_mos_june = 0;
            $monthwise_mos_july = 0;
            $monthwise_mos_august = 0;
            $monthwise_mos_september = 0;
            $monthwise_mos_october = 0;
            $monthwise_mos_november = 0;
            $monthwise_mos_december = 0;

            $total_kpi_weight = 0;
            $total_mos_weightage = 0;
            $value->mos = $data_return;

            if (sizeof($data_return) > 0) {
                foreach ($data_return  as $key2 => $value2) {
                    //if($value2->iskra == '1'){
                    $achievement = $value2->mosachievementjoin($request);
                    $target = $value2->mostargetjoin($request);
                    $kpi = $value2->kpijoin;
                    
                    if (!empty($target)) {
                        if ($value2->weightage != '') {
                            $total_mos_weightage += $value2->weightage;
                        }
                        $monthwise_target_january += $target->january;
                        $monthwise_target_february += $target->february;
                        $monthwise_target_march += $target->march;
                        $monthwise_target_april += $target->april;
                        $monthwise_target_may += $target->may;
                        $monthwise_target_june += $target->june;
                        $monthwise_target_july += $target->july;
                        $monthwise_target_august += $target->august;
                        $monthwise_target_september += $target->september;
                        $monthwise_target_october += $target->october;
                        $monthwise_target_november += $target->november;
                        $monthwise_target_december += $target->december;



                        $monthwise_achievement_january += $achievement->january;
                        $monthwise_achievement_february += $achievement->february;
                        $monthwise_achievement_march += $achievement->march;
                        $monthwise_achievement_april += $achievement->april;
                        $monthwise_achievement_may += $achievement->may;
                        $monthwise_achievement_june += $achievement->june;
                        $monthwise_achievement_july += $achievement->july;
                        $monthwise_achievement_august += $achievement->august;
                        $monthwise_achievement_september += $achievement->september;
                        $monthwise_achievement_october += $achievement->october;
                        $monthwise_achievement_november += $achievement->november;
                        $monthwise_achievement_december += $achievement->december;
                        //}
                        //echo "$target->january  && $achievement->january ||";
                        //echo "$kpi";
                        $total_kpi_weight += $kpi->kpi_weight;

                        if ($target->january > 0) {
                            if ($achievement->january > 0) {
                                //echo " $kpi->weight ||";

                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->january / $achievement->january) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->january / $target->january) * $value2->weightage);
                                } else {
                                    $tscore = 0;
                                }
                            }else{
                                $tscore = 0;
                            }


                            $monthwise_score_january += ($tscore > $value2->weightage ? $value2->weightage : $tscore);

                            $monthwise_mos_january += $value2->weightage;
                            //$monthwise_score_january += 1;
                        }
                        if ($target->february > 0) {
                            if ($achievement->february > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->february / $achievement->february) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->february / $target->february) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_february += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_february += $value2->weightage;
                        }
                        if ($target->march > 0) {
                            if ($achievement->march > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->march / $achievement->march) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->march / $target->march) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_march += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_march += $value2->weightage;
                        }

                        if ($target->april > 0) {
                            if ($achievement->april > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->april / $achievement->april) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->april / $target->april) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_april += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_april += $value2->weightage;
                        }
                        if ($target->may > 0) {
                            if ($achievement->may > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->may / $achievement->may) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->may / $target->may) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_may += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_may += $value2->weightage;
                        }
                        if ($target->june > 0) {
                            if ($achievement->june > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->june / $achievement->june) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->june / $target->june) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_june += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_june += $value2->weightage;
                        }
                        if ($target->july > 0) {
                            if ($achievement->july > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->july / $achievement->july) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->july / $target->july) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_july += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_july += $value2->weightage;
                        }
                        if ($target->august > 0) {
                            if ($achievement->august > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->august / $achievement->august) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->august / $target->august) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_august += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_august += $value2->weightage;
                        }

                        if ($target->september > 0) {
                            if ($achievement->september > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->september / $achievement->september) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->september / $target->september) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_september += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_september += $value2->weightage;
                        }

                        if ($target->october > 0) {
                            if ($achievement->october > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->october / $achievement->october) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->october / $target->october) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_october += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_october += $value2->weightage;
                        }
                        if ($target->november > 0) {
                            if ($achievement->november > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->november / $achievement->november) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->november / $target->november) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_november += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_november += $value2->weightage;
                        }

                        if ($target->december > 0) {
                            if ($achievement->december > 0) {
                                if ($value2->mos_calculation == '1' || $value2->mos_calculation ==  '3') {
                                    $tscore = (($target->december / $achievement->december) * $value2->weightage);
                                } elseif ($value2->mos_calculation == '0' || $value2->mos_calculation == '2' || $value2->mos_calculation == '') {
                                    $tscore = (($achievement->december / $target->december) * $value2->weightage);
                                }
                            } else {
                                $tscore = 0;
                            }
                            $monthwise_score_december += ($tscore > $value2->weightage ? $value2->weightage : $tscore);
                            $monthwise_mos_december += $value2->weightage;
                        }
                    }
                }



                $value->target = array(
                    'january' => $monthwise_target_january,
                    'february' => $monthwise_target_february,
                    'march' => $monthwise_target_march,
                    'april' => $monthwise_target_april,
                    'may' => $monthwise_target_may,
                    'june' => $monthwise_target_june,
                    'july' => $monthwise_target_july,
                    'august' => $monthwise_target_august,
                    'september' => $monthwise_target_september,
                    'october' => $monthwise_target_october,
                    'november' => $monthwise_target_november,
                    'december' => $monthwise_target_december,
                );
                $value->achievement = array(
                    'january' => $monthwise_achievement_january,
                    'february' => $monthwise_achievement_february,
                    'march' => $monthwise_achievement_march,
                    'april' => $monthwise_achievement_april,
                    'may' => $monthwise_achievement_may,
                    'june' => $monthwise_achievement_june,
                    'july' => $monthwise_achievement_july,
                    'august' => $monthwise_achievement_august,
                    'september' => $monthwise_achievement_september,
                    'october' => $monthwise_achievement_october,
                    'november' => $monthwise_achievement_november,
                    'december' => $monthwise_achievement_december,
                );
                $value->score = array(
                    'january' => $monthwise_score_january,
                    'february' => $monthwise_score_february,
                    'march' => $monthwise_score_march,
                    'april' => $monthwise_score_april,
                    'may' => $monthwise_score_may,
                    'june' => $monthwise_score_june,
                    'july' => $monthwise_score_july,
                    'august' => $monthwise_score_august,
                    'september' => $monthwise_score_september,
                    'october' => $monthwise_score_october,
                    'november' => $monthwise_score_november,
                    'december' => $monthwise_score_december,
                );
                $value->mos_weightage = array(
                    'january' => $monthwise_mos_january,
                    'february' => $monthwise_mos_february,
                    'march' => $monthwise_mos_march,
                    'april' => $monthwise_mos_april,
                    'may' => $monthwise_mos_may,
                    'june' => $monthwise_mos_june,
                    'july' => $monthwise_mos_july,
                    'august' => $monthwise_mos_august,
                    'september' => $monthwise_mos_september,
                    'october' => $monthwise_mos_october,
                    'november' => $monthwise_mos_november,
                    'december' => $monthwise_mos_december,
                );
                $value->total_kpi_weight = $total_kpi_weight;
                //$value->mos_weightage = $total_mos_weightage ;
            }
        }
        //echo json_decode(json_encode($departments));exit;
        // $items = DepartmentActivityResource::collection($departments); 
        return $this->sendResponse($departments, 'Departments retrieved successfully 111111');
    }

    /**
     * Store a newly created Department in storage.
     * POST /departments
     *
     * @param CreateDepartmentAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDepartmentAPIRequest $request)
    {
        $input = $request->all();

        $department = $this->departmentRepository->create($input);

        return $this->sendResponse($department->toArray(), 'Department saved successfully');
    }

    public function department_factory(Request $request){
        if($request->checkStatus){
            DB::table('department_factories')
            ->insert([
                'dept_id' => $request->department_id,
                'factory_id' => $request->factory_id
            ]);
        }else{
            DB::table('department_factories')
            ->where('dept_id', $request->department_id)
            ->where('factory_id', $request->factory_id)
            ->delete();
        }
        return $this->sendResponse($request->all(), 'Department retrieved successfully');
    }
    /**
     * Display the specified Department.
     * GET|HEAD /departments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Department $department */
        //$department = $this->departmentRepository->find($id);

        $department = $this->departmentRepository->getListById($id);
        $department['department_factory'] = collect(DB::table('department_factories')
        ->select('factory_id')
        ->where('dept_id', $id)
        ->get())
        ->toArray();

        if (empty($department)) {
            return $this->sendError('Department not found');
        }
 
        return $this->sendResponse($department->toArray(), 'Department retrieved successfully');
    }

    /**
     * Update the specified Department in storage.
     * PUT/PATCH /departments/{id}
     *
     * @param int $id
     * @param UpdateDepartmentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDepartmentAPIRequest $request)
    {
        
        $input = $request->all();
        /** @var Department $department */
        $department = $this->departmentRepository->find($id);
        if (empty($department)) {
            return $this->sendError('Department not found');
        }
        $department = $this->departmentRepository->update($input, $id);

        $departmentFactoryAssignArrayData = array();
        if(isset($request->factory_id)){
            foreach($request->factory_id as $factory){
                $departmentFactoryAssignData['dept_id'] = $id;
                $departmentFactoryAssignData['factory_id'] = $factory;

                $departmentFactoryAssignArrayData[] = $departmentFactoryAssignData;
            }
            DB::table('department_factories')->insert($departmentFactoryAssignArrayData);
        } 
        //DepartmentCCmail::where('dept_id',$id)->delete();
        DB::table('department_c_cmails')->where('dept_id', $id)->delete();
        if(isset($request->cc_users)){ 
            foreach($request->cc_users as $cc_mail){ 
                DepartmentCCmail::create([
                    'dept_id' =>  $id,
                    'user_id' =>  $cc_mail['id'],
                ]);
            } 
          
        }


        return $this->sendResponse($department->toArray(), 'Department updated successfully');
    }

    /**
     * Remove the specified Department from storage.
     * DELETE /departments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Department $department */
        $department = $this->departmentRepository->find($id);

        if (empty($department)) {
            return $this->sendError('Department not found');
        }

        $department->delete();

        return $this->sendSuccess('Department deleted successfully');
    }
}
