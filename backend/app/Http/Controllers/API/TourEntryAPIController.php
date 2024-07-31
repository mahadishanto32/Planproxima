<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTourEntryAPIRequest;
use App\Http\Requests\API\UpdateTourEntryAPIRequest;
use App\Http\Resources\TourBusinessTypeResource;
use App\Models\TourEntry;
use App\Http\Resources\TourPlanResource;
use App\Http\Resources\TourPlanMonthlyResource;
use App\Models\TourPlanBusinessType;
use App\Repositories\TourEntryRepository;
use Illuminate\Http\Request;
use App\Models\TourEntrieObjective;
use App\Models\TourRouteDetails;
use App\Http\Controllers\AppBaseController;
use App\Models\TourTerritoryDetails;
use App\Models\TourPointDetails;
use App\Models\TourFoDetails;
use Response;
use Auth, DB;
use Carbon\Carbon; 
use App\Models\TourUser ;
use App\Exports\TourUsersTaskExport;
use Maatwebsite\Excel\Facades\Excel;
/**
 * Class TourEntryController
 * @package App\Http\Controllers\API
 */

class TourEntryAPIController extends AppBaseController
{
    /** @var  TourEntryRepository */
    private $tourEntryRepository;

    public function __construct(TourEntryRepository $tourEntryRepo)
    {
        $this->tourEntryRepository = $tourEntryRepo;
    }

    /**
     * Display a listing of the TourEntry.
     * GET|HEAD /tourEntries
     *
     * @param Request $request
     * @return Response
     */
    public function tsm_works_with_fo(Request $request){

        // $tourViewQuery = TourEntry::select('tour_entries.*')->limit(100)->get() ;
      
        $date = $request->date ??  Carbon::now()->toDateString() ; 

        //Divisional Sales Manager
        $designation = '';
        if($request->type  == 'DSM'){
            $designation = 'Divisional Sales Manager' ; 
        }elseif($request->type  == 'TSM'){
            $designation = 'Territory Sales Manager' ; 
        }

        
       
 
        $tourViewQuery = TourEntry::where('date', $date)
                ->select('users.employee_id')
                ->join('users', 'users.id', 'tour_entries.user_id')
                ->join('tour_fo_details', 'tour_fo_details.tour_id', 'tour_entries.id'); 
                if ($request->channel) { 
                    $tourViewQuery->where('tour_fo_details.business_type_id', $request->channel);
                }
                if ($request->division) { 
                    $tourViewQuery->where('tour_fo_details.division_id', $request->division);  
                }

            $tourViewQuery = $tourViewQuery->where('users.designation', $designation)
                ->get()
                ->pluck('employee_id')
                ->unique()
                ->toArray();

                $accuracyQuery =  TourEntry::where('date', $date)
                    ->select('users.employee_id')
                    ->join('users', 'users.id', 'tour_entries.user_id')
                    ->join('tour_fo_details', 'tour_fo_details.tour_id', 'tour_entries.id');

                if ($request->channel) {
                    $accuracyQuery->join('tour_users', 'tour_users.user_id', 'tour_entries.user_id');
                    $accuracyQuery->join('tour_plan_business_types', 'tour_plan_business_types.id', 'tour_users.business_type');
                    $accuracyQuery->where('tour_plan_business_types.autmtn_id', $request->channel);
                } 
                $accuracyQuery->where('users.designation', $designation);
                $route_accuracy = $accuracyQuery->where('tour_entries.route_accuracy', 1)->count();


        return $this->sendResponse(array(
            'with_fo'=> $tourViewQuery,
            'route_accuracy' =>  $route_accuracy 
        ), 'Tour Entries retrieved successfully');

    }
    public function tour_entrie_list(Request $request)
    {  
        $designation = $request->designation;
        $hq = $request->hq;
        $user_data = Auth::user();
        //return $this->sendResponse($user_data, 'Tour Entries retrieved successfully');
        $emp_code = $request->emp_code;
        $division_id = $request->division_id;
        $business_type = $request->business_type;
 
        $tourViewQuery = TourEntry::select('tour_entries.*','users.designation','users.employee_id','users.name')
            ->with(['touruser' => function($query){
                $query->select('id','business_type','division_id','user_id');
            }])
            ->whereHas('touruser', function($query) use ($division_id, $business_type)  {
                $query->when($division_id, function($query, $division_id){
                    if($division_id !='all'){
                        return $query->where('division_id', $division_id);
                    }                    
                })->when($business_type, function($query, $business_type){
                    if($business_type !='all'){
                        return $query->where('business_type', $business_type);
                    }
                });  
            })
            ->when($designation, function($query, $designation){
                if($designation !='all'){
                    return $query->where('users.designation', $designation);
                }                
            })
            ->when($emp_code, function($query, $emp_code){
                return $query->where('users.employee_id', $emp_code);
            })
            ->when($hq, function($query, $hq){
                return $query->where('users.id', $hq);
            })
            ->join('users', 'users.id', 'tour_entries.user_id'); 
        if ($request->start_date != '' and $request->end_date != '') {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));
            $tourViewQuery->whereBetween('tour_entries.date', array($start_date, $end_date));
        } 
        if (!$request->hq && !$division_id && !$business_type && !$designation) {
            if (($user_data->designation == 'Director' || $user_data->designation == "MD's")) {
                $tourViewQuery->whereIn('users.designation', ['AGM', 'GM']);
            } elseif ($user_data->designation == 'Sales Admin' && $user_data->role_id == 5) {
                $tourViewQuery->whereIn('users.designation', ['AGM']);
            } elseif ($user_data->designation == 'GM') {
                $tourViewQuery->whereIn('users.designation', ['AGM', 'GM']);
                $tourViewQuery->where('users.dept_id', $user_data->dept_id);
            } elseif ($user_data->role_id == 9) { 
                $tourViewQuery->where('users.dept_id', $user_data->dept_id);
            } elseif ($user_data->designation == 'SM' || $user_data->designation == 'Sales Manager' || $user_data->designation == 'AGM' || $user_data->designation == 'ASM' || $user_data->designation == 'Assistant Sales Manager'
                || $user_data->designation == 'DSM' || $user_data->designation == 'Divisional Sales Manager' || $user_data->designation == 'ADSM' 
                || $user_data->designation == 'Assistant Divisional Sales Manager' || $user_data->designation == 'RSM' || $user_data->designation == 'Regional Sales Manager') {
                $tourViewQuery->where(function ($query) use ($user_data) {
                    $query->where('tour_entries.user_id', $user_data->id)
                        ->orwhere('tour_entries.hos', '=', $user_data->id)
                        ->orwhere('tour_entries.sm', '=', $user_data->id)
                        ->orwhere('tour_entries.asm', '=', $user_data->id)
                        ->orwhere('tour_entries.adsm', '=', $user_data->id)
                        ->orwhere('tour_entries.dsm', '=', $user_data->id)
                        ->orwhere('tour_entries.rsm', '=', $user_data->id); 
                });
            } else {
                $tourViewQuery->where('tour_entries.user_id', $user_data->id);
            }            
        }
        $tourViewQuery->orderBy('tour_entries.id', 'DESC');
        // $tourViewQuery->limit(600);  
        //echo $data_return = $tourViewQuery->toSql();
        //exit;
        $data_return = $tourViewQuery->get();
        //return response()->json($data_return);
        $data_return  = TourPlanResource::collection($data_return);
        return $this->sendResponse($data_return, 'Tour Entries retrieved successfully');
    }
    public function download_file(Request $request){
        $data = array(
            'designation' => $request->designation,
            'hq' => $request->hq,
            'emp_code' => $request->emp_code,
            'division_id' => $request->division_id,
            'business_type' => $request->business_type,
            'start_date' => date('Y-m-d' , strtotime($request->start_date)),
            'end_date' => date('Y-m-d' , strtotime($request->end_date)),
        );

        return Excel::download(new TourUsersTaskExport($data), 'users.xlsx');
    }

    public function tour_entrie_list_backup(Request $request)
    { // New API
        $designation = $request->designation;
        $hq = $request->hq;
        $user_data = Auth::user();
        $emp_code = $request->emp_code;
        $division_id = $request->division_id;
        $business_type = $request->business_type;
 
        $tourViewQuery = TourEntry::with(['touruser' => function($query){
                $query->select('id','business_type','division_id','user_id');
            }])
            ->whereHas('touruser', function($query) use ($division_id, $business_type)  {
                $query->when($division_id, function($query, $division_id){
                    return $query->where('division_id', $division_id);
                })->when($business_type, function($query, $business_type){
                    return $query->where('business_type', $business_type);
                }); 
            })->select( 'tour_entries.*','users.designation','users.employee_id','users.name'); 
        $tourViewQuery->join('users', 'users.id', 'tour_entries.user_id'); 
        if ($designation) {
            $tourViewQuery->where('users.designation', $designation);
        }
        if ($emp_code) {
            $tourViewQuery->where('users.employee_id', $emp_code);
        }
        if ($request->start_date != '' and $request->end_date != '') {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));
            $tourViewQuery->whereBetween('tour_entries.date', array($start_date, $end_date));
        }
        if ($request->hq) {
            $tourViewQuery->where('tour_entries.user_id', $request->hq);
        }
        if (!$request->hq && !$request->division_id && !$request->business_type && !$request->designation) {

            if (($user_data->designation == 'Director' || $user_data->designation == "MD's")) {
                $tourViewQuery->whereIn('users.designation', ['AGM', 'GM']);
            } elseif ($user_data->designation == 'Sales Admin' && $user_data->role_id == 5) {
                $tourViewQuery->whereIn('users.designation', ['AGM']);
            } elseif ($user_data->designation == 'GM') {
                $tourViewQuery->whereIn('users.designation', ['AGM', 'GM']);
                $tourViewQuery->where('users.dept_id', $user_data->dept_id);
            } elseif ($user_data->role_id == 9) { 
                $tourViewQuery->where('users.dept_id', $user_data->dept_id);
            } elseif ($user_data->designation == 'SM' || $user_data->designation == 'AGM' || $user_data->designation == 'ASM' || $user_data->designation == 'DSM' || $user_data->designation == 'ADSM' || $user_data->designation == 'RSM') {
                $tourViewQuery->where(function ($query) use ($user_data) {
                    $query->where('tour_entries.user_id', $user_data->id)
                        ->orwhere('tour_entries.hos', '=', $user_data->id)
                        ->orwhere('tour_entries.sm', '=', $user_data->id)
                        ->orwhere('tour_entries.asm', '=', $user_data->id)
                        ->orwhere('tour_entries.adsm', '=', $user_data->id)
                        ->orwhere('tour_entries.rsm', '=', $user_data->id);
                });
            } else {
                $tourViewQuery->where('tour_entries.user_id', $user_data->id);
            }
        }


        //$tourViewQuery->where('users.id',$user_data->id); 


        // $tourViewQuery->with['objectiveItem'] ;
        $tourViewQuery->orderBy('tour_entries.id', 'DESC');
        $tourViewQuery->limit(600);
        $data_return = $tourViewQuery->get();
        //return response()->json($data_return);
        $data_return  = TourPlanResource::collection($data_return);
        return $this->sendResponse($data_return, 'Tour Entries retrieved successfully');
    }

    public function  tour_entrie_month_list(Request $request)
    {
        $designation = $request->designation;
        $hq = $request->hq;
        $user_data = Auth::user();
        $emp_code = $request->emp_code;
        $request->month < 10 ? '0' . $request->month : $request->month;
        $month = $request->month < 10 ? '0' . $request->month : $request->month;
        $date =  $request->year . '-';

        $tourViewQuery = TourEntry::select(
            'tour_entries.*',
            'users.designation',
            'users.employee_id',
            'users.name'
        )
        ->orderBy('tour_entries.id', 'DESC');
        //$tourViewQuery->join('tour_users', 'tour_users.user_id','tour_entries.user_id');
        $tourViewQuery->join('users', 'users.id', 'tour_entries.user_id');
        // if($designation){
        //     $tourViewQuery->where('users.designation',$designation);
        // }
        if ($emp_code) {
            $tourViewQuery->where('users.employee_id', $emp_code);
        }
        if ($date) {
            $tourViewQuery->where('tour_entries.date', 'like',  $date . '%');
        }
        // if($division_id){
        //     $tourViewQuery->where('users.division_id', $division_id);                    
        // }
        // if ($request->start_date!='' AND $request->end_date!='') {
        //     $start_date = date('Y-m-d',strtotime($request->start_date));
        //     $end_date = date('Y-m-d',strtotime($request->end_date)); 
        //     $tourViewQuery->whereBetween('tour_entries.date', array($start_date, $end_date));
        // }  
        if ($request->hq) {
            $tourViewQuery->where('tour_entries.user_id', $request->hq);
        }
        // if($user_data->designation== 'Director' || $user_data->designation== 'GM'){
        //     $tourViewQuery->whereIn('users.designation', ['AGM','GM']);
        // }elseif($user_data->designation=='SM' || $user_data->designation=='AGM' ){
        //     $tourViewQuery->where('tour_entries.sm',$user_data->id);

        // }else{
        //     $tourViewQuery->where('users.dept_id', $user_data->dept_id);
        // }
        $tourViewQuery->limit(600);
        $data_return = $tourViewQuery->get();

        $data_return = TourPlanMonthlyResource::collection($data_return);

        return $this->sendResponse($data_return, ' Tour Entries retrieved successfully');
    }

    public function index(Request $request)
    {
        $designation = $request->designation;
        $hq = $request->hq;
        $user_data = Auth::user();
        //return $this->sendResponse($user_data, 'Tour Entries retrieved successfully');
        $emp_code = $request->emp_code;
        $division_id = $request->division_id;
        $tourView = TourEntry::orderBy('id', 'DESC');
        if ($designation && $user_data->role_id <= 5) {
            $users = collect(DB::table('users')
                ->where('designation', $designation)
                ->where(function ($query) use ($emp_code, $division_id) {
                    if ($emp_code) {
                        $query->where('employee_id', $emp_code);
                    }
                    if ($division_id) {
                        $query->where('division_id', $division_id);
                    }
                })
                // ->where(function($query)use($division_id){
                //     if($division_id){
                //          $query->where('division_id',$division_id);                    
                //     }
                // })             
                ->select('id')
                ->where('tour_permission', 1)
                ->get())->pluck('id')
                ->toArray();

            $tourView->where(function ($query) use ($users, $hq) {
                if ($hq) {
                    $query->where('user_id', $hq);
                } else {
                    $query->whereIn('user_id', $users);
                }
            });

            if ($request->start_date != '' and $request->end_date != '') {
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date = date('Y-m-d', strtotime($request->end_date));
                // echo $fdate.$tdate;die();
                $tourView->whereBetween('date', array($start_date, $end_date));
            }

            $tourView->limit(600);
            $tourResult = $tourView->get();
            $data_return  = TourPlanResource::collection($tourResult);

            return $this->sendResponse($data_return, ' Tour Entries retrieved successfully');
        }

        if ($user_data->role_id == 1 || $user_data->role_id == 2 || $user_data->role_id == 3 || $user_data->role_id == 4 || $user_data->role_id == 5 || $user_data->role_id == 8 || $user_data->role_id == 9) {

            if ($request->hq) {

                $tourView->where('user_id', '=', $request->hq);
            } else {

                if ($user_data->id == 1027 || $user_data->id == 227) {

                    $tourView->whereIN(
                        'user_id',
                        collect(DB::table('users')
                            ->select('id')
                            ->where('iactive', 0)
                            ->whereIn('designation', ['AGM', 'GM'])
                            ->where('dmd_tour', '=', 1)
                            ->where(function ($query) use ($emp_code) {
                                if ($emp_code) {
                                    $query->where('employee_id', $emp_code);
                                }
                            })
                            ->where(function ($query) use ($division_id) {
                                if ($division_id) {
                                    $query->where('division_id', $division_id);
                                }
                            })
                            ->orderBy('id')->get())
                            ->pluck('id')
                            ->toArray()
                    );
                } elseif ($user_data->role_id == 5) { //note: this condiotion is for only hod 

                    // $tourView->whereIN('user_id', 
                    // collect(DB::table('users')
                    // ->select('id')
                    // // ->where('dept_id','=', $user_data->dept_id)
                    // ->orderBy('id')->get())
                    // ->pluck('id')     
                    // ->toArray()         
                    // );

                } else { ////note: this condiotion is for other user's
                    $tourView->whereIN(
                        'user_id',
                        collect(DB::table('users')
                            ->select('id')
                            ->where('dept_id', '=', $user_data->dept_id)
                            ->where(function ($query) use ($emp_code) {
                                if ($emp_code) {
                                    $query->where('employee_id', $emp_code);
                                }
                            })
                            ->orderBy('id')->get())
                            ->pluck('id')
                            ->toArray()
                    );
                }
            }
        } else if ($user_data->designation == 'SM' || $user_data->designation == 'AGM' || $user_data->designation == 'ASM' || $user_data->designation == 'DSM' || $user_data->designation == 'ADSM' || $user_data->designation == 'RSM') {

            if ($request->hq) {
                $tourView->where('user_id', $request->hq);
                $user = $request->hq;
                $tourView->where(function ($query) use ($user) {
                    $user_data = Auth::user();
                    $query->where('user_id', '=', $user)
                        ->orwhere('hos', '=', $user_data->id)
                        ->orwhere('sm', '=', $user_data->id)
                        ->orwhere('asm', '=', $user_data->id)
                        ->orwhere('adsm', '=', $user_data->id)
                        ->orwhere('dsm', '=', $user_data->id)
                        ->orwhere('rsm', '=', $user_data->id);
                });
            } else {
                $tourView->where(function ($query) {
                    $user_data = Auth::user();
                    $query->where('user_id', $user_data->id)
                        ->orwhere('hos', '=', $user_data->id)
                        ->orwhere('sm', '=', $user_data->id)
                        ->orwhere('asm', '=', $user_data->id)
                        ->orwhere('adsm', '=', $user_data->id)
                        ->orwhere('rsm', '=', $user_data->id);
                });
            }
        } else {

            if ($request->hq) {
                $tourView->where('user_id', '=', $request->hq);
            } else {
                $tourView->where('user_id', '=', $user_data->id);
            }
        }

        // select 

        $users = collect(DB::table('users')
            ->where(function ($query) use ($emp_code, $division_id) {
                if ($emp_code) {
                    $query->where('employee_id', $emp_code);
                }
                if ($division_id) {
                    $query->where('division_id', $division_id);
                }
            })
            ->select('id')
            ->where('tour_permission', 1)
            ->get())->pluck('id')
            ->toArray();

        $tourView->where(function ($query) use ($users, $hq) {
            if ($hq) {
                $query->where('user_id', $hq);
            } else {
                $query->whereIn('user_id', $users);
            }
        });

        if ($request->start_date != '' and $request->end_date != '') {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d', strtotime($request->end_date));
            $tourView->whereBetween('date', array($start_date, $end_date));
        } else if ($request->start_date != '') {
            $start_date = date('Y-m-d', strtotime($request->start_date));
            $end_date = date('Y-m-d');
            $tourView->whereBetween('date', array($start_date, $end_date));
        } else {
            $tourView->where('date', date('Y-m-d'));
        }
        $tourView->limit(600);
        $tourResult = $tourView->get();

        $data_return  =   TourPlanResource::collection($tourResult);

        return $this->sendResponse($data_return, 'Tour Entries retrieved successfully');
    }

    /**
     * Store a newly created TourEntry in storage.
     * POST /tourEntries
     *
     * @param CreateTourEntryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTourEntryAPIRequest $request)
    {
        $user_data = Auth::user();

        $user_info  = TourUser::where('user_id', $user_data->id)->first();
        $request['user_id'] = $user_data->id;
        $request['dept_id'] = $user_data->dept_id;
        $request['hos'] = $user_info->head_of_sales;
        $request['sm'] = $user_info->sm;
        $request['asm'] = $user_info->asm;
        $request['dsm'] = $user_info->dsm;
        $request['adsm'] = $user_info->adsm;
        $request['rsm'] = $user_info->rsm;

        if (!$request['date']) {
            $request['date'] = date("Y-m-d");
        } else {
            $request['date'] =  date("Y-m-d", strtotime($request['date']));
        }

        if (!$request['route_name']) {
            $request['route_name'] = ' ';
        }       

        $input = $request->all();
        $tourEntry = $this->tourEntryRepository->create($input);
        if($request['territory_details']){
            foreach ($request['territory_details'] as $key => $value) {
                $territory_details                  = new TourTerritoryDetails(); 
                $territory_details->tour_id         = $tourEntry->id;
                $territory_details->emp_id          = $value['emp_id'];
                $territory_details->territory_id    = $value['id'];
                $territory_details->name            = $value['name'];
                $territory_details->user_id         = $value['user_id'];
                $territory_details->save();
            }
        }

        if($request['point_details']){
            foreach ($request['point_details'] as $key => $value) {
                $territory_check = TourTerritoryDetails::where('tour_id' , $tourEntry->id)
                ->where('territory_id',$value['territory_id'])->first();
                if(!empty($territory_check)){
                    $point_details                          = new TourPointDetails(); 
                    $point_details->tour_id                 = $tourEntry->id;
                    $point_details->business_type_id        = $value['business_type_id'];
                    $point_details->global_company_id       = $value['global_company_id'];
                    $point_details->is_depot                = $value['is_depot'];
                    $point_details->point_division          = $value['point_division'];
                    $point_details->point_id                = $value['point_id'];
                    $point_details->point_name              = $value['point_name'];
                    $point_details->point_status            = $value['point_status'];
                    $point_details->sap_code                = $value['sap_code'];
                    $point_details->territory_id            = $value['territory_id'];
                    $point_details->territory_name          = $value['territory_name'];
                    $point_details->save();                    
                }
            }
        }        

        if($request['route_details']){
            foreach ($request['route_details'] as $key => $value) {
                $point_check = TourPointDetails::where('tour_id' , $tourEntry->id)
                ->where('point_id',$value['point_id'])
                ->first();
                if(!empty($point_check)){
                    $TourRouteD = new TourRouteDetails(); 
                    $TourRouteD->tour_id    = $tourEntry->id;
                    $TourRouteD->route_id   = $value['route_id'];
                    $TourRouteD->route_name = $value['route_name'];
                    $TourRouteD->point_id   = $value['point_id'];
                    $TourRouteD->save();
                }                
            }
        }

        if($request['work_with_details']){
            foreach ($request['work_with_details'] as $key => $value) {
                $point_check = TourPointDetails::where('tour_id' , $tourEntry->id)
                ->where('point_id',$value['point_id'])
                ->first();    
                if(!empty($point_check)){            
                    $workWithDetails                    = new TourFoDetails(); 
                    $workWithDetails->tour_id           = $tourEntry->id;
                    $workWithDetails->business_type_id  = $value['business_type_id'];
                    $workWithDetails->designation       = $value['designation'];
                    $workWithDetails->display_name      = $value['display_name'];
                    $workWithDetails->division_id       = $value['division_id'];
                    $workWithDetails->email             = $value['email'];
                    $workWithDetails->employee_id       = $value['employee_id'];
                    $workWithDetails->point_id          = $value['point_id'];
                    $workWithDetails->territory_id      = $value['territory_id'];
                    $workWithDetails->user_id           = $value['user_id'];
                    $workWithDetails->user_type         = $value['user_type'];
                    $workWithDetails->user_type_id      = $value['user_type_id'];
                    $workWithDetails->save();
                }
            }
        }

        
        // if( $tourEntry){
        //     if($request['objectiveslist']){
        //         $objectiveslist = $request['objectiveslist'] ; 
        //         foreach ($objectiveslist as $key => $value) {
        //             # code...
        //             if($value['objective']){
        //                 $dataInput = array(
        //                     'tour_entrie_id' =>$tourEntry->id ,
        //                     'objective' => $value['objective'] );
        //                 TourEntrieObjective::create($dataInput) ;
        //             }

        //         }

        //     }
        // }
        return $this->sendResponse($tourEntry->toArray(), 'Tour Entry saved successfully');
    }

    /**
     * Display the specified TourEntry.
     * GET|HEAD /tourEntries/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TourEntry $tourEntry */
        $tourEntry = $this->tourEntryRepository->find($id);

        if (empty($tourEntry)) {
            return $this->sendError('Tour Entry not found');
        }
        $data_return = new TourPlanResource($tourEntry);

        return $this->sendResponse($data_return, 'Tour Entry retrieved successfully');
    }

    /**
     * Update the specified TourEntry in storage.
     * PUT/PATCH /tourEntries/{id}
     *
     * @param int $id
     * @param UpdateTourEntryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTourEntryAPIRequest $request)
    {
        $input = $request->all();
        
       
        /** @var TourEntry $tourEntry */
        $tourEntry = $this->tourEntryRepository->find($id);

        if (empty($tourEntry)) {
            return $this->sendError('Tour Entry not found');
        }

        $tourEntry = $this->tourEntryRepository->update($input, $id);

        // if($request['route_details']){
        //     TourRouteDetails::where('tour_id' , $tourEntry->id)->delete();
        //     foreach ($request['route_details'] as $key => $value) {
        //         $TourRouteD = new TourRouteDetails();
        //         $TourRouteD->tour_id = $tourEntry->id;
        //         $TourRouteD->route_id = $value['route_id'];
        //         $TourRouteD->route_name = $value['route_name'];
        //         $TourRouteD->save();
        //     }
        // }
        if ($tourEntry) {
            if ($request['objectiveItem']) {
                $objectiveItem = $request['objectiveItem'];
                foreach ($objectiveItem as $key => $value) {
                    # code...
                    if ($value['objective']) {
                        if (isset($value['id'])) {
                            TourEntrieObjective::where("id", $value['id'])->update(["objective" => $value['objective']]);
                        } else {
                            $dataInput = array(
                                'tour_entrie_id' => $tourEntry->id,
                                'objective' => $value['objective']
                            );
                            TourEntrieObjective::create($dataInput);
                        }
                    }
                }
            }
        }

        if($request['territory_details']){
            TourTerritoryDetails::where('tour_id' , $tourEntry->id)->delete();
            foreach ($request['territory_details'] as $key => $value) {
                $territory_details                  = new TourTerritoryDetails(); 
                $territory_details->tour_id         = $tourEntry->id;
                $territory_details->emp_id          = $value['emp_id'];
                $territory_details->territory_id    = $value['id'];
                $territory_details->name            = $value['name'];
                $territory_details->user_id         = $value['user_id'];
                $territory_details->save();
            }
        }
        
        if($request['point_details']){
            
            TourPointDetails::where('tour_id' , $tourEntry->id)->delete();
            foreach ($request['point_details'] as $key => $value) {
                $territory_check = TourTerritoryDetails::where('tour_id' , $tourEntry->id)
                ->where('territory_id',$value['territory_id'])->first();
                
                if(!empty($territory_check)){
                    $point_details                          = new TourPointDetails(); 
                    $point_details->tour_id                 = $tourEntry->id;
                    $point_details->business_type_id        = $value['business_type_id'];
                    $point_details->global_company_id       = $value['global_company_id'];
                    $point_details->is_depot                = $value['is_depot'];
                    $point_details->point_division          = $value['point_division'];
                    $point_details->point_id                = $value['point_id'];
                    $point_details->point_name              = $value['point_name'];
                    $point_details->point_status            = $value['point_status'];
                    $point_details->sap_code                = $value['sap_code'];
                    $point_details->territory_id            = $value['territory_id'];
                    $point_details->territory_name          = $value['territory_name'];
                    $point_details->save();      
                    
                    
                }
            }
        }        
    
        if($request['route_details']){
            TourRouteDetails::where('tour_id' , $tourEntry->id)->delete();
            foreach ($request['route_details'] as $key => $value) {
                $point_check = TourPointDetails::where('tour_id' , $tourEntry->id)
                ->where('point_id',$value['point_id'])
                ->first();
                if(!empty($point_check)){
                    $TourRouteD = new TourRouteDetails(); 
                    $TourRouteD->tour_id    = $tourEntry->id;
                    $TourRouteD->route_id   = $value['route_id'];
                    $TourRouteD->route_name = $value['route_name'];
                    $TourRouteD->point_id   = $value['point_id'];
                    $TourRouteD->save();
                }                
            }
        }
        
        if($request['work_with_details']){
            TourFoDetails::where('tour_id' , $tourEntry->id)->delete(); 
            foreach ($request['work_with_details'] as $key => $value) {
                $point_check = TourPointDetails::where('tour_id' , $tourEntry->id)
                ->where('point_id',$value['point_id'])
                ->first();    
                if(!empty($point_check)){           
                    $workWithDetails                    = new TourFoDetails(); 
                    $workWithDetails->tour_id           = $tourEntry->id;
                    $workWithDetails->business_type_id  = $value['business_type_id'];
                    $workWithDetails->designation       = $value['designation'];
                    $workWithDetails->display_name      = $value['display_name'];
                    $workWithDetails->division_id       = $value['division_id'];
                    $workWithDetails->email             = $value['email'];
                    $workWithDetails->employee_id       = $value['employee_id'];
                    $workWithDetails->point_id          = $value['point_id'];
                    $workWithDetails->territory_id      = $value['territory_id'];
                    $workWithDetails->user_id           = $value['user_id'];
                    $workWithDetails->user_type         = $value['user_type'];
                    $workWithDetails->user_type_id      = $value['user_type_id'];
                    $workWithDetails->save();
                }
            }
        }     

        return $this->sendResponse($tourEntry->toArray(), 'TourEntry updated successfully');
    }

    /**
     * Remove the specified TourEntry from storage.
     * DELETE /tourEntries/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TourEntry $tourEntry */
        $tourEntry = $this->tourEntryRepository->find($id);
        TourTerritoryDetails::where('tour_id' , $id)->delete();
        TourPointDetails::where('tour_id' , $id)->delete();
        TourRouteDetails::where('tour_id' , $id)->delete();
        TourFoDetails::where('tour_id' , $id)->delete();

        if (empty($tourEntry)) {
            return $this->sendError('Tour Entry not found');
        }

        $tourEntry->delete();

        return $this->sendSuccess('Tour Entry deleted successfully');
    }

    /**
     * TOUR BUSINESS TYPE LIST
     */
    public function tour_business_types()
    {
        $tourBusinessTypeList = TourPlanBusinessType::all();
        $data_return  =   TourBusinessTypeResource::collection($tourBusinessTypeList);
        return $this->sendResponse($data_return, 'Tour business list');
    }
}
