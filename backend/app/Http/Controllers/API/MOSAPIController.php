<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMOSAPIRequest;
use App\Http\Requests\API\UpdateMOSAPIRequest;
use App\Models\MOS;
use App\Models\KRA;
use App\Models\KPI;
use App\Models\MosData;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\UesrMos;
use App\Models\Wing;
use App\Models\MosFeadback;
use App\Repositories\MOSRepository;
use App\Repositories\MosDataRepository;
use App\Http\Resources\MosTreeResource;
use App\Http\Resources\MosTreeResourceUnassign;
use App\Http\Resources\KraEmployeeWiseResource;
use App\Http\Resources\KraResource;
use App\Http\Resources\KpiResource;
use App\Http\Resources\MosEmployeeWiseResource;
use App\Http\Resources\ConfirmationMosWeightage;
use App\Http\Resources\MosItemResource;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\KpiTreeResource;
use App\Http\Resources\MosHistoryResource;
use App\Models\Department;
use Response;
use DB;
use Auth;
use URL;
use App\Models\MosDataLog;
use App\Models\DepartmentSetting;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class MOSController
 * @package App\Http\Controllers\API
 */

class MOSAPIController extends AppBaseController
{
    /** @var  MOSRepository */
    private $mOSRepository;
    private $mosDataRepository;

    public function __construct(
        MOSRepository $mOSRepo,
        MosDataRepository $mosDataRepo
    ) {
        $this->mOSRepository = $mOSRepo;
        $this->mosDataRepository = $mosDataRepo;
    }

    /**
     * Display a listing of the MOS.
     * GET|HEAD /mOS
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $mOS = $this->mOSRepository->all(
            $request->except(["skip", "limit"]),
            $request->get("skip"),
            $request->get("limit")
        );
        return $this->sendResponse(
            $mOS->toArray(),
            "M O S retrieved successfully"
        );
    }
    public function kra_kpi_mos_list2(Request $request)
    {
    }
    public function get_achievement(Request $request)
    {
        $mOS = MOS::find($request->id);
        $data_return = new MosHistoryResource($mOS);
        return $this->sendResponse(
            $data_return,
            "M O S retrieved successfully"
        );
        // $mOS = MosDataLog::where('mos_id',$request->id)->where('permission_months',$request->month)->get();
        // $data_return = AchievementHistoryResource::collection($mOS);
        // return $this->sendResponse($data_return, 'M O S retrieved successfully');
    }

    public function get_rep_mos(Request $request)
    {
        $KPI = KPI::where('rep_id', $request->id)->get();
        if (count($KPI) == 0) {
            $request['checkMos'] = true;
            $KPI = KPI::select('k_p_i_s.*')
                ->join('m_o_s', 'm_o_s.kpi_id', 'k_p_i_s.id')
                ->where('m_o_s.rep_id', $request->id)
                ->groupBy('m_o_s.kpi_id')
                ->get();
            $KPI = KpiResource::collection($KPI);
            // return $data;
        }
        $data = KpiResource::collection($KPI);
        return $this->sendResponse(
            $data,
            "M O S retrieved successfully"
        );
    }

    public function submit_rep_mos(Request $request)
    {
        $items = $request->item;
        foreach ($items as $key => $item) {
            foreach ($item['mosjoin'] as $key => $mos) {
                $mos_data = MOS::find($mos['id']);
                $mos_data->rep_per = $mos['rep_per'];
                $mos_data->save();
            }
        }
        return $this->sendResponse(
            $items,
            "M O S Share successfully"
        );
    }

    public function assign_mos_submit(Request $request)
    {

        $items = $request->item;
        $rep_mos = $request->rep_mos;
        $assign_kpi = $request->kpi_id;

        $KPI = KPI::find($assign_kpi);
        $KPI->rep_id = $rep_mos['id'];
        $KPI->save();

        foreach ($items as $key => $item) {
            $mos_data = MOS::find($item['id']);
            $mos_data->rep_per = $item['rep_per'];
            $mos_data->save();
        }

        return $this->sendResponse(
            $items,
            "M O S Share successfully"
        );
    }


    public function kra_kpi_mos_hris(Request $request)
    {
        $employee = User::where("email", $request->emplyee_id)
            ->orWhere("employee_id", $request->employee_id)
            ->first();
        $task = MOS::limit(300);
        if ($request->year) {
            $task->where("year", $request->year);
        }
        if ($request->emplyee_id) {
            $task->whereIn(
                "id",
                UesrMos::select("mos_id")
                    ->where("user_id", $employee->id)
                    ->get()
                    ->toArray()
            );
            //$task->where('user_id',$request->user_id );
        }
        $task->orderBy("kra_id", "ASC");
        $task->orderBy("kpi_id", "ASC");
        $task->orderBy("id", "ASC");
        $result = $task->get();

        $data_return = MosTreeResource::collection($result);
        return $this->sendResponse(
            $data_return,
            "K P I S retrieved successfully"
        );
    }
    public function kra_kpi_mos_hris_weightage(Request $request)
    {
        $employee = User::where("email", $request->employee_id)
            ->orWhere("employee_id", $request->employee_id)
            ->first();

        if ($request->year != 2023) { //Note: Except 2023
            if ($employee) {
                $query = KRA::where("year", $request->year);
                if ($employee->role_id == 5) {
                    $query->where("role_id", 5);
                    $query->where("dept_id", $employee->dept_id);
                } else {
                    $query->where("user_id", $employee->id);
                }
                $result = $query->get();
                $data_return = KraEmployeeWiseResource::collection($result);

                echo json_encode($data_return);
            } else {
                echo $massage =
                    "KPI data not available, please update your KPI from BPT";
            }
        } else { //Note:Only For 2023 year 
            self::kra_kpi_mos_hris_weightage_2023_2023($request, $employee);
        }
        //return $this->sendResponse($data_return, 'K P I S retrieved successfully');
    }

    public function kra_kpi_mos_hris_weightage_2023_2023($request, $employee)
    {
        //Note: This Calculation Is for 2022
        $query = KRA::where("year", 2022);
        if ($employee->role_id == 5) {
            $query->where("role_id", 5);
            $query->where("dept_id", $employee->dept_id);
        } else {
            $query->where("user_id", $employee->id);
        }
        $result = $query->get();
        $data_return = KraEmployeeWiseResource::collection($result);

        //Note: This Calculation Is for 2023
        $queryCurrentYear = KRA::where("year", 2023);
        if ($employee->role_id == 5) {
            $queryCurrentYear->where("role_id", 5);
            $queryCurrentYear->where("dept_id", $employee->dept_id);
        } else {
            $queryCurrentYear->where("user_id", $employee->id);
        }

        $resultCurrentYear = $queryCurrentYear->get();
        $dataCurrentYear = KraEmployeeWiseResource::collection($resultCurrentYear);

        $data = [
            'year_2022' => $data_return,
            'year_2023' => $dataCurrentYear,
        ];

        echo json_encode($data);
    }

    //kra_kpi_mos_hris_weightage_2022

    public function kra_kpi_mos_hris_weightage_2022(Request $request)
    {
        //Note: This Calculation Is for 2022
        $employee = User::where("email", $request->employee_id)
            ->orWhere("employee_id", $request->employee_id)
            ->first();
        try {
            if (empty($employee)) {
                $data = [
                    'msg' => 'No User Data Found In Bpt'
                ];
                return json_encode($data);
            }
            $query = KRA::where("year", 2022);
            if ($employee->role_id == 5) {
                $query->where("role_id", 5);
                $query->where("dept_id", $employee->dept_id);
            } else {
                $query->where("user_id", $employee->id);
            }
            $result = $query->get();
            $data_return = KraEmployeeWiseResource::collection($result);
            echo json_encode($data_return);
        } catch (\ErrorException $e) {

            $data = [
                'msg' => 'Internal Server Error'
            ];
            return json_encode($data);
        } catch (\Exception $e) {
            $data = [
                'msg' => 'Upps!! Someting went wrong'
            ];
            return json_encode($data);
        }
    }

    public function kra_kpi_mos_hris_weightage_2023(Request $request)
    {
        //Note: This Calculation Is for 2022
        $employee = User::where("email", $request->employee_id)
            ->orWhere("employee_id", $request->employee_id)
            ->first();
        try {
            if (empty($employee)) {
                $data = [
                    'msg' => 'No User Data Found In Bpt'
                ];
                return json_encode($data);
            }
            //Note: This Calculation Is for 2023
            $queryCurrentYear = KRA::where("year", 2023);
            if ($employee->role_id == 5) {
                $queryCurrentYear->where("role_id", 5);
                $queryCurrentYear->where("dept_id", $employee->dept_id);
            } else {
                $queryCurrentYear->where("user_id", $employee->id);
            }

            $resultCurrentYear = $queryCurrentYear->get();
            $dataCurrentYear = KraEmployeeWiseResource::collection($resultCurrentYear);
            echo json_encode($dataCurrentYear);
        } catch (\ErrorException $e) {
            $data = [
                'msg' => 'Internal Server Error'
            ];
            return json_encode($data);
        } catch (\Exception $e) {
            $data = [
                'msg' => 'Upps!! Someting went wrong'
            ];
            return json_encode($data);
        }
    }

    public function ConfirmationMosWeightage(Request $request)
    {
        $employee = User::where("email", $request->employee_id)
            ->orWhere("employee_id", $request->employee_id)
            ->first();

        
        if ($employee && $employee->employee_status == 0) { 

            $query = KRA::where("user_id", $employee->id)
                ->join('m_o_s', 'm_o_s.kra_id', '=', 'k_r_a_s.id')
                ->select('k_r_a_s.*') // Select the columns you need from the KRA table
                ->whereNull('m_o_s.deleted_at') // Condition for m_o_s.deleted_at IS NULL
                ->havingRaw('COUNT(m_o_s.id) > 0')
                ->orderBy('k_r_a_s.year','ASC')
                ->orderBy('k_r_a_s.id','ASC')
                ->groupBy('k_r_a_s.id');  
            $result = $query->get(); 

        $result = $query->get();

            return $data_return = KraEmployeeWiseResource::collection($result);
            // echo json_encode($data_return);
        } else {
            
            $request->merge(['year' => date("Y") ]); 
            $request->merge(['foid' => $request->employee_id]); 
            $request->merge(['type' => 1]);          
            $this->fo_performance_month_wise($request); 

            $employee = User::where("email", $request->employee_id)
            ->orWhere("employee_id", $request->employee_id)
            ->first(); 

            $query = KRA::where("user_id", $employee->id)
                ->join('m_o_s', 'm_o_s.kra_id', '=', 'k_r_a_s.id')
                ->select('k_r_a_s.*') // Select the columns you need from the KRA table
                ->whereNull('m_o_s.deleted_at') // Condition for m_o_s.deleted_at IS NULL
                ->havingRaw('COUNT(m_o_s.id) > 0')
                ->orderBy('k_r_a_s.year','ASC')
                ->orderBy('k_r_a_s.id','ASC')
                ->groupBy('k_r_a_s.id');  
            $result = $query->get();
            return $data_return = KraEmployeeWiseResource::collection($result);
            //echo json_encode($data_return);


            // echo $massage =
            //     "KPI data not available, please update your KPI from BPT";
        }
    }
    public function mosValue($employee, $year)
    {
        if ($year) {
            //return $this->sendResponse($year , 'Test  : MOS retrieved successfully');

            $query = KRA::where("year", $year ? $year : date("Y"));
            if ($employee->role_id == 5) {
                $query->where("role_id", 5);
                $query->where("dept_id", $employee->dept_id);
            } else {
                $query->where("user_id", $employee->id);
            }
            $result = $query->get();
            return $data_return = ConfirmationMosWeightage::collection($result);
        }
    }

    public function kra_hris_weightage(Request $request)
    {
        $employee = User::where("email", $request->employee_id)
            ->orWhere("employee_id", $request->employee_id)
            ->first();
        if ($employee) {
            $result_kra = KRA::select("id", "kra_name", "kra_weight")
                ->where("year", $request->year)
                ->where("user_id", $employee->id)
                ->get();

            $result_kpi = KPI::select(
                "k_p_i_s.id",
                "k_p_i_s.kra_id",
                "kpi_name",
                "kpi_weight"
            )
                ->where("k_r_a_s.user_id", $employee->id)
                ->join("k_r_a_s", "k_p_i_s.kra_id", "k_r_a_s.id")
                ->where("k_r_a_s.year", $request->year)
                ->get();

            $result_mos = MOS::select("m_o_s.*")
                ->where("k_r_a_s.user_id", $employee->id)
                ->join("k_r_a_s", "m_o_s.kra_id", "k_r_a_s.id")
                ->where("k_r_a_s.year", $request->year)
                ->get();

            $result_mos = MosEmployeeWiseResource::collection($result_mos);

            $DataReturn = [
                "kra" => $result_kra,
                "kpi" => $result_kpi,
                "mos" => $result_mos,
            ];
            echo json_encode($DataReturn);
        } else {
            echo $massage =
                "KPI data not available, please update your KPI from BPT";
            //echo json_encode([]);
        }

        //    echo json_encode($data_return);
        //     return $data_return ;
        // return $this->sendResponse($employee, 'K P I S retrieved successfully');
    }

    public function dept_sync()
    {
        $results = MOS::select("mos_datas.*")
            ->where("k_r_a_s.year", "2021")
            ->where("k_r_a_s.role_id", "5")
            ->join("k_r_a_s", "k_r_a_s.id", "m_o_s.kra_id")
            ->join("mos_datas", "mos_datas.id", "m_o_s.id")
            ->get();
        foreach ($results as $key => $value) {
            $totalValue =
                $value->january +
                $value->february +
                $value->march +
                $value->april +
                $value->may +
                $value->june +
                $value->july +
                $value->august +
                $value->september +
                $value->october +
                $value->november +
                $value->december;
            MosData::where("id", $value->id)->update(["total" => $totalValue]);
        }

        return $this->sendResponse($results, "MOS retrieved successfully");
    }
    public function kra_kpi_delete(Request $request){
        $employeeIds = $request->employee_id;
        $year = $request->year;
        
        // $KRARestore = KRA::onlyTrashed()
        // ->whereDate('deleted_at', Carbon::today())
        // ->update(['deleted_at' => null]);

        // $KPIRestore = KPI::onlyTrashed()
        // ->join('k_r_a_s', 'k_r_a_s.id', '=', 'k_p_i_s.kra_id')
        // ->whereDate('k_p_i_s.deleted_at', Carbon::today())
        // ->update(['k_p_i_s.deleted_at' => null]);
        
        // $MOSRestore = MOS::onlyTrashed()
        // ->join('k_r_a_s', 'k_r_a_s.id', '=', 'm_o_s.kra_id')
        // ->whereDate('m_o_s.deleted_at', Carbon::today())
        // ->update(['m_o_s.deleted_at' => null]);

        // return [$KRARestore , $KPIRestore , $MOSRestore];

        try {
            DB::beginTransaction();
                $KRADelete = KRA::join('users', 'users.id', '=', 'k_r_a_s.user_id')
                ->where('k_r_a_s.year', $year)
                ->where('users.employee_id', $employeeIds)
                ->delete();
            
                $KPIDelete = KPI::join('k_r_a_s', 'k_r_a_s.id', '=', 'k_p_i_s.kra_id')
                ->join('users', 'users.id', '=', 'k_r_a_s.user_id')
                ->where('k_r_a_s.year', $year)
                ->where('users.employee_id', $employeeIds)
                ->delete();
            
                $MOSDelete = MOS::join('k_r_a_s', 'k_r_a_s.id', '=', 'm_o_s.kra_id')
                ->join('users', 'users.id', '=', 'k_r_a_s.user_id')
                ->where('k_r_a_s.year', $year)
                ->where('users.employee_id', $employeeIds)
                ->delete();
            DB::commit();
            return 'Delete Successfully';
            // Perform any additional logic or return a success response here
        } catch (\Exception $e) {
            DB::rollback();
            return 'Some thing went Wrong..!!';
            // Handle the exception and return an error response or log the error
        }                    
    }

    public function kra_kpi_mos_list_cal(Request $request)
    {
        $user_data = Auth::user();
        //return $this->sendResponse( $user_data, 'K P I S retrieved successfully');
        if($user_data->role_id ==  1 &&  $request->dept_id ==""){
            return $this->sendResponse(
                0,
                "K P I S retrieved successfully"
            );

        }

        if ($request->dept_id) {
            $dept_id = $request->dept_id;
        } else {
            $dept_id = $user_data->dept_id;
        }

        $task = MOS::select("m_o_s.*")
        ->join("k_r_a_s", "k_r_a_s.id", "m_o_s.kra_id")
        ->limit(300);

        if ($request->user_id) {
            $task->where("k_r_a_s.user_id", $request->user_id);
        } else {
            if (
                $user_data->role_id == 7 ||
                $user_data->role_id == 6 ||
                $user_data->role_id == 10 ||
                $user_data->role_id == 9
            ) {
                if ($user_data->role_id == 6) {
                    $task->where("k_r_a_s.wing_id", $user_data->wing_id);
                    if ($request->user_id) {
                        $task->where("k_r_a_s.user_id", $request->user_id);
                    } else {
                        $task->where("k_r_a_s.user_id", $user_data->id);
                    }
                } else {
                    $task->where("k_r_a_s.user_id", $user_data->id);
                }
            } else {
                if ($request->user_id) {
                    $task->where("k_r_a_s.user_id", $request->user_id);
                } elseif ($request->wing_id) {
                    $task->where("k_r_a_s.wing_id", $request->wing_id);
                } else {
                    if ($user_data->id == 3882) { //Note: Special Condition
                        $task->where("k_r_a_s.user_id", 3882);
                    } else {
                        $task->where("k_r_a_s.role_id", 5);
                    }
                }
            }

            if ($dept_id) { //Note: Special Condition Finance
                $task->where("m_o_s.dept_id", $dept_id);
                // if ($dept_id == 4) {
                //     if ($user_data->id == 3882) { //Note: Special Condition firoz Sir
                //         $task->where("k_r_a_s.user_id", 3882);
                //     } else {
                //         $task->where("k_r_a_s.user_id", 9);
                //     }
                // } else {
                //     $task->where("m_o_s.dept_id", $dept_id);
                // }
            }
        }

        if ($request->kra_id) {
            $task->where("m_o_s.kra_id", $request->kra_id);
        }

        if ($request->kpi_id) {
            $task->where("m_o_s.kpi_id", $request->kpi_id);
        }
        
        if ($request->mos_id) {
            $task->where("m_o_s.mos_id", $request->mos_id);
        }

        if ($request->year) {
            $task->where("m_o_s.year", $request->year);
        }

        //Note: This part is for Provationary Employee
        // if ($request->user_id) {
        //     $userInfo = User::find($request->user_id);

        //     if ($userInfo->employee_status == 1) {
        //         $task->where("m_o_s.year", $request->year);
        //     }
        // } else {
        //     $task->where("m_o_s.year", $request->year);
        // }

        // }
        //Note: This part is for Provationary Employee End

        $task->orderBy("m_o_s.kra_id", "ASC");
        $task->orderBy("m_o_s.kpi_id", "ASC");
        $task->orderBy("m_o_s.id", "ASC");
        $result = $task->get();

        return $data_return = MosTreeResource::collection($result);
    }

    public function kra_kpi_mos_list(Request $request)
    {
        $data_return = self::kra_kpi_mos_list_cal($request);
        return $this->sendResponse(
            $data_return,
            "K P I S retrieved successfully"
        );
    }

    public function kra_kpi_mos_score_list(Request $request)
    {
        if (((int) $request->year) < 2023) {
            return self::kra_kpi_mos_list($request);
        } else {
            //Note: Current Year End             
            $current = self::kra_kpi_mos_list_cal($request);
            $currentYear = $request->year;
            $previousYear = $request->year - 1;
            $request->merge(['year' => $previousYear]);
            $previous = self::kra_kpi_mos_list_cal($request);
            $data = [
                'current' => $current,
                'previous' => $previous,
            ];

            return $this->sendResponse(
                $data,
                "K P I S retrieved successfully"
            );
        }
    }

    public function target_permission_list(Request $request)
    {
        $user_data = Auth::user();
        if ($request->dept_id) {
            $dept_id = $request->dept_id;
        } else {
            $dept_id = $user_data->dept_id;
        }

        $task = MOS::select("m_o_s.*")
            ->whereIn("modification_status", [1, 3])
            ->join("k_r_a_s", "k_r_a_s.id", "m_o_s.kra_id")
            ->limit(300);

        if (
            $user_data->role_id == 7 ||
            $user_data->role_id == 6 ||
            $user_data->role_id == 10 ||
            $user_data->role_id == 9
        ) {
            if ($user_data->role_id == 6) {
                $task->where("k_r_a_s.wing_id", $user_data->wing_id);
                if ($request->user_id) {
                    $task->where("k_r_a_s.user_id", $request->user_id);
                } else {
                    $task->where("k_r_a_s.user_id", $user_data->id);
                }
            } else {
                $task->where("k_r_a_s.user_id", $user_data->id);
            }
        } else {
            if ($request->user_id) {
                $task->where("k_r_a_s.user_id", $request->user_id);
            } elseif ($request->wing_id) {
                $task->where("k_r_a_s.wing_id", $request->wing_id);
            } else {
                $task->where("k_r_a_s.role_id", 5);
            }
        }
        if ($user_data->role_id != 1) {
            $task->where("user_id", "!=", $user_data->id);
        }

        if ($dept_id) {
            $task->where("m_o_s.dept_id", $dept_id);
        }
        if ($request->kra_id) {
            $task->where("m_o_s.kra_id", $request->kra_id);
        }
        if ($request->kpi_id) {
            $task->where("m_o_s.kpi_id", $request->kpi_id);
        }
        if ($request->mos_id) {
            $task->where("m_o_s.mos_id", $request->mos_id);
        }
        if ($request->year) {
            $task->where("m_o_s.year", $request->year);
        }
        // }
        $task->orderBy("m_o_s.kra_id", "ASC");
        $task->orderBy("m_o_s.kpi_id", "ASC");
        $task->orderBy("m_o_s.id", "ASC");

        $result = $task->get();
        $data_return = MosTreeResource::collection($result);
        return $this->sendResponse(
            $data_return,
            "K P I S retrieved successfully"
        );
    }

    //Note: Assign Kra and kpi start
    public function kra_kpi_mos_list_unassign(Request $request)
    {
        $user_data = Auth::user();
        if ($request->dept_id) {
            $dept_id = $request->dept_id;
        } else {
            $dept_id = $user_data->dept_id;
        }
        $task = MOS::select("m_o_s.*")
            ->join("k_r_a_s", "k_r_a_s.id", "m_o_s.kra_id")
            ->limit(300);

        if (
            $user_data->role_id == 7 ||
            $user_data->role_id == 6 ||
            $user_data->role_id == 10 ||
            $user_data->role_id == 9
        ) {
            if ($user_data->role_id == 6) {
                $task->where("k_r_a_s.wing_id", $user_data->wing_id);
                if ($request->user_id) {
                    $task->where("k_r_a_s.user_id", $request->user_id);
                } else {
                    $task->where("k_r_a_s.user_id", $user_data->id);
                }
            } else {
                $task->where("k_r_a_s.user_id", $user_data->id);
            }
        } else {
            if ($request->user_id) {
                $task->where("k_r_a_s.user_id", $request->user_id);
            } elseif ($request->wing_id) {
                $task->where("k_r_a_s.wing_id", $request->wing_id);
            } else {
                $task->where("k_r_a_s.role_id", 5);
            }
        }

        if ($dept_id) {
            $task->where("m_o_s.dept_id", $dept_id);
        }
        if ($request->kra_id) {
            $task->where("m_o_s.kra_id", $request->kra_id);
        }
        if ($request->kpi_id) {
            $task->where("m_o_s.kpi_id", $request->kpi_id);
        }
        if ($request->mos_id) {
            $task->where("m_o_s.mos_id", $request->mos_id);
        }
        if ($request->year) {
            $task->where("m_o_s.year", $request->year);
        }
        //Note:Already Assign Mos
        // $task->whereNotIn('m_o_s.id',KPI::select('k_p_i_s.rep_id')
        // ->where('k_p_i_s.rep_id','!=',0)
        // ->where('k_p_i_s.year','=',$request->year)
        // ->where('k_p_i_s.dept_id',  $dept_id  )
        // ->get()
        // );
        //Note:Already Assign Mos
        $task->orderBy("m_o_s.kra_id", "ASC");
        $task->orderBy("m_o_s.kpi_id", "ASC");
        $task->orderBy("m_o_s.id", "ASC");

        // echo  $task->toSql();
        // exit();
        $result = $task->get();
        $data_return = MosTreeResourceUnassign::collection($result);
        return $this->sendResponse(
            $data_return,
            "K P I S retrieved successfully"
        );
    }

    public function assign_kra(Request $request)
    {
        $user_data = Auth::user();
        $dept_id = isset($request->dept_id)
            ? $request->dept_id
            : $user_data->dept_id;
        $team_id = $request->team_id;
        $member_id = $request->member_id;
        if ($user_data->role_id != 7) {
            if (!$team_id && !$member_id) {
                $user_id = $request->user_id;
            } elseif ($team_id && !$member_id) {
                $team = Team::find($team_id);
                $user_id = $team->team_leader;
            } else {
                $teamMember = TeamMember::find($member_id);
                $user_id = $teamMember->user_id;
            }
        } else {
            $teamMember = TeamMember::find($member_id);
            $user_id = $teamMember->user_id;
        }
        $wing_id = $request->wing_id;
        $year = $request->year;
        $items = $request->item;
        $user = User::find($user_id);
        $kpi_data = [];
        foreach ($items as $key => $item) {
            if ($item["mos_checked"]) {
                //Note: only Assign most supervisor
                $KRA = KRA::where("rep_id", $item["kpijoin"]["id"])
                    ->where("user_id", $user_id)
                    ->first();
                if (!$KRA) {
                    $KRA = new KRA();
                    $KRA->rep_id = $item["kpijoin"]["id"];
                    $KRA->kra_name = $item["kpijoin"]["kpi_name"];
                    $KRA->dept_id = $dept_id;
                    $KRA->year = $year;
                    $KRA->kra_weight = $item["kra_weight_assign"];
                    $KRA->user_id = $user_id;
                    $KRA->role_id = $user->role_id;
                    $KRA->wing_id = $user->wing_id;
                    $KRA->save();
                }
                $kpi_data[$key]["dept_id"] = $dept_id;
                $kpi_data[$key]["rep_id"] = $item["id"];
                $kpi_data[$key]["kra_id"] = $KRA->id;
                $kpi_data[$key]["kpi_name"] = $item["mos_name"];
                $kpi_data[$key]["kpi_weight"] = $item["kpi_weight_assign"];
                $kpi_data[$key]["year"] = $year;
            }
        }
        $KPI = KPI::insert($kpi_data);
        return $this->sendResponse($kpi_data, "Assign successfully");
    }
    // Note: Assign Kra and kpi End

    //Note: Assign Mos start
    public function assign_mos_list(Request $request)
    {
        $user_data = Auth::user();
        if ($request->dept_id) {
            $dept_id = $request->dept_id;
        } else {
            $dept_id = $user_data->dept_id;
        }

        $kra_task = KPI::select(
            "k_r_a_s.kra_name",
            "k_r_a_s.kra_weight",
            "k_p_i_s.*"
        )
            ->join("k_r_a_s", "k_r_a_s.id", "k_p_i_s.kra_id")
            ->limit(300);
        if (
            $user_data->role_id == 7 ||
            $user_data->role_id == 6 ||
            $user_data->role_id == 10 ||
            $user_data->role_id == 9
        ) {
            if ($user_data->role_id == 6) {
                $kra_task->where("k_r_a_s.wing_id", $user_data->wing_id);
                if ($request->user_id) {
                    $kra_task->where("k_r_a_s.user_id", $request->user_id);
                }
            } else {
                if ($request->user_id) {
                    $kra_task->where("k_r_a_s.user_id", $request->user_id);
                } else {
                    $kra_task->where("k_r_a_s.user_id", $user_data->id);
                }
            }
        } else {
            if ($request->user_id) {
                $kra_task->where("k_r_a_s.user_id", $request->user_id);
            } elseif ($request->wing_id) {
                $kra_task->where("k_r_a_s.wing_id", $request->wing_id);
            } else {
                $kra_task->where("k_r_a_s.role_id", 5);
            }
        }
        if ($dept_id) {
            $kra_task->where("k_p_i_s.dept_id", $dept_id);
        }
        if ($request->kra_id) {
            $kra_task->where("k_p_i_s.kra_id", $request->kra_id);
        }
        if ($request->kpi_id) {
            $kra_task->where("k_p_i_s.id", $request->kpi_id);
        }
        if ($request->year) {
            $kra_task->where("k_p_i_s.year", $request->year);
        }
        $kra_task->orderBy("k_p_i_s.kra_id", "ASC");
        $kra_task->orderBy("k_p_i_s.id", "ASC");

        $result = $kra_task->get();
        $data_return = KpiTreeResource::collection($result);
        return $this->sendResponse(
            $data_return,
            "K P I S retrieved successfully"
        );
    }

    public function assign_mos(Request $request)
    {
        $user_data = Auth::user();
        $dept_id = isset($request->dept_id)
            ? $request->dept_id
            : $user_data->dept_id;
        $user_id = $request->user_id;
        $wing_id = $request->wing_id;
        $year = $request->year;
        $items = $request->item;
        $user = User::find($user_id);

        $kpi_data = [];
        foreach ($items as $key => $item) {
            if ($item["mos"]) {
                //Note: only Assign most supervisor
                foreach ($item["mos"] as $key => $mos_item) {
                    if ($mos_item["mos_name"] && $mos_item["weightage"]) {
                        $MOS = MOS::where("id", $mos_item["id"])
                            ->where("kpi_id", $item["id"])
                            ->where("kra_id", $item["kra_id"])
                            ->first();
                        if ($MOS) {
                            $MOS->mos_name = $mos_item["mos_name"];
                            $MOS->weightage = $mos_item["weightage"];
                            $MOS->save();
                        } else {
                            $MOS = new MOS();
                            $MOS->dept_id = $dept_id;
                            $MOS->kra_id = $item["kra_id"];
                            $MOS->kpi_id = $item["id"];
                            $MOS->mos_name = $mos_item["mos_name"];
                            $MOS->weightage = $mos_item["weightage"];
                            $MOS->isvalorper = 0;
                            $MOS->year = $year;
                            $MOS->save();
                        }
                        $target = MosData::where("mos_id", $MOS->id)
                            ->where("type", "target")
                            ->first();
                        if (!$target) {
                            $data["mos_id"] = $MOS->id;
                            $data["dept_id"] = $dept_id;
                            $data["type"] = "target";
                            $data["year"] = $year;
                            $data["january"] = 0;
                            $data["february"] = 0;
                            $data["march"] = 0;
                            $data["april"] = 0;
                            $data["may"] = 0;
                            $data["june"] = 0;
                            $data["july"] = 0;
                            $data["august"] = 0;
                            $data["september"] = 0;
                            $data["october"] = 0;
                            $data["november"] = 0;
                            $data["december"] = 0;
                            $data["total"] = 0;
                            MosData::create($data);
                        }
                        $achievement = MosData::where("mos_id", $MOS->id)
                            ->where("type", "achievement")
                            ->first();
                        if (!$achievement) {
                            $data["mos_id"] = $MOS->id;
                            $data["dept_id"] = $dept_id;
                            $data["type"] = "achievement";
                            $data["year"] = $year;
                            $data["january"] = 0;
                            $data["february"] = 0;
                            $data["march"] = 0;
                            $data["april"] = 0;
                            $data["may"] = 0;
                            $data["june"] = 0;
                            $data["july"] = 0;
                            $data["august"] = 0;
                            $data["september"] = 0;
                            $data["october"] = 0;
                            $data["november"] = 0;
                            $data["december"] = 0;
                            $data["total"] = 0;
                            MosData::create($data);
                        }
                        $module = MosData::where("mos_id", $MOS->id)
                            ->where("type", "module")
                            ->first();
                        if (!$module) {
                            $data2["mos_id"] = $MOS->id;
                            $data2["type"] = "module";
                            $data2["year"] = $year;
                            $data2["dept_id"] = $dept_id;
                            MosData::create($data2);
                        }
                    }
                }
            }
        }
        return $this->sendResponse($item, "Assign successfully");
    }

    //Note: Assign MOs End
    /**
     * Store a newly created MOS in storage.
     * POST /mOS
     *
     * @param CreateMOSAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMOSAPIRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $mOS = $this->mOSRepository->create($input);
            $data["mos_id"] = $mOS->id;
            $data["type"] = "target";
            $data["dept_id"] = $request->dept_id;
            $data["year"] = $request->year;
            $this->mosDataRepository->create($data);

            $data2["mos_id"] = $mOS->id;
            $data2["type"] = "module";
            $data2["dept_id"] = $request->dept_id;
            $data2["year"] = $request->year;
            $this->mosDataRepository->create($data2);

            $data3["mos_id"] = $mOS->id;
            $data3["type"] = "achievement";
            $data3["dept_id"] = $request->dept_id;
            $data3["year"] = $request->year;
            $this->mosDataRepository->create($data3);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError("Something went wrong");
        }

        return $this->sendResponse($mOS->toArray(), "M O S saved successfully");
    }
    public function achievement_approval(Request $request)
    {
        //mosachievementjoin
        $user_data = Auth::user();
        $comments = $request->comments;

        foreach ($request->items as $key => $value) {
            //return $this->sendResponse($value['mosachievementjoin'], 'Achievement approval successfully');
            if ($value["mosachievementjoin"]) {
                $user_id = $value["krajoin"]["user_id"];
                //note: status
                $status = 2;
                if ($value["krajoin"]["user_id"] == $user_data->id) {
                    $status = 1;
                }
                //note:status month 
                $permission_months = $this->checkMonth($user_data->dept_id);
                $value["mosachievementjoin"][$permission_months . "_status"] = $status;
                //Note : MOS Achivement
                $this->mosDataRepository->update(
                    $value["mosachievementjoin"],
                    $value["mosachievementjoin"]["id"]
                );
                //Note: Mos Permission
                MosData::where(
                    "id",
                    $value["mosachievementjoin"]["id"]
                )->update([$permission_months . "_status" => 2]);
                //Note: MOS Log 
                $achievement = $value["mosachievementjoin"];
                $achievement["mos_data_id"] = $value["mostargetjoin"]["id"];
                $achievement["insert_type"] = "update";
                $achievement["created_by"] = $user_data->id;
                $achievement["permission_months"] = $permission_months;
                $mos_log_id = MosDataLog::create($achievement);
                //Note : MOS Feedback
                if (isset($value["comment"])) {
                    $comment = $value["comment"];
                    $date = date("Y-m-d");
                    $feed = [
                        "mos_id" => $value["id"],
                        "user_id" => $user_data->id,
                        "dept_id" => $user_data->dept_id,
                        "date" => $date,
                        "mosdata_log_id" => $mos_log_id->id,
                    ];
                    $feed["msg"] = $comment;
                    $feed["fmonth"] = $this->checkMonthNumber(
                        $user_data->dept_id
                    );
                    MosFeadback::create($feed);
                }

                // if(MosFeadback::where(['fmonth'=> 1, 'mos_id'=>$value['id'] ])->count() == 0){
                //     MosFeadback::create($feed);
                // }else{
                //     MosFeadback::where(['fmonth'=> 1, 'mos_id'=>$value['id'] ])->update(['msg'=> $feed['msg']]);
                // }

                //Note: Achivement from KPI 
                if ($value["kpijoin"]["rep_id"]) {
                    $rep_id = $value["kpijoin"]["rep_id"];
                    $month = $permission_months;
                    $achi = MosData::where("mos_id", $rep_id)
                        ->where("mos_datas.type", "achievement")
                        ->first();

                    if ($month) {
                        //if ($achi[$this->checkMonth($value['kpijoin']['dept_id'])] == 0) {
                        //Note: bottom line target and achivement 
                        $target = $this->targetAchi($month, $rep_id, "target", 'kpi');
                        $achievement = $this->targetAchi(
                            $month,
                            $rep_id,
                            "achievement",
                            'kpi'
                        );

                        $myTarget = MosData::where("mos_id", $rep_id)
                            ->where("mos_datas.type", "target")
                            ->sum($month);
                        $myAchivement = 0;
                        if (isset($value["kpijoin"]["rep_id"]) && ($value['rep_per'] > 0 && $achievement > 0)) {

                            $reference_mos = MOS::select("m_o_s.*")
                                ->join("k_p_i_s", "m_o_s.kpi_id", "k_p_i_s.id")
                                ->where("k_p_i_s.rep_id", $rep_id)
                                ->with(['mostarget_join', 'mosachivement_join'])
                                ->get();

                            $myAchivement = 0;
                            foreach ($reference_mos as $key => $val_ref) {
                                $b_achvConvert = $val_ref['mosachivement_join'][$month] * ($val_ref['mostarget_join'][$month] == 0 ? 0 : (100 / $val_ref['mostarget_join'][$month]));
                                $u_maxrange = ($myTarget * 100) == 0 ? 0 : ($val_ref['rep_per'] / ($myTarget * 100));
                                //$actualAchivement = ($b_achvConvert/100)==0?0:($u_maxrange * ($b_achvConvert/100) > $u_maxrange ? $u_maxrange : ($u_maxrange * ($b_achvConvert/100))); 
                                $actualAchivement = ($b_achvConvert / 100) == 0 ? 0 : $u_maxrange * ($b_achvConvert / 100);
                                $myAchivement += $actualAchivement;
                            }
                        } else {
                            $myAchivement = $target > 0 && $achievement > 0
                                ? ($achievement / $target) * $myTarget
                                : 0;
                        }
                        //return $this->sendResponse($myAchivement , 'test 0 Achievement approval successfully');
                        if ($myAchivement > 0) {
                            MosData::where("id", $achi["id"])->update([
                                $month => $myAchivement,
                            ]);
                        }
                    }
                    //};
                }

                //Note: Achivement from MOS
                if (isset($value["rep_id"]) && $value["rep_id"] > 0) {
                    $rep_id = $value["rep_id"];

                    $month = $permission_months;
                    $achi = MosData::where("mos_id", $rep_id)
                        ->where("mos_datas.type", "achievement")
                        ->first();

                    if ($month) {
                        //Note: bottom line target and achivement 
                        $target = $this->targetAchi($month, $rep_id, "target", 'mos');
                        $achievement = $this->targetAchi(
                            $month,
                            $rep_id,
                            "achievement",
                            'mos'
                        );

                        $myTarget = MosData::where("mos_id", $rep_id)
                            ->where("mos_datas.type", "target")
                            ->sum($month);
                        //Note: Share percentage Calculation 
                        $myAchivement = 0;
                        if ((isset($value["rep_id"]) && $value["rep_id"] > 0) && ($value['rep_per'] > 0 && $achievement > 0)) {
                            $reference_mos = MOS::select("m_o_s.*")
                                ->where("m_o_s.rep_id", $rep_id)
                                ->with(['mostarget_join', 'mosachivement_join'])
                                ->get();
                            $myAchivement = 0;
                            foreach ($reference_mos as $key => $val_ref) {
                                $b_achvConvert = $val_ref['mosachivement_join'][$month] * ($val_ref['mostarget_join'][$month] == 0 ? 0 : (100 / $val_ref['mostarget_join'][$month]));
                                $u_maxrange = ($myTarget * 100) == 0 ? 0 : ($val_ref['rep_per'] / ($myTarget * 100));
                                // $actualAchivement = ($b_achvConvert/100)==0?0:($u_maxrange * ($b_achvConvert/100) > $u_maxrange ? $u_maxrange : ($u_maxrange * ($b_achvConvert/100))); 
                                $actualAchivement = ($b_achvConvert / 100) == 0 ? 0 : $u_maxrange * ($b_achvConvert / 100);
                                $myAchivement += $actualAchivement;
                            }
                        } else {
                            $myAchivement = $target > 0 && $achievement > 0
                                ? ($achievement / $target) * $myTarget
                                : 0;
                        }

                        if ($myAchivement > 0) {
                            MosData::where("id", $achi["id"])->update([
                                $month => $myAchivement,
                            ]);
                        }
                    }
                }
            }
        }
        return $this->sendResponse(
            $comments,
            "Achievement approval successfully"
        );
    }
    public function targetAchi($month, $rep_id, $type, $check)
    {
        if ($month) {
            if ($check == 'kpi') {
                $data = KPI::select("mos_datas." . $month)
                    ->where("k_p_i_s.rep_id", $rep_id)
                    ->join("m_o_s", "m_o_s.kpi_id", "k_p_i_s.id")
                    ->join("mos_datas", "mos_datas.mos_id", "m_o_s.id")
                    ->where("mos_datas.type", $type)
                    ->whereNull("mos_datas.deleted_at")
                    ->sum($month);
            } else {
                $data = MOS::select("mos_datas." . $month)
                    ->join("mos_datas", "mos_datas.mos_id", "m_o_s.id")
                    ->where("mos_datas.type", $type)
                    ->where("m_o_s.rep_id", $rep_id)
                    ->whereNull("mos_datas.deleted_at")
                    ->sum($month);
            }
            return $data;
        } else {
            return 0;
        }
    }
    public function mos_update(Request $request)
    {
        $data = $request->all();
        $user_data = Auth::user();
        $date =
            $request->year_get == date("Y")
            ? date("Y-m-d")
            : $request->year_get . "-12-31";

        $mosjoin = $data["arrayData"]["mosjoin"];
        foreach ($mosjoin as $key => $value) {
            //`mos_id`, `user_id`, `dept_id`, `date`, `msg`, `month`, `fmonth`, `status`, `created_at`, `updated_at`, `deleted_at`SELECT * FROM `mos_feadbacks` WHERE 1
            $mos = [
                "mos_name" => $value["mos_name"],
                "weightage" => $value["weightage"],
                "isvalorper" => $value["isvalorper"],
                "mos_calculation" => $value["mos_calculation"],
            ];
            if (isset($data["feedback"])) {
                $feedback = $data["feedback"];
                $user_data = Auth::user();
                $feed = [
                    "mos_id" => $value["id"],
                    "user_id" => $user_data->id,
                    "dept_id" => $user_data->dept_id,
                    "date" => $date,
                ];
                if (isset($feedback["january_" . $value["id"]])) {
                    $feed["msg"] = $feedback["january_" . $value["id"]];
                    $feed["fmonth"] = 1;

                    if (
                        MosFeadback::where([
                            "fmonth" => 1,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 1,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["february_" . $value["id"]])) {
                    $feed["msg"] = $feedback["february_" . $value["id"]];
                    $feed["fmonth"] = 2;
                    //MosFeadback::create($feed);

                    if (
                        MosFeadback::where([
                            "fmonth" => 2,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 2,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["march_" . $value["id"]])) {
                    $feed["msg"] = $feedback["march_" . $value["id"]];
                    $feed["fmonth"] = 3;
                    //MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 3,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 3,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["april_" . $value["id"]])) {
                    $feed["msg"] = $feedback["april_" . $value["id"]];
                    $feed["fmonth"] = 4;
                    //MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 4,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 4,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["may_" . $value["id"]])) {
                    $feed["msg"] = $feedback["may_" . $value["id"]];
                    $feed["fmonth"] = 5;
                    //MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 5,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 5,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["june_" . $value["id"]])) {
                    $feed["msg"] = $feedback["june_" . $value["id"]];
                    $feed["fmonth"] = 6;
                    //MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 6,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 6,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["july_" . $value["id"]])) {
                    $feed["msg"] = $feedback["july_" . $value["id"]];
                    $feed["fmonth"] = 7;
                    // MosFeadback::create($feed);

                    if (
                        MosFeadback::where([
                            "fmonth" => 7,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 7,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["august_" . $value["id"]])) {
                    $feed["msg"] = $feedback["august_" . $value["id"]];
                    $feed["fmonth"] = 8;
                    //MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 8,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 8,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["september_" . $value["id"]])) {
                    $feed["msg"] = $feedback["september_" . $value["id"]];
                    $feed["fmonth"] = 9;
                    //MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 9,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 9,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["october_" . $value["id"]])) {
                    $feed["msg"] = $feedback["october_" . $value["id"]];
                    $feed["fmonth"] = 10;
                    // MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 10,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 10,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["november_" . $value["id"]])) {
                    $feed["msg"] = $feedback["november_" . $value["id"]];
                    $feed["fmonth"] = 11;
                    // MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 11,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 11,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }
                if (isset($feedback["december_" . $value["id"]])) {
                    $feed["msg"] = $feedback["december_" . $value["id"]];
                    $feed["fmonth"] = 12;
                    // MosFeadback::create($feed);
                    if (
                        MosFeadback::where([
                            "fmonth" => 12,
                            "mos_id" => $value["id"],
                        ])->count() == 0
                    ) {
                        MosFeadback::create($feed);
                    } else {
                        MosFeadback::where([
                            "fmonth" => 12,
                            "mos_id" => $value["id"],
                        ])->update(["msg" => $feed["msg"]]);
                    }
                }

                // print_r($feedback['july_'.$value['id']]);
            }
            $this->mOSRepository->update($mos, $value["id"]);

            // MosData::where('id', $value['mostargetjoin']['id'])
            //         ->update($value['mostargetjoin']);
            // MosData::where('id', $value['mosmodulejoin']['id'])
            //         ->update($value['mosmodulejoin']);
            // MosData::where('id', $value['mosachievementjoin']['id'])
            //         ->update($value['mosachievementjoin']);
            if ($value["mostargetjoin"]) {
                $this->mosDataRepository->update(
                    $value["mostargetjoin"],
                    $value["mostargetjoin"]["id"]
                );
                $mosValu = MosData::find($value["mostargetjoin"]["id"]);
                $totalValue =
                    $mosValu->january +
                    $mosValu->february +
                    $mosValu->march +
                    $mosValu->april +
                    $mosValu->may +
                    $mosValu->june +
                    $mosValu->july +
                    $mosValu->august +
                    $mosValu->september +
                    $mosValu->october +
                    $mosValu->november +
                    $mosValu->december;
                MosData::where("id", $value["mostargetjoin"]["id"])->update([
                    "total" => $totalValue,
                ]);
            }
            if ($value["mosmodulejoin"]) {
                $this->mosDataRepository->update(
                    $value["mosmodulejoin"],
                    $value["mosmodulejoin"]["id"]
                );
                $mosValu = MosData::find($value["mostargetjoin"]["id"]);
                $totalValue =
                    $mosValu->january +
                    $mosValu->february +
                    $mosValu->march +
                    $mosValu->april +
                    $mosValu->may +
                    $mosValu->june +
                    $mosValu->july +
                    $mosValu->august +
                    $mosValu->september +
                    $mosValu->october +
                    $mosValu->november +
                    $mosValu->december;
                MosData::where("id", $value["mostargetjoin"]["id"])->update([
                    "total" => $totalValue,
                ]);
            }
            if ($value["mosachievementjoin"]) {
                $this->mosDataRepository->update(
                    $value["mosachievementjoin"],
                    $value["mosachievementjoin"]["id"]
                );
                $mosValu = MosData::find($value["mostargetjoin"]["id"]);
                $totalValue =
                    $mosValu->january +
                    $mosValu->february +
                    $mosValu->march +
                    $mosValu->april +
                    $mosValu->may +
                    $mosValu->june +
                    $mosValu->july +
                    $mosValu->august +
                    $mosValu->september +
                    $mosValu->october +
                    $mosValu->november +
                    $mosValu->december;
                MosData::where("id", $value["mostargetjoin"]["id"])->update([
                    "total" => $totalValue,
                ]);

                $achievement = $value["mosachievementjoin"];
                $achievement["mos_data_id"] = $value["mostargetjoin"]["id"];
                $achievement["insert_type"] = "update";
                $achievement["created_by"] = $user_data->id;
                $achievement["permission_months"] = $this->checkMonth(
                    $user_data->dept_id
                );
                MosDataLog::create($achievement);
            }
        }

        return $this->sendResponse($data, "MOS retrieved successfully");
    }
    public function checkMonthNumber($dept_id)
    {
        $permission = DepartmentSetting::where("dept_id", $dept_id)->first();

        $month = "";
        if ($permission->jan == 1) {
            $month = 1;
        } elseif ($permission->feb == 1) {
            $month = 2;
        } elseif ($permission->mar == 1) {
            $month = 3;
        } elseif ($permission->apr == 1) {
            $month = 4;
        } elseif ($permission->may == 1) {
            $month = 5;
        } elseif ($permission->jun == 1) {
            $month = 6;
        } elseif ($permission->jul == 1) {
            $month = 7;
        } elseif ($permission->agu == 1) {
            $month = 8;
        } elseif ($permission->sep == 1) {
            $month = 9;
        } elseif ($permission->oct == 1) {
            $month = 10;
        } elseif ($permission->nov == 1) {
            $month = 11;
        } elseif ($permission->dec == 1) {
            $month = 12;
        }
        return $month;
    }
    public function checkMonth($dept_id)
    {
        $permission = DepartmentSetting::where("dept_id", $dept_id)->first();


        $month = "";
        if ($permission->jan == 1) {
            $month = "january";
        } elseif ($permission->feb == 1) {
            $month = "february";
        } elseif ($permission->mar == 1) {
            $month = "march";
        } elseif ($permission->apr == 1) {
            $month = "april";
        } elseif ($permission->may == 1) {
            $month = "may";
        } elseif ($permission->jun == 1) {
            $month = "june";
        } elseif ($permission->jul == 1) {
            $month = "july";
        } elseif ($permission->aug == 1) {
            $month = "august";
        } elseif ($permission->sep == 1) {
            $month = "september";
        } elseif ($permission->oct == 1) {
            $month = "october";
        } elseif ($permission->nov == 1) {
            $month = "november";
        } elseif ($permission->dec == 1) {
            $month = "december";
        }
        return $month;
    }

    /**
     * Display the specified MOS.
     * GET|HEAD /mOS/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Request $request)
    {
        /** @var MOS $mOS */
        $mOS = $this->mOSRepository->find($id);
        if (empty($mOS)) {
            return $this->sendError("M O S not found");
        }
        $mOS = new MosItemResource($mOS);
        return $this->sendResponse($mOS, "M O S retrieved successfully");
    }

    /**
     * Update the specified MOS in storage.
     * PUT/PATCH /mOS/{id}
     *
     * @param int $id
     * @param UpdateMOSAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMOSAPIRequest $request)
    {
        $input = $request->all();

        /** @var MOS $mOS */
        $mOS = $this->mOSRepository->find($id);

        if (empty($mOS)) {
            return $this->sendError("M O S not found");
        }

        $mOS = $this->mOSRepository->update($input, $id);

        return $this->sendResponse($mOS->toArray(), "MOS updated successfully");
    }

    /**
     * Remove the specified MOS from storage.
     * DELETE /mOS/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MOS $mOS */
        $mOS = $this->mOSRepository->find($id);

        if (empty($mOS)) {
            return $this->sendError("M O S not found");
        }

        $mOS->delete();

        return $this->sendSuccess("M O S deleted successfully");
    }

    /**
     * DEPTARTMENT WISE MOS LIST
     */
    public function departmentWiseMosList()
    {
        $mosList = MOS::with("krajoin", "kpijoin")
            ->where("dept_id", Auth::user()->dept_id)
            ->orderBy("kra_id")
            ->get();
        return $this->sendResponse($mosList, "MOS retrieved successfully");
    }

    /**
     * DEPTARTMENT WISE MOS LIST
     */
    public function userWiseMosList(Request $request)
    {
        if ($request->wings_id) {
            //Note: This Is for wings
            $wings_user = User::select("id")
                ->where("wing_id", $request->wings_id)
                ->get()
                ->pluck("id")
                ->toArray();

            $mosList = UesrMos::whereIN("user_id", $wings_user)->get();
            return $this->sendResponse(
                $mosList,
                "MOS retrieved successfully-2"
            );
        } else {
            //Note:This part is for single user
            $mosList = UesrMos::where("user_id", $request->user_id)->get();
        }
        return $this->sendResponse($mosList, "MOS retrieved successfully");
    }

    /**
     * DEPTARTMENT WISE USER LIST
     */
    public function departmentWiseUserList()
    {
        $userList = User::where("dept_id", Auth::user()->dept_id)
            ->where("status", 1)
            ->whereIn("role_id", [6, 7])
            ->orderBy("role_id", "ASC")
            ->get();
        return $this->sendResponse($userList, "User retrieved successfully");
    }

    public function mosSettings(Request $request)
    {
        //GET EXISTING DATA
        $savedMos = UesrMos::where([
            "user_id" => $request->user_id,
            "mos_id" => $request->mos_id,
        ])->get();
        if ($request->checked) {
            //Note: Mos Create
            if ($request->data_type == "kra") {
                //Note:set user all kra
                $mos_id = MOS::select("id")
                    ->where("kra_id", $request->mos_id)
                    ->get()
                    ->pluck("id")
                    ->toArray();
                foreach ($mos_id as $key => $mos_ids) {
                    //Note:set user all Kpi
                    $checked_data = UesrMos::where("user_id", $request->user_id)
                        ->where("mos_id", $mos_ids)
                        ->first();

                    if (!$checked_data) {
                        //Note: Already Data checked
                        UesrMos::create([
                            "user_id" => $request->user_id,
                            "mos_id" => $mos_ids,
                        ]);
                    }
                }
            } elseif ($request->data_type == "kpi") {
                //Note:set user single Mos
                $mos_id = MOS::select("id")
                    ->where("kpi_id", $request->mos_id)
                    ->get()
                    ->pluck("id")
                    ->toArray();
                foreach ($mos_id as $key => $mos_ids) {
                    $checked_data = UesrMos::where("user_id", $request->user_id)
                        ->where("mos_id", $mos_ids)
                        ->first();

                    if (!$checked_data) {
                        //Note: Already Data checked
                        UesrMos::create([
                            "user_id" => $request->user_id,
                            "mos_id" => $mos_ids,
                        ]);
                    }
                }
            } else {
                UesrMos::create([
                    "user_id" => $request->user_id,
                    "mos_id" => $request->mos_id,
                ]);
            }
        } else {
            //Note: Mos Delete
            if ($request->data_type == "kra") {
                //Note:delete user all kra
                $mos_id = MOS::select("id")
                    ->where("kra_id", $request->mos_id)
                    ->get()
                    ->pluck("id")
                    ->toArray();
                $deleteMos = UesrMos::where("user_id", $request->user_id)
                    ->whereIn("mos_id", $mos_id)
                    ->delete();
            } elseif ($request->data_type == "kpi") {
                //Note:delete user all Kpi
                $mos_id = MOS::select("id")
                    ->where("kpi_id", $request->mos_id)
                    ->get()
                    ->pluck("id")
                    ->toArray();
                $deleteMos = UesrMos::where("user_id", $request->user_id)
                    ->whereIn("mos_id", $mos_id)
                    ->delete();
            } else {
                //Note:delete user single Mos
                $deleteMos = UesrMos::where([
                    "user_id" => $request->user_id,
                    "mos_id" => $request->mos_id,
                ])->delete();
            }
        }

        //GET ALL DATA
        $getMos = UesrMos::where(["user_id" => $request->user_id])->get();

        return Response::json($getMos);
    }

    public function mos_modification_permission(Request $request)
    {
        $user_data = Auth::user();
        foreach ($request->ids as $key => $item_id) {
            MOS::where("id", $item_id)->update([
                "modification_status" => 1,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                "modification_months" => json_encode($request->select_months),
            ]);
        }

        $to_date =
            $request->toDate != ""
            ? date("Y-m-d", strtotime($request->start_date))
            : date("Y-m-d");
        $from_date =
            $request->fromDate != ""
            ? date("Y-m-d", strtotime($request->end_date))
            : date("Y-m-d");
        $departments = Department::find($user_data->dept_id);
        $phpMail = new PHPMailer();
        $message = "";

        $task = MOS::limit(50);
        $task->whereIn("id", $request->ids);
        $task->orderBy("kra_id", "ASC");
        $task->orderBy("kpi_id", "ASC");
        $task->orderBy("id", "ASC");
        $items = $task->get();

        $user = $user_data["name"];
        $phpMail->AddCC("sayed@ssgbd.com", "System CC");
        $form = $user_data->ad_mail;
        $phpMail->AddReplyTo($user_data->ad_mail);
        $data["role_id"] = $user_data->role_id;
        if (
            $user_data->role_id == 7 ||
            $user_data->role_id == 6 ||
            $user_data->role_id == 9 ||
            $user_data->role_id == 10
        ) {
            //     $wings =  User::where('role_id', 6)->where('wing_id', $user_data->wing_id)->first();
            //     $sender  = $wings->ad_mail;
            //     $phpMail->AddAddress($wings->ad_mail,  "Monthly Report Permission");
            // } else if ($user_data->role_id ==  6) {
            $dept = User::where("role_id", 5)
                ->where("dept_id", $user_data->dept_id)
                ->first();

            // $phpMail->AddAddress($dept->ad_mail,  "Monthly Report Permission");
            if ($user_data->dept_id == 1 || $user_data->dept_id == 40 || $user_data->dept_id == 41) {
                $data["link"] =
                    URL::to("/") .
                    "/api/mos_modification_permission_acknowledge?user_id=" .
                    $user_data->id .
                    "&ids=" .
                    implode("_", $request->ids) .
                    "&type=mail";
            } else {
                $data["link"] =
                    URL::to("/") .
                    "/api/modification_permission_approved?ids=" .
                    implode("_", $request->ids);
            }

            $phpMail->AddAddress($departments->hod_email, "Permission");
        } else {
            $data["link"] =
                URL::to("/") .
                "/api/modification_permission_approved?ids=" .
                implode("_", $request->ids);
            // $phpMail->AddAddress(
            //     "khushbu@ssgbd.com",
            //     "Monthly Report Permission"
            // );
            //shahidul.alam@ssgbd.com

            $phpMail->AddAddress("shahidul.alam@ssgbd.com", "Monthly Report Permission");
            $phpMail->AddCC("tasnim.tabassum@ssgbd.com", "Tasnim Tabassum");
            // $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");

            // $sender  = 'sayed@ssgbd.com';
            // $phpMail->AddAddress('sayed@ssgbd.com',  "Monthly Report Permission");
        }

        $sender = "management.desk@ssgbd.com";

        $nextmonth = "";
        $where = "";
        if ($user_data->role_id == 5) {
            $where = "?dept_id=" . $user_data->dept_id;
        } else {
            $where = "?user_id=" . $user_data->id;
        }
        $data["items"] = $items;
        $data["modification_months"] = $request->select_months;
        $data["nextmonth"] = $to_date;
        $data["to_date"] = $to_date;
        $data["from_date"] = $from_date;
        $data["department"] = $departments;
        $data["note"] = $request->note;

        $data["panel_link"] =
            str_replace("backend/public", "", URL::to("/")) .
            "/target_permission_list" .
            $where;
        $message = view("mail.target_permission")->with(["data" => $data]);
        $msg = nl2br($message);
        $phpMail->FromName = $user;
        $phpMail->From = $form;
        $phpMail->Sender = $sender;
        $phpMail->IsHTML(true);
        $phpMail->Host = "mail.ssgbd.com:25";
        $phpMail->IsSMTP();
        $phpMail->Mailer = "smtp";
        $phpMail->Subject = "MOS Target Modify permission";
        $phpMail->Body = $msg;
        $phpMail->SMTPAuth = false;

        if (!$phpMail->Send()) {
            echo "Message could not be sent.";
            echo "Mailer Error: " . $phpMail->ErrorInfo;
            exit();
        }

        $phpMail->ClearAddresses();
        $phpMail->ClearAttachments();

        return $this->sendResponse($request, "Permission update successfully");
    }

    public function mail_test(Request $request)
    {
        $phpMail = new PHPMailer();
        $user = "AS Sayem";
        // $user_email = $user_data->ad_mail;
        $phpMail->AddCC("sayed@ssgbd.com", "System CC");
        $form = "abdul.mazid@ssgbd.com";
        $sender = "abdul.mazid@ssgbd.com";
        // $phpMail->AddReplyTo($user_data->ad_mail);
        $nextmonth = "";
        $message = view("mail.testMail")->with(["data" => ""]);
        $msg = nl2br($message);
        $phpMail->FromName = $user;
        $phpMail->From = $form;
        $phpMail->Sender = $sender;
        $phpMail->IsHTML(true);
        $phpMail->Host = "mail.ssgbd.com:25";
        $phpMail->IsSMTP();
        $phpMail->Mailer = "smtp";
        $phpMail->Subject = "MOS Target Modify permission";
        $phpMail->Body = $msg;
        $phpMail->SMTPAuth = false;
        if (!$phpMail->Send()) {
            echo "Message could not be sent.";
            echo "Mailer Error: " . $phpMail->ErrorInfo;
            exit();
        }
        $phpMail->ClearAddresses();
        $phpMail->ClearAttachments();
        return $this->sendResponse($request, "Permission update successfully");
    }

    public function modification_permission_approved(Request $request)
    {
        //print_r( $request->ids);
        $ids = explode("_", $request->ids);
        foreach ($ids as $key => $item_id) {
            MOS::where("id", $item_id)->update(["modification_status" => 2]);
        }
        if ($ids[0]) {
            $mostData = MOS::select(
                "users.name",
                "users.ad_mail",
                "m_o_s.end_date",
                "k_r_a_s.role_id",
                "departments.hod_email",
                "departments.hod_name"
            )
                ->leftjoin("k_r_a_s", "k_r_a_s.id", "m_o_s.kra_id")
                ->leftjoin("users", "users.id", "k_r_a_s.user_id")
                ->leftjoin("departments", "departments.id", "k_r_a_s.dept_id")
                ->where("m_o_s.id", $ids[0])
                ->first()
                ->toArray();
            if ($mostData["hod_email"]) {
                $phpMail = new PHPMailer();
                $message = "";
                if ($mostData["role_id"] == 5) {
                    $phpMail->AddAddress(
                        $mostData["hod_email"],
                        $mostData["hod_name"]
                    );
                } else {
                    if ($mostData["ad_mail"]) {
                        $phpMail->AddAddress(
                            $mostData["ad_mail"],
                            $mostData["name"]
                        );
                    }
                }
                $nextmonth = "";
                $data["contena"] =
                    "Your target permission request approved, Please complete the changes by " .
                    $mostData["end_date"] .
                    'Regards,Team BPT';
                $message = view("mail.default_theme")->with(["data" => $data]);

                $user = "Management Desk";
                $user_email = "management.desk@ssgbd.com";

                $phpMail->AddReplyTo("management.desk@ssgbd.com", "Check Mail");

                $msg = nl2br($message);

                $phpMail->FromName = $user;
                $phpMail->From = "management.desk@ssgbd.com";
                $phpMail->Sender = $user_email;
                $phpMail->IsHTML(true);
                $phpMail->Host = "mail.ssgbd.com:25";
                $phpMail->IsSMTP();
                $phpMail->Mailer = "smtp";
                $phpMail->Subject = "Your target permission request approved";
                $phpMail->Body = $msg;
                $phpMail->SMTPAuth = false;

                if (!$phpMail->Send()) {
                    echo "Message could not be sent.";
                    echo "Mailer Error: " . $phpMail->ErrorInfo;
                    // exit;
                }
            }
        }

        //You will complete the target change by the 25th

        return redirect(
            str_replace("backend/public", "", URL::to("/")) .
                "?permission=Permission approved successfully"
        );
        //echo  'Permission update successfully' ;
    }

    public function mos_achievement_permission(Request $request)
    {
        $user_data = Auth::user();
        $departments = Department::find($user_data->dept_id);

        $phpMail = new PHPMailer();
        $message = "";
        $phpMail->AddAddress("shahidul.alam@ssgbd.com", "Sayem");
        $phpMail->AddCC("sayed@ssgbd.com", "System CC");
        $phpMail->AddCC("tasnim.tabassum@ssgbd.com", "Tasnim Tabassum");
        $nextmonth = "";
        $data["department_name"] = $departments->name;
        $data["to_date"] = $request->start_date;
        $data["from_date"] = $request->end_date;
        $mo_name = "";
        foreach ($request->select_months as $key => $mo) {
            $mo_name = $mo_name . $mo["name"] . ", ";
            # code...
        }
        $data["months"] = $mo_name;
        $data["link"] =
            URL::to("/") .
            "/api/achievement_permission_approved?dept_id=" .
            $departments->id .
            "&start_date=" .
            $request->start_date .
            "&end_date=" .
            $request->end_date .
            "&months=" .
            json_encode($request->select_months);

        $data["content"] =
            " " .
            $request->content .
            ' 
            

            Regards,
            Team BPT
            ';
        $message = view("mail.achievements_permission")->with([
            "data" => $data,
        ]);

        $user = "Management Desk";
        $user_email = "management.desk@ssgbd.com";

        $phpMail->AddReplyTo("management.desk@ssgbd.com", "Check Mail");

        $msg = nl2br($message);

        $phpMail->FromName = $user;
        $phpMail->From = "management.desk@ssgbd.com";
        $phpMail->Sender = $user_email;
        $phpMail->IsHTML(true);
        $phpMail->Host = "mail.ssgbd.com:25";
        $phpMail->IsSMTP();
        $phpMail->Mailer = "smtp";
        $phpMail->Subject = "Request for achievement permission";
        $phpMail->Body = $msg;
        $phpMail->SMTPAuth = false;

        if (!$phpMail->Send()) {
            echo "Message could not be sent.";
            echo "Mailer Error: " . $phpMail->ErrorInfo;
            // exit;
        }
        return $this->sendResponse(
            $request,
            "Permission mail send successfully"
        );
    }

    public function achievement_permission_approved(Request $request)
    {
        $departments = Department::find($request->dept_id)->toArray();
        $months = json_decode($request->months);

        $settings = DepartmentSetting::where(
            "dept_id",
            $request->dept_id
        )->first();
        $settings->start_date = $request->start_date;
        $settings->end_date = $request->end_date;

        //DepartmentSetting::where('id', $settings['id'])
        //      ->update( $settings);
        foreach ($months as $key => $month) {
            $settings[$month->id] = 1;
        }
        $settings->save();

        if ($departments["hod_email"]) {
            $phpMail = new PHPMailer();
            $message = "";
            $phpMail->AddAddress($departments["hod_email"], "System");
            $phpMail->AddCC("sayed@ssgbd.com", "System CC");
            $nextmonth = "";
            $data["contena"] =
                "Your achievement permission request approved, Please complete the changes by " .
                $settings->end_date .
                '
                

                Regards,
                Team BPT
                ';
            $message = view("mail.default_theme")->with(["data" => $data]);

            $user = "Management Desk";
            $user_email = "management.desk@ssgbd.com";

            $phpMail->AddReplyTo("management.desk@ssgbd.com", "Check Mail");

            $msg = nl2br($message);

            $phpMail->FromName = $user;
            $phpMail->From = "management.desk@ssgbd.com";
            $phpMail->Sender = $user_email;
            $phpMail->IsHTML(true);
            $phpMail->Host = "mail.ssgbd.com:25";
            $phpMail->IsSMTP();
            $phpMail->Mailer = "smtp";
            $phpMail->Subject = "Your achievement permission request approved";
            $phpMail->Body = $msg;
            $phpMail->SMTPAuth = false;

            if (!$phpMail->Send()) {
                echo "Message could not be sent.";
                echo "Mailer Error: " . $phpMail->ErrorInfo;
                // exit;
            }

            return redirect(
                str_replace("backend/public", "", URL::to("/")) .
                    "?permission=Permission approved successfully"
            );
        }
    }

    public function mos_modification_permission_approved(Request $request)
    {
        //$user_data = Auth::user();
        foreach ($request->ids as $key => $item) {
            MOS::where("id", $item["id"])->update([
                "modification_status" => 2,
                "start_date" => date("Y-m-d", strtotime($item["start_date"])),
                "end_date" => date("Y-m-d", strtotime($item["end_date"])),
            ]);
        }

        if ($request->ids[0]) {
            $mostData = MOS::select(
                "users.name",
                "users.ad_mail",
                "m_o_s.end_date",
                "k_r_a_s.role_id",
                "departments.hod_email",
                "departments.hod_name"
            )
                ->leftjoin("k_r_a_s", "k_r_a_s.id", "m_o_s.kra_id")
                ->leftjoin("users", "users.id", "k_r_a_s.user_id")
                ->leftjoin("departments", "departments.id", "k_r_a_s.dept_id")
                ->where("m_o_s.id", $request->ids[0]["id"])
                ->first()
                ->toArray();
            // return $this->sendResponse($mostData , 'Permission update successfully');
            if ($mostData["hod_email"]) {
                $phpMail = new PHPMailer();
                $message = "";
                if ($mostData["role_id"] == 5) {
                    $phpMail->AddAddress(
                        $mostData["hod_email"],
                        $mostData["hod_name"]
                    );
                } else {
                    $phpMail->AddAddress(
                        $mostData["ad_mail"],
                        $mostData["name"]
                    );
                }
                $nextmonth = "";
                $data["contena"] =
                    "Your target permission request approved, Please complete the changes by " .
                    $mostData["end_date"] .
                    '
                    

                    Regards,
                    Team BPT
                    ';
                $message = view("mail.default_theme")->with(["data" => $data]);

                $user = "Management Desk";
                $user_email = "management.desk@ssgbd.com";

                $phpMail->AddReplyTo("management.desk@ssgbd.com", "Check Mail");

                $msg = nl2br($message);

                $phpMail->FromName = $user;
                $phpMail->From = "management.desk@ssgbd.com";
                $phpMail->Sender = $user_email;
                $phpMail->IsHTML(true);
                $phpMail->Host = "mail.ssgbd.com:25";
                $phpMail->IsSMTP();
                $phpMail->Mailer = "smtp";
                $phpMail->Subject = "Your target permission request approved";
                $phpMail->Body = $msg;
                $phpMail->SMTPAuth = false;

                if (!$phpMail->Send()) {
                    echo "Message could not be sent.";
                    echo "Mailer Error: " . $phpMail->ErrorInfo;
                    // exit;
                }
            }
        }

        return $this->sendResponse($request, "Permission update successfully");
    }

    public function mos_modification_permission_acknowledge(Request $request)
    {
        $user_data = User::find($request->user_id);

        if ($request->type == "mail") {
            $ids = explode("_", $request->ids);
        } else {
            $idData = [];
            foreach ($request->ids as $key => $value) {
                //print_r($value['id']);
                $idData[] = $value["id"];
                //exit();
                //$idData[] = $request->ids[$i]['id'];
            }

            $ids = $idData;
        }

        foreach ($ids as $key => $item_id) {
            MOS::where("id", $item_id)->update(["modification_status" => 3]);
        }

        $to_date =
            $request->toDate != ""
            ? date("Y-m-d", strtotime($request->start_date))
            : date("Y-m-d");
        $from_date =
            $request->fromDate != ""
            ? date("Y-m-d", strtotime($request->end_date))
            : date("Y-m-d");
        $departments = Department::find($user_data->dept_id);
        $phpMail = new PHPMailer();
        $message = "";

        $task = MOS::limit(50);
        $task->whereIn("id", $ids);
        $task->orderBy("kra_id", "ASC");
        $task->orderBy("kpi_id", "ASC");
        $task->orderBy("id", "ASC");
        $items = $task->get();
        $user = $user_data["name"];
        $phpMail->AddAddress("shahidul.alam@ssgbd.com", "Permission");
        $phpMail->AddCC("sayed@ssgbd.com", "System CC");
        $phpMail->AddCC("tasnim.tabassum@ssgbd.com", "Tasnim Tabassum");
        $form = $user_data->ad_mail;
        $data["role_id"] = $user_data->role_id;
        $data["link"] =
            URL::to("/") .
            "/api/modification_permission_approved?ids=" .
            implode("_", $ids);
        $sender = "management.desk@ssgbd.com";
        $nextmonth = "";
        $where = "";
        if ($user_data->role_id == 5) {
            $where = "?dept_id=" . $user_data->dept_id;
        } else {
            $where = "?user_id=" . $user_data->id;
        }
        $data["items"] = $items;
        // $data['modification_months'] =  [];
        $data["nextmonth"] = $to_date;
        $data["note"] = "HOD already acknowledged ({$user_data->name} : {$user_data->employee_id} )";
        $data["to_date"] = $to_date;
        $data["from_date"] = $from_date;
        $data["department"] = $departments;
        //$data['note'] = $request->note;
        $data["panel_link"] =
            str_replace("backend/public", "", URL::to("/")) .
            "/target_permission_list" .
            $where;
        $message = view("mail.target_permission")->with(["data" => $data]);
        $msg = nl2br($message);
        $phpMail->FromName = $user;
        $phpMail->From = $form;
        $phpMail->Sender = $sender;
        $phpMail->IsHTML(true);
        $phpMail->Host = "mail.ssgbd.com:25";
        $phpMail->IsSMTP();
        $phpMail->Mailer = "smtp";
        $phpMail->Subject = "MOS Target Modify permission";
        $phpMail->Body = $msg;
        $phpMail->SMTPAuth = false;

        if (!$phpMail->Send()) {
            echo "Message could not be sent.";
            echo "Mailer Error: " . $phpMail->ErrorInfo;
            exit();
        }

        $phpMail->ClearAddresses();
        $phpMail->ClearAttachments();

        //return $this->sendResponse($request, ' Permission update successfully');
        return redirect(
            str_replace("backend/public", "", URL::to("/")) .
                "?permission=Permission approved successfully"
        );
    }

    public function fo_performance_month_wise(Request $request)
    {

       
        $employee = User::where("email", $request->foid)
            ->orWhere("employee_id", $request->foid)
            ->first();

           

        if (!$employee) {
            $url = "http://magpie.hris.ssgbd.com/api/EmployeeInfoBPT?empCode=" . $request->foid;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $hrisResult = json_decode($response, true); 
            $hrisResult = $hrisResult[0];
            $departmentHead = [];
            $supervisorId = []; 
            if ($hrisResult) {
                if (isset($hrisResult["departmentHeadId"])) {
                    $departmentHead = User::where("email", $hrisResult["departmentHeadId"])
                        ->orWhere("employee_id", $hrisResult["departmentHeadId"])
                        ->first();
                }
                if (isset($hrisResult["supervisorId"])) {
                    $supervisorId = User::where("email", $hrisResult["supervisorId"])
                        ->orWhere("employee_id", $hrisResult["supervisorId"])
                        ->first();
                }
            }
            $userData["password"] = bcrypt(12345);
            $userData["dept_id"] = $departmentHead
                ? $departmentHead->dept_id
                : 1;
            $userData["wing_id"] =
                $supervisorId && $supervisorId->wing_id
                ? $supervisorId->wing_id
                : 0;
            $userData["email"] = $request->foid;
            $userData["name"] = $hrisResult
                ? $hrisResult["employeeName"]
                : $request->foid;
            $userData["ad_mail"] =
                $hrisResult && isset($hrisResult["email"])
                ? $hrisResult["email"]
                : "";
            $userData["role_id"] = 7;
            $userData["status"] = 1;
            $userData["designation"] = $hrisResult
                ? $hrisResult["designation"]
                : "";
            $userData["employee_id"] = $request->foid;
            $employee = User::create($userData);
        }

        if (!KRA::where("user_id", $employee->id)
            ->where("year", $request->year)
            ->exists()) {
            $this->foMosCreat($request->year, $employee);
        }

        $url = "https://ssforce.ssgbd.com/api/fo_performance_month_wise?year=" . $request->year
            . "&foid=" . $request->foid . "&type=" . $request->type;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $foResult =  json_decode($response, true);

        if (isset($foResult['status']) && $foResult['status'] == 1) {
            // print_r($mosDataIms );
            $mosDataIms = $foResult['ims'];
            $this->foMosTasgetAchiv('Business Revenue', 'IMS', 'As per Target Achievement', 35, $request->year,  $mosDataIms, $employee);
            //collection
            $collection = $foResult['collection'];
            $this->foMosTasgetAchiv('Business Revenue', 'Collection', 'As per Target Achievement', 35, $request->year,  $collection, $employee);
            //memo
            $memo = $foResult['memo'];
            $this->foMosTasgetAchiv('Coverage & Growth', 'No of Memo Target', 'As per Target Achievement', 10, $request->year,  $memo, $employee);
            //retailer
            $retailer = $foResult['retailer'];
            $this->foMosTasgetAchiv('Coverage & Growth', 'Retail business coverage', 'As per Target Achievement', 5, $request->year,  $retailer, $employee);
            //pg
            $pg = $foResult['pg'];
            $this->foMosTasgetAchiv('Coverage & Growth', 'PG Coverage', 'As per Target Achievement', 10, $request->year,  $pg, $employee);
            //fo_attendance
            $fo_attendance = $foResult['fo_attendance'];
            $this->foMosTasgetAchiv('Discipline', 'Attendence', '100% Attendence', 5, $request->year,  $fo_attendance, $employee);
        }

        // echo 'Success.......!!!';
        // echo "\n";
        //return $this->sendResponse($hrisResult, ' Permission update successfully');
    }

    public function foMosTasgetAchiv($kra, $kpi, $mos, $weightage, $year,  $mosData, $employee)
    {
        $target  = isset($mosData['target']) ?  $mosData['target'] : null;
        $achievement = [];
        $achievement  = isset($mosData['achievement']) ? $mosData['achievement'] : null;

        $mosInfo = MOS::select('m_o_s.*')
            ->join('k_p_i_s', 'k_p_i_s.id', 'm_o_s.kpi_id')
            ->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id')
            ->where('m_o_s.mos_name', $mos)
            ->where('k_r_a_s.user_id', $employee->id)
            ->where('k_r_a_s.year', $year)
            ->where('k_p_i_s.kpi_name', $kpi)
            ->where('k_r_a_s.kra_name', $kra)
            ->orderBy('m_o_s.id', 'DESC')
            ->first();
        if ($mosInfo) {
            $mosInfo->weightage = $weightage;
            $mosInfo->save();
        }

        if ($kpi == 'Attendence') {
            $targetData['january'] =  30;
            $targetData['february'] =  30;
            $targetData['march'] =  30;
            $targetData['april'] =  30;
            $targetData['may'] =  30;
            $targetData['june'] =  30;
            $targetData['july'] =  30;
            $targetData['august'] =  30;
            $targetData['september'] =  30;
            $targetData['october'] =  30;
            $targetData['november'] =  30;
            $targetData['december'] =  30;
        } else {
            if ($target) {
                $targetData['january'] =   $kpi != 'Collection' ? $target[1]['target'] : $target[1]['achievement'];
                $targetData['february'] =   $kpi != 'Collection' ? $target[2]['target'] : $target[2]['achievement'];
                $targetData['march'] =   $kpi != 'Collection' ? $target[3]['target'] : $target[3]['achievement'];
                $targetData['april'] =   $kpi != 'Collection' ? $target[4]['target'] : $target[4]['achievement'];
                $targetData['may'] =   $kpi != 'Collection' ? $target[5]['target'] : $target[5]['achievement'];
                $targetData['june'] =   $kpi != 'Collection' ? $target[6]['target'] : $target[6]['achievement'];
                $targetData['july'] =   $kpi != 'Collection' ? $target[7]['target'] : $target[7]['achievement'];
                $targetData['august'] =   $kpi != 'Collection' ? $target[8]['target'] : $target[8]['achievement'];
                $targetData['september'] =   $kpi != 'Collection' ? $target[9]['target'] : $target[9]['achievement'];
                $targetData['october'] =   $kpi != 'Collection' ? $target[10]['target'] : $target[10]['achievement'];
                $targetData['november'] =   $kpi != 'Collection' ? $target[11]['target'] : $target[11]['achievement'];
                $targetData['december'] =   $kpi != 'Collection' ? $target[12]['target'] : $target[12]['achievement'];
            }
        }

        $update_target = MosData::where('mos_id', $mosInfo->id)->where('type', 'target')->first();

        $update_target->fill($targetData)->save();
        if (!empty($achievement)) {
            $achivData['january'] =  isset($achievement[1]['achievement']) ? $achievement[1]['achievement'] : null;
            $achivData['february'] =  isset($achievement[2]['achievement']) ? $achievement[2]['achievement'] : null;
            $achivData['march'] =  isset($achievement[3]['achievement']) ? $achievement[3]['achievement'] : null;
            $achivData['april'] =  isset($achievement[4]['achievement']) ? $achievement[4]['achievement'] : null;
            $achivData['may'] =  isset($achievement[5]['achievement']) ? $achievement[5]['achievement'] : null;
            $achivData['june'] =  isset($achievement[6]['achievement']) ? $achievement[6]['achievement'] : null;
            $achivData['july'] =  isset($achievement[7]['achievement']) ? $achievement[7]['achievement'] : null;
            $achivData['august'] =  isset($achievement[8]['achievement']) ? $achievement[8]['achievement'] : null;
            $achivData['september'] =  isset($achievement[9]['achievement']) ? $achievement[9]['achievement'] : null;
            $achivData['october'] =  isset($achievement[10]['achievement']) ? $achievement[10]['achievement'] : null;
            $achivData['november'] =  isset($achievement[11]['achievement']) ? $achievement[11]['achievement'] : null;
            $achivData['december'] =  isset($achievement[12]['achievement']) ? $achievement[12]['achievement'] : null;

            $achievement_update = MosData::where('mos_id', $mosInfo->id)
                ->where('type', 'achievement')
                ->first();

            $achievement_update->fill($achivData)->save();
        }
    }

    public function foMosCreat($year, $employee)
    {
        $dataArray = [
            [
                "kra_name" => "Business Revenue",
                "weightage" => 70,
                "kpi" => [
                    [
                        "kpi_name" => "IMS",
                        "weightage" => 35,
                        "mos" => [
                            [
                                "mos_name" => "As per Target Achievement",
                                "weightage" => 35,
                            ],
                        ],
                    ],
                    [
                        "kpi_name" => "Collection",
                        "weightage" => 35,
                        "mos" => [
                            [
                                "mos_name" => "As per Target Achievement",
                                "weightage" => 35,
                            ],
                        ],
                    ],
                ],
            ],
            [
                "kra_name" => "Coverage & Growth",
                "weightage" => 25,
                "kpi" => [
                    [
                        "kpi_name" => "Retail business coverage",
                        "weightage" => 10,
                        "mos" => [
                            [
                                "mos_name" => "As per Target Achievement",
                                "weightage" => 10,
                            ],
                        ],
                    ],
                    [
                        "kpi_name" => "No of Memo Target",
                        "weightage" => 5,
                        "mos" => [
                            [
                                "mos_name" => "As per Target Achievement",
                                "weightage" => 5,
                            ],
                        ],
                    ],
                    [
                        "kpi_name" => "PG Coverage",
                        "weightage" => 10,
                        "mos" => [
                            [
                                "mos_name" => "As per Target Achievement",
                                "weightage" => 10,
                            ],
                        ],
                    ],
                ],
            ],
            [
                "kra_name" => "Discipline",
                "weightage" => 5,
                "kpi" => [
                    [
                        "kpi_name" => "Attendence",
                        "weightage" => 5,
                        "mos" => [
                            [
                                "mos_name" => "100% Attendence",
                                "weightage" => 5,
                            ],
                        ],
                    ],
                ],
            ],
        ];
        foreach ($dataArray as $key_kra => $kra) {
            $newkra =  KRA::create([
                'kra_name' =>  $kra['kra_name'],
                'dept_id' => $employee->dept_id ?  $employee->dept_id : 1,
                'year' => $year,
                'user_id' => $employee ? $employee->id :  0,
                'role_id' => $employee ? $employee->role_id :  0,
                'rep_id' => 0,
                'wing_id' => $employee ? $employee->wing_id :  0,
                'kra_weight' => $kra['weightage']
            ]);

            foreach ($kra['kpi'] as $key_kpi => $kpi) {

                $newkpi = KPI::create([
                    'kra_id' => $newkra['id'],
                    'kpi_name' =>  $kpi['kpi_name'],
                    'rep_id' => 0,
                    'dept_id' => $employee->dept_id ?  $employee->dept_id : 1,
                    'year' => $year,
                    //'user_id' => $employee ? $employee->id :  0 , 
                    //'role_id' => $employee ? $employee->role_id :  0 , 
                    'kpi_weight' =>   $kpi['weightage']
                ]);

                foreach ($kpi['mos'] as $key_mos => $mos) {
                    $mOS = MOS::create([
                        'kra_id' => $newkra['id'],
                        'dept_id' => $employee->dept_id ?  $employee->dept_id : 1,
                        'kpi_id' => $newkpi['id'],
                        'mos_name' =>  $mos['mos_name'] ?  $mos['mos_name'] : '',
                        'year' => $year,
                        'weightage' =>  $mos['weightage']
                    ]);
                    $data['mos_id'] = $mOS->id;
                    $data['type'] = 'target';
                    $data['year'] =  $year;
                    $data['dept_id'] =  $employee->dept_id ?  $employee->dept_id : 1;
                    $data['january'] =  0;
                    $data['february'] =  0;
                    $data['march'] =  0;
                    $data['april'] =  0;
                    $data['may'] =  0;
                    $data['june'] =  0;
                    $data['july'] =  0;
                    $data['august'] =  0;
                    $data['september'] =  0;
                    $data['october'] =  0;
                    $data['november'] =  0;
                    $data['december'] =  0;
                    $data['total'] =  0;
                    $target  = MosData::create($data);
                    $data['type'] = 'module';
                    $module  = MosData::create($data);
                    $data['type'] = 'achievement';
                    $achievement  = MosData::create($data);
                }
            }
        };
        return true;
    }
}
