<?php

namespace App\Http\Controllers\API;
use App\Http\Requests\API\CreateMOSAPIRequest;
use App\Http\Requests\API\UpdateMOSAPIRequest;
use App\Models\MOS;
use App\Models\KRA;
use App\Models\KPI;
use App\Models\MosData;
use App\Models\Department;
use App\Models\TeamMember;
use App\Models\UesrMos;
use App\Models\Wing;
use App\Models\MosFeadback;
use App\Repositories\MOSRepository;
use App\Repositories\MosDataRepository;
use App\Http\Resources\MosTreeResource;
use App\Http\Resources\MosTreeResourceUnassign;
use App\Http\Resources\KraResource;
use App\Http\Resources\KraEmployeeWiseResource;
use App\Http\Resources\MosEmployeeWiseResource;
use App\Http\Resources\MosItemResource;
use App\Http\Resources\KraIndividualUpdateResource;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\KpiTreeResource;
use Response;
use DB;
use Auth ;
use Illuminate\Foundation\Auth\User;



class KraIndividualUpdateController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user();
        $month_name = date("F", mktime(0, 0, 0, $request->month?$request->month:date('m'), 10));
        //Note:Departments data
        $q = Department::where('status', 1);
        if($request->dept_id){
            $q->where('id', $request->dept_id); 
        }
        $q->where('iskra', 1);
        $departments = $q->get();
        $kra_departments_id = $departments->pluck('id');

        //Note:Departments Wise User list data
        $UserQuery = User::leftJoin('uaer_shares', 'uaer_shares.user_id', 'users.id');
        $UserQuery->where('users.status',1);
        if($request->dept_id){
            $UserQuery->where('users.dept_id',$request->dept_id);
            // $UserQuery->where('uaer_shares.dept_id',$request->dept_id);
        }else{
            $UserQuery->whereIN('users.dept_id',$kra_departments_id);
        }
        $UserQuery->whereNotIn('designation', ['Field Officer', 'Senior Field Officer', 'Territory Sales Manager', 'Junior Territory Sales Manager', 'Regional Sales Manager']);
        $UserQuery->select('users.dept_id','users.id','users.role_id');
        
        $Users = $UserQuery->get()->groupBy('dept_id');
        //Note:Departments Wise User Monthly Entry List
        $mosQuery = KRA::select('k_r_a_s.dept_id','k_r_a_s.user_id','k_r_a_s.year' , 'users.dept_id as u_dept');
        $mosQuery->join('users','users.id','k_r_a_s.user_id');
        if($request->dept_id){
            $mosQuery->where('k_r_a_s.dept_id',$request->dept_id);
            // $mosQuery->where('users.dept_id',$request->dept_id);
        }else{
            // $mosQuery->where('k_r_a_s.dept_id','users.dept_id');            
        }
        if($request->year){
            $mosQuery->where('k_r_a_s.year',$request->year);
        }else{
            $mosQuery->where('k_r_a_s.year',date('Y'));
        }
        $mosQuery->where('users.status',1);
        $mosQuery->groupBy('k_r_a_s.user_id');
        $mosQuery->groupBy('k_r_a_s.dept_id');
        $mosQuery->groupBy('k_r_a_s.year');

        $mosData = $mosQuery->get()->groupBy('dept_id');
       
        $mosAchvQuery = MosData::select('k_r_a_s.dept_id','k_r_a_s.user_id','k_r_a_s.year',DB::Raw("SUM(mos_datas.$month_name) as months"),"mos_datas.$month_name");
        $mosAchvQuery->join('m_o_s','m_o_s.id','mos_datas.mos_id');       
        $mosAchvQuery->join('k_r_a_s','k_r_a_s.id','m_o_s.kra_id');
        $mosAchvQuery->join('users','users.id','k_r_a_s.user_id');
        if($request->dept_id){
            $mosAchvQuery->where('k_r_a_s.dept_id',$request->dept_id);
            $mosAchvQuery->where('users.dept_id',$request->dept_id);
        }else{
            // $mosQuery->where('k_r_a_s.dept_id','users.dept_id');            
        }
        if($request->year){
            $mosAchvQuery->where('k_r_a_s.year',$request->year);
        }else{
            $mosAchvQuery->where('k_r_a_s.year',date('Y'));
        }
        $mosAchvQuery->where('users.status',1);
        $mosAchvQuery->where($month_name,'!=',0);
        $mosAchvQuery->where('mos_datas.type','achievement');        
        $mosAchvQuery->groupBy('k_r_a_s.user_id');
        $mosAchvQuery->groupBy('k_r_a_s.dept_id');
        $mosAchvQuery->groupBy('k_r_a_s.year');
        $mosAchvQuery->groupBy("mos_datas.$month_name");
        $mosAchv = $mosAchvQuery->get()
        ->groupBy('dept_id'); 

        foreach($departments as $key=>$department){
            $department_users = isset($Users[$department->id])?$Users[$department->id]:[];
            $kra_data = isset($mosData[$department->id])?$mosData[$department->id]->keyBy('user_id'):[]; 
            $kraAchv_data = isset($mosAchv[$department->id])?$mosAchv[$department->id]->keyBy('user_id'):[]; 
            $departments[$key]['users'] = count($department_users);    
            $departments[$key]['upload_kra'] = count($department_users) < count($kra_data) ? count($department_users):count($kra_data);
            $departments[$key]['kra_data'] = $kra_data;


            $mosDeptAchvQuery = MosData::select(
                'k_r_a_s.dept_id',
                'k_r_a_s.user_id',
                'k_r_a_s.year',
                DB::raw("SUM(mos_datas.$month_name) as months"),
                "mos_datas.$month_name"
            )
                ->join('m_o_s', 'm_o_s.id', '=', 'mos_datas.mos_id')
                ->join('k_r_a_s', 'k_r_a_s.id', '=', 'm_o_s.kra_id')
                ->where('k_r_a_s.dept_id', $departments[$key]['id'])
                ->where('k_r_a_s.role_id', 5)
                ->where(function ($query) use ($request) {
                    if ($request->year) {
                        $query->where('k_r_a_s.year', $request->year);
                    } else {
                        $query->where('k_r_a_s.year', date('Y'));
                    }
                })
                ->where($month_name, '!=', 0)
                ->where('mos_datas.type', 'achievement')
                ->count();



            $departments[$key]['dept_upload_kra'] = $mosDeptAchvQuery ;
            $departments[$key]['users_list'] = $department_users;   

            $kra_updated = 0;
            $kra_due = 0;

            if(sizeof($department_users)>0){
                foreach($department_users as $key_user=> $department_user){
                    // if($department_user->role_id==5){
                    //     $UserKra_data = isset($kra_data[""])?$kra_data[""]:[];
                    // }else{
                    $UserKra_data = isset($kraAchv_data[$department_user->id])?$kraAchv_data[$department_user->id]:[];
                    // }
                    $months_data = isset($UserKra_data->months)?$UserKra_data->months:0;
                    $months_data>0?$kra_updated+=1:0;
                }
            }
            $departments[$key]['kra_updated'] = $kra_updated;
            $departments[$key]['kra_due'] = $departments[$key]['upload_kra'] - $kra_updated;         
        }
        $departments = KraIndividualUpdateResource::collection($departments);
        return $this->sendResponse($departments, 'Data retrieved succesvxfsfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $month_name = strtolower(date("F", mktime(0, 0, 0, $request->month?$request->month:date('m'), 10)));
        if($request->type){
            $kraUserId = KRA::join('users','users.id','k_r_a_s.user_id')
            ->where('k_r_a_s.dept_id', $request->dep_id)
            // ->where('users.dept_id',$request->dept_id)
            ->where('k_r_a_s.year' , $request->year)
            ->where('users.status',1)
            ->pluck('user_id')
            ->unique()
            ->values();
            
            $user = User::select('users.id','users.name' , 'users.email','users.employee_id')
            ->leftJoin('uaer_shares', 'uaer_shares.user_id', 'users.id')
            ->where('users.dept_id' , $request->dep_id)
            ->whereNotIn('designation', ['Field Officer', 'Senior Field Officer', 'Territory Sales Manager', 'Junior Territory Sales Manager', 'Regional Sales Manager'])
            ->whereNotIn('users.id' , $kraUserId)
            ->where('users.status' , 1)
            ->get();
        }else{
            $mosQuery = MosData::select('k_r_a_s.user_id',DB::Raw("SUM(mos_datas.$month_name) as months"));
            $mosQuery->join('m_o_s','m_o_s.id','mos_datas.mos_id');
            $mosQuery->join('k_r_a_s','k_r_a_s.id','m_o_s.kra_id');  
            $mosQuery->join('users','users.id','k_r_a_s.user_id');
            $mosQuery->whereNotIn('designation', ['Field Officer', 'Senior Field Officer', 'Territory Sales Manager', 'Junior Territory Sales Manager', 'Regional Sales Manager']);
            $mosQuery->where('users.status',1);
            $mosQuery->where('k_r_a_s.dept_id', $request->dep_id);
            $mosQuery->where('k_r_a_s.year',$request->year);
            $mosQuery->where('mos_datas.type','achievement');
            $mosQuery->groupBy('k_r_a_s.user_id');
            $mosQuery->having('months', '>', 0); 
            $mosUser = $mosQuery->pluck('user_id'); 
            
            $user = KRA::select('users.id','users.name' , 'users.email' ,'users.employee_id')
            ->join('users','users.id','k_r_a_s.user_id')
            ->where('k_r_a_s.dept_id', $request->dep_id)
            ->where('k_r_a_s.year',$request->year)
            ->whereNotIn('k_r_a_s.user_id' , $mosUser)
            ->where('users.status' , 1)
            ->groupBy('k_r_a_s.user_id')
            ->get();
        }
        
        $data = [
            'department' => $request->dep_id ,
            'user' => $user ,
        ];

        return $this->sendResponse($data, 'Data retrieved succesvxfsfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
