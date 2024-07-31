<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTourUserAPIRequest;
use App\Http\Requests\API\UpdateTourUserAPIRequest;
use App\Models\TourUser;
use App\Models\User;
use App\Repositories\TourUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response , DB , Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Resources\TourBusinessTypeResource;
use App\Models\TourPlanBusinessType;

use App\Models\Division; 
/**
 * Class TourUserController
 * @package App\Http\Controllers\API
 */

class TourUserAPIController extends AppBaseController
{
    /** @var  TourUserRepository */
    private $tourUserRepository;
    private $userRepository;

    public function __construct(TourUserRepository $tourUserRepo, UserRepository $userRepo)
    {
        $this->tourUserRepository = $tourUserRepo;
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the TourUser.
     * GET|HEAD /tourUsers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tourUsers = $this->userRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($tourUsers->toArray(), 'Tour Users retrieved successfully');
    }
    public function tourPlanHelper(Request $request)
    { 
        // $tourBusinessTypeList = TourPlanBusinessType::all();
        // $tourBusinessTypeList = TourBusinessTypeResource::collection($tourBusinessTypeList); 
        // $division = Division::all(); 

        // $helperData = array(
        //         'designation'=> $this->tour_designation(),
        //         'divisions' => $division,
        //         'businessType' => $tourBusinessTypeList,
        //         'users' => ''
        //     );
        // return $this->sendResponse( $helperData , 'Tour helper data retrieved successfully');
    }

    public function tour_user_list(Request $request){ 
        $designation = $request->designation;
        $sattus = $request->actv_status; 
        $business_type = $request->business_type; 
        $users =  TourUser::with(['businesstype' => function($query){
                $query->select('id','title');
            }])->with(['user' => function($query){
                $query->select('id','division_id');
            }])->select('tour_users.*', 'users.name',  'users.email',  'users.ad_mail' , 'users.phone', 'users.employee_id', 'users.designation', 'users.status')
        ->join('users','users.id','tour_users.user_id') 
        ->where(function($query)use($sattus){
            if($sattus==0){
                $query->where('users.status',1);
            }else{
                $query->where('users.status',0);                    
            }
        })
        ->where(function($query)use($designation){
            if($designation){
                $query->where('users.designation',$designation);
            }
        })
        ->when($business_type, function($q,$business_type){
            return $q->where('tour_users.business_type', $business_type);
        })
        ->get();
        
         
        return $this->sendResponse( $users , 'Tour Users retrieved successfully');

    }

    public function tour_users(Request $request)
    {
        $user_data = Auth::user();

        $users = DB::table('users')
            ->where('iactive', 0)
            ->where(function ($query) {
                $query->where('tour_permission', '=', 1)
                    ->orWhere('designation', '=', 'General Manager')
                    ->orWhere('designation', '=', 'Sales Manager')
                    ->orWhere('designation', '=', 'Assistant Divisional Sales Manager')
                    ->orWhere('designation', '=', 'Divisional Sales Manager');
            })
            //->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID need correnction 
            ->orderBy('id')->get();


        $rsm = DB::table('users')
            ->where('tour_permission', 1)
            ->where('designation', 'Regional Sales Manager')
            //->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID need correnction 
            ->orderBy('id')->get();

        $adsm = DB::table('users')
            ->where('tour_permission', 1)
            ->where('designation', 'Assistant Divisional Sales Manager')
            //->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID  need correnction 
            ->orderBy('id')->get();

        $dsm = DB::table('users')
            ->where('tour_permission', 1)
            ->where('designation', 'Divisional Sales Manager')
            //->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID need correnction 
            ->orderBy('id')->get();

        $asm = DB::table('users')
            ->where('tour_permission', 1)
            ->where('designation', 'Assistant Sales Manager')
            //->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID need correnction 
            ->orderBy('id')->get();

        $sm = DB::table('users')
            ->where('tour_permission', '=', 1)
            ->where('dmd_tour',1)
            ->where(function ($query) {
                $query->where('designation', '=', 'Sales Manager')
                    ->orWhere('designation', '=', 'Assistant General Manager');
            })
            //->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID need correnction 
            ->orderBy('id')->get();

        $divisional_head = DB::table('users')
            ->where('tour_permission', 1)
            ->where('designation', 'DIVISONAL_HEAD')
            // ->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID need correnction 
            ->orderBy('id')->get();

        // $hos = DB::table('users')
        // ->where('tour_permission', 1)
        // ->where('head_of_sales', 1)
        // ->orderBy('id')->get();
        
        $hos = DB::table('users')
            ->where('tour_permission', 1)
            ->where('designation', 'General Manager')
            //->where('dept_id', $user_data->dept_id) //ADD WHERE CLAUSE BY DEPT ID will need correnction  
            ->orderBy('id')->get();

        $return_data = array(
            'users' => $users,
            'rsm' => $rsm,
            'adsm' => $adsm,
            'dsm' => $dsm,
            'asm' => $asm,
            'sm' => $sm,
            'divisional_head' => $divisional_head,
            'hos' => $hos
        );


        return $this->sendResponse($return_data, 'Tour Users retrieved successfully');
    }

    public function tour_designation(Request $request)
    {
        $user = Auth::user();
        $designations = DB::table('users')
        ->select('designation')
        ->where('status', 1)
        ->where('tour_permission', '=', 1)
        ->Where('designation', '!=', '')
        ->where(function($query)use($user){
            if($user->id == 1027){//Note: this condtion is for sadi sir 
               $query->whereIn('designation',['Assistant General Manager','Assistant Sales Manager','Divisional Sales Manager','General Manager','Manager']);
            }
        })
        ->groupBy('designation')
        ->orderBy('designation', 'DESC')
        ->get();

        $return_data = array(
            'users' => $designations,
        );


        return $this->sendResponse($return_data, 'Tour Users retrieved successfully');
    }

    public function tour_supervisor(Request $request){
        $user_data = Auth::user();
       
       
        $designation = $request->designation;
        $supervisorQuery =  TourUser::select(
            //'tour_users.*',
            'users.id',  
            'users.name', 
            //'users.name as text', 
             DB::raw('CONCAT( users.name , " : " , IFNULL( users.employee_id , 0 ) ) AS text'),
            'users.email',  
            'users.ad_mail' , 
            'users.phone', 
            'users.employee_id', 
            'users.designation', 
            'users.status');
        $supervisorQuery->join('users','users.id','tour_users.user_id');
        $supervisorQuery->where('users.status' , 1 ) ;
        if($designation){ 
            $supervisorQuery->where(function($query)use($designation){
                $query->where('users.designation',$designation);
                if($designation=='Assistant General Manager'){ 
                    $query->where('tour_users.dmd_tour',1);
                } 
            });
           
        }else{

            
            if($request->division_id){
                $supervisorQuery->where('tour_users.division_id',$request->division_id); 
            }
            //return $this->sendResponse( $user_data , '3 Tour Users retrieved successfully');  
             
            if($user_data->designation =='Sales Manager' || $user_data->designation=='Assistant General Manager' ){
                $supervisorQuery->where('tour_users.sm',$user_data->id);  
                
            }elseif($user_data->designation=='Assistant Sales Manager'){
                $supervisorQuery->where('tour_users.asm',$user_data->id);
            }elseif($user_data->designation=='Divisional Sales Manager'){
                $supervisorQuery->where('tour_users.dsm',$user_data->id);
            }elseif($user_data->designation=='Assistant Divisional Sales Manager'){
                $supervisorQuery->where('tour_users.adsm',$user_data->id);
            }elseif($user_data->designation=='Regional Sales Manager'){
                $supervisorQuery->where('tour_users.rsm',$user_data->id);
            }elseif($user_data->designation== 'Director'){
                $supervisorQuery->whereIn('users.designation',['Assistant General Manager','General Manager'])
                        ->where('tour_users.dmd_tour','=', 1); 
            }elseif($user_data->designation== 'General Manager'){
                $supervisorQuery->whereIn('users.designation',['Assistant General Manager','General Manager']); 
                $supervisorQuery->where('users.dept_id',$user_data->dept_id); 
                
            }elseif($user_data->designation== 'Sales Admin' && $user_data->role_id == 5 ){
                $supervisorQuery->whereIn('users.designation',['Assistant General Manager']); 
                //$supervisorQuery->where('users.dept_id',$user_data->dept_id); 
                
            }
            elseif($user_data->email== 'hrashid' ||  $user_data->email== 'sysadmin' ||  $user_data->email== 'sales'){
               // $supervisorQuery->where('tour_program',1);
               $supervisorQuery->where('tour_users.dmd_tour',1);
               
            }elseif($user_data->role_id == 6){
                $supervisorQuery->where('users.wing_id',$user_data->wing_id);
            }elseif($user_data->role_id == 7){
                $supervisorQuery->where('users.id',$user_data->id);
            }else{
                $supervisorQuery->where('users.id',$user_data->id); 
            }
           

        }
        $supervisor = $supervisorQuery->orderBy('name')->get(); 
        return $this->sendResponse($supervisor, 'Tour Users retrieved successfully');             
         
    }

    public function supervisor(Request $request)
    {
        $user_data = Auth::user();
        $designation = $request->designation;
        if($designation){
            $q = DB::table('users')
            ->where('status',1)
            ->where(function($query)use($designation){
                if($designation=='Assistant General Manager'){
                    $query->where('designation',$designation)
                    ->where('dmd_tour','=', 1);
                }else{
                    $query->where('designation',$designation);
                }
            })
            ->where('tour_permission',1);
            if($request->division_id){
                $q->where('division_id', $request->division_id); 
            }
            $supervisor = $q->orderBy('name')->get();
            
            return $this->sendResponse($supervisor, 'Tour Users retrieved successfully');            
        }

        
        
        $supervisor = DB::table('users')
        ->where('status',1);
        if($request->division_id){
            $supervisor->where('division_id',$request->division_id); 
        }
        if($user_data->designation=='Sales Manager' || $user_data->designation=='Assistant General Manager'){
            $supervisorResult = $supervisor->where('sm',$user_data->id)->orderBy('id')->get();
        }
        elseif($user_data->designation=='Assistant Sales Manager'){
            $supervisorResult = $supervisor->where('asm',$user_data->id)->orderBy('id')->get();
        }
        elseif($user_data->designation=='Divisional Sales Manager'){
            $supervisorResult = $supervisor->where('dsm',$user_data->id)->orderBy('id')->get();
        } 
        elseif($user_data->designation=='Assistant Divisional Sales Manager'){
            $supervisorResult = $supervisor->where('adsm',$user_data->id)->orderBy('id')->get();
        }
        elseif($user_data->designation=='Regional Sales Manager'){
            $supervisorResult = $supervisor->where('rsm',$user_data->id)->orderBy('id')->get();
        }else{
            if($user_data->usertype=='A'){
                if($user_data->email=='hrashid'){
                    $supervisorResult = $supervisor->where('dmd_tour','=', 1)
                    ->orderBy('name')->get();
                }else{
                    //IF SADI SIR LOGIN
                    if($user_data->id == 1027 || $user_data->id == 227){
                        $supervisorResult = $supervisor
                        ->whereIn('designation',['Assistant General Manager','General Manager'])
                        ->where('dmd_tour','=', 1)
                        ->orderBy('name')->get();
                    }else{
                        $supervisorResult = $supervisor->where(function($query)
                        {
                            $query->where('email','>',30)
                            ->orWhere('email','=','sysadmin')
                            ->orWhere('email','=','ibrahim')
                            ->orWhere('email','=','sales');
                        })
                        ->where('tour_program','=', 1)
                        ->orderBy('name')->get();
                    }                   
                }
            }elseif($user_data->usertype=='D'){   
                if($user_data->id == 227){
                    $supervisorResult = $supervisor->where('designation','Assistant General Manager')
                    ->where('dept_id',41)
                    ->orderBy('name')->get();
                }
                // elseif($user_data->role_id == 5){ Note:THis Code this for only HOD
                //     $supervisorResult = $supervisor->where('dept_id',$user_data->dept_id)
                //     // ->where('tour_program','=', 1)
                //     ->orderBy('name')->get();
                // }
                else{
                    $supervisorResult = $supervisor->where('tour_program','=', 1)
                    ->orderBy('name')->get();
                }
            }elseif($user_data->dept>0){
                $supervisorResult = $supervisor->where('rsm',$user_data->dept)->orderBy('id')->get();
            }else{
                if($user_data->email=='solaradmin'){//Note:this condition is for solar admin user
                    $supervisorResult = $supervisor->where('dept_id',$user_data->dept_id)->orderBy('id')->get();
                }else{
                    $supervisorResult = $supervisor->where('email',$user_data->email)->orderBy('id')->get();
                }
            }
        }

        return $this->sendResponse($supervisorResult, 'Tour Users retrieved successfully');
    }

    /**
     * Store a newly created TourUser in storage.
     * POST /tourUsers
     *
     * @param CreateTourUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTourUserAPIRequest $request)
    {


        try {
            DB::beginTransaction();
            $input = $request->all();

           // $tourUser = $this->tourUserRepository->create($input);

            // $data['email'] = $request->email;
            // $data['password'] = Hash::make($request->password);
            // $data['dept_id'] = 1;

            $input['iactive'] = 0 ;

            $this->userRepository->create($input);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Something went wrong');
        }

        return $this->sendResponse($tourUser->toArray(), 'Tour User saved successfully');
    }

    /**
     * Display the specified TourUser.
     * GET|HEAD /tourUsers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TourUser $tourUser */
        $tourUser = $this->tourUserRepository->find($id);

        if (empty($tourUser)) {
            return $this->sendError('Tour User not found');
        }

        return $this->sendResponse($tourUser->toArray(), 'Tour User retrieved successfully');
    }

    public function tour_user_edit($id){
        $data  =  User::select('users.id',  'users.name', 'users.email', 'users.employee_id', 'users.phone', 'users.designation', 'users.dept_id', 'users.role_id',  'users.la', 'users.sap_code', 'users.ad_mail', 'users.status', 'tour_users.user_id', 'tour_users.base_station_address', 'tour_users.business_type', 'tour_users.head_of_sales', 'tour_users.division_head', 'tour_users.division_id', 'tour_users.dmd_tour', 'tour_users.sm', 'tour_users.dsm', 'tour_users.asm', 'tour_users.adsm', 'tour_users.rsm')
        ->where('users.id',$id)
        ->join('tour_users','tour_users.user_id','users.id')
        ->first();
        return $this->sendResponse($data , 'Tour User successfully');

    }

    /**
     * Update the specified TourUser in storage.
     * PUT/PATCH /tourUsers/{id}
     *
     * @param int $id
     * @param UpdateTourUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTourUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var TourUser $tourUser */
        $tourUser = $this->tourUserRepository->find($id);

        if (empty($tourUser)) {
            return $this->sendError('Tour User not found');
        }

        $tourUser = $this->tourUserRepository->update($input, $id);

        return $this->sendResponse($tourUser->toArray(), 'TourUser updated successfully');
    }

    /**
     * Remove the specified TourUser from storage.
     * DELETE /tourUsers/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TourUser $tourUser */
        $tourUser = $this->tourUserRepository->find($id);

        if (empty($tourUser)) {
            return $this->sendError('Tour User not found');
        }

        $tourUser->delete();

        return $this->sendSuccess('Tour User deleted successfully');
    }
}
