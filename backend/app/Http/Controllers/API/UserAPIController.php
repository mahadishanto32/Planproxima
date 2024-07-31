<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Models\QuickLink;
use App\Models\Department_transfer;
use App\Models\TourUserProfile;
use App\Models\User;
use App\Models\KPI;
use App\Models\MosFeadback;
use App\Models\MOS;
use App\Models\DailySchedule;
use App\Models\TourUser;
use App\Models\DepartmentAssign;
use Adldap\Laravel\Facades\Adldap;
use App\Repositories\UserRepository;
use App\Http\Resources\UserTourResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\TourEntry;
use App\Models\KRA;
use App\Models\MosData;
use Illuminate\Support\Facades\Hash;
use Response;
use Auth, DB;
use Image;
use App\Models\UaerShare;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */
class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     * GET|USER /users
     *
     * @param Request $request
     * @return Response
     */

    public function index(Request $request)
    {
        $user_data = Auth::user();
        $query =  User::select(
            "uaer_shares.dept_id as share_dept_id",
            "users.id",
            "users.name",
            "users.email",
            "users.employee_id",
            "users.employee_status",
            "users.hos",
            "users.phone",
            "users.designation",
            "users.dept_id",
            "users.role_id",
            "users.wing_id",
            "users.sap_code",
            "users.ad_mail",
            "users.status",
            "users.is_factory",
            "users.tour_plan",
            "users.tour_program",
            "users.tour_permission",
            "users.tour_super",
            "users.created_at"
        );
        $query->leftJoin('uaer_shares', 'uaer_shares.user_id', 'users.id');


        if ($request->search) {
            $query->where(function ($query) use ($request) {
                $query->where('users.employee_id', 'like', '%' . $request->search . '%');
                $query->orWhere('users.name', 'like', '%' . $request->search . '%');
                $query->orWhere('users.email', 'like', '%' . $request->search . '%');
            });
        } else {
            $query->where(function ($query) use ($request) {
                $query->where('users.employee_id', 'like', '%' . $request->search_key . '%');
                $query->orWhere('users.name', 'like', '%' . $request->search_key . '%');
                $query->orWhere('users.email', 'like', '%' . $request->search_key . '%');
            });
            
            if ($request->role_id) {
                $query->where('users.role_id', $request->role_id);
                // $request['role_id'] = $request->role_id; 
            }

            if ($request->dept_id) {
                //$request['dept_id'] = $request->dept_id; 
                //$query->where('users.dept_id', $request->dept_id);
                if ($request['wing_id']) {
                    // $request['wing_id'] = $request['wing_id'];
                    $query->where('users.wing_id', $request->wing_id);
                }
                if ($user_data->role_id == 6) {
                    //$request['wing_id'] = [$request['wing_id']];//$user_data->wing_id;
                    $query->where('users.wing_id', $request->wing_id);
                }
                // if($user_data->role_id == 5) {
                $query->where(function ($query) use ($request) {
                    $query->where('users.dept_id', '=', $request->dept_id);
                    $query->orWhere('uaer_shares.dept_id', '=', $request->dept_id);
                });
                // }



            } else {
                //exit();
                if ($user_data->role_id == 5) {
                    // $request['dept_id'] = $user_data->dept_id;
                    // $query->where('users.dept_id',$user_data->dept_id);

                    $query->where(function ($query) use ($user_data) {
                        $query->where('users.dept_id', '=', $user_data->dept_id);
                        $query->orWhere('uaer_shares.dept_id', '=', $user_data->dept_id);
                    });
                } elseif ($user_data->role_id == 6) {
                    //$request['wing_id'] = $user_data->wing_id;
                    $query->where('users.wing_id', $user_data->wing_idd);
                } elseif ($user_data->role_id == 7) {
                    //$request['id'] = $user_data->id;
                    $query->where('id', $user_data->id);
                }
            }
        }

        if (isset($request->status)) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 1);
        }

        // $query->where('status', $request->status);
        $query->where('role_id', '>', 0);
        $query->orderBy('users.role_id', 'ASC');
        $query->groupBy('users.id');
        $query->orderBy('users.employee_id', 'ASC');

        // $request['status'] = 1;
        // $users = $this->userRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );
        $query->limit($request->limit ? $request->limit : 200);
        $users = $query->get();

        $users = UserResource::collection($users);

        return $this->sendResponse($users, $request->search . 'Users retrieved successfully');
    }

    public function users_list(Request $request)
    {
        $user_data = Auth::user();
        $usersSql = User::select('id', 'name',  DB::raw('CONCAT(name , "  " , IFNULL( employee_id , "" ) ) AS text'),  'email', 'ad_mail');
        if ($request->dept_id) {
            $usersSql->where('dept_id', $request->dept_id);
        } else {
            if ($user_data->role_id == 5) {
                $usersSql->where('dept_id', $user_data->dept_id);
            } elseif ($user_data->role_id == 6) {
                //$request['wing_id'] = $user_data->wing_id;
                $usersSql->where('wing_id', $user_data->wing_id);
            } elseif ($user_data->role_id == 7) {
                $usersSql->where('id', $user_data->id);
                //$request['id'] = $user_data->id;
            };
        }

        $usersSql->limit(1000);
        $users = $usersSql->get();
        // if($request->role_id){
        //     $request['role_id'] = $request->role_id; 
        // }

        // if($request->dept_id){
        //     $request['dept_id'] = $request->dept_id; 
        //     if ($request['wing_id']) {
        //         $request['wing_id'] = $request['wing_id'];
        //     }
        //     if($user_data->role_id == 6) {
        //         $request['wing_id'] = [$request['wing_id']];//$user_data->wing_id;
        //     }
        // }else{
        //     if ($user_data->role_id == 5) {
        //         $request['dept_id'] = $user_data->dept_id;
        //     } elseif ($user_data->role_id == 6) {
        //         $request['wing_id'] = $user_data->wing_id;
        //     } elseif ($user_data->role_id == 7) {
        //         $request['id'] = $user_data->id;
        //     }
        // }

        // $request['status'] = 1;
        // $users = $this->userRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

        // $users = UserResource::collection($users);

        return $this->sendResponse($users, 'Users retrieved successfully');
    }
    public function get_wings_users($wing_id)
    {
        $user = User::select('id', 'name', 'dept_id', 'wing_id', 'email', 'employee_id', 'ad_mail')
            ->where('status', 1)
            ->where('wing_id', $wing_id)
            ->where('role_id', 7)
            ->get();
        return $this->sendResponse($user, 'Users retrieved successfully');
    }
    public function get_dept_users($dept_id)
    {
        $user = User::select('id', 'name', 'dept_id', 'wing_id', 'email', 'employee_id', 'ad_mail')
            ->where('status', 1)
            ->where('dept_id', $dept_id)
            ->where('role_id', 7)
            ->get();
        return $this->sendResponse($user, 'Users retrieved successfully');
    }
    public function ccUsers()
    {
        $user = User::select('id', DB::raw("CONCAT(ad_mail,' (' , name , ')') AS name"),  'email', 'employee_id', 'ad_mail')
            ->where('status', 1)
            ->where('ad_mail', '!=', "")
            ->whereIn('role_id', array(1, 2, 3, 4, 5, 6, 7))
            ->get();
        return $this->sendResponse($user, 'Users retrieved successfully');
    }

    public function AssignccUsers($id)
    {
        $user = User::select('users.id', DB::raw("CONCAT(users.ad_mail,' (' , users.name , ')') AS name"),  'users.email', 'users.employee_id', 'users.ad_mail')
            ->join('department_c_cmails', 'department_c_cmails.user_id', '=', 'users.id')
            ->where('department_c_cmails.dept_id', $id)
            ->whereNull('department_c_cmails.deleted_at')
            ->get();
        return $this->sendResponse($user, 'Users retrieved successfully');
    }


    public function profile_thamnail(Request $request)
    {
        $user_data = Auth::user();
        $img = "thumbnailphoto/" . Auth::user()->pro_image;

        return $this->sendResponse($img, 'Users retrieved successfully');
    }
    public function autenticate_user(Request $request)
    {
        $user_data = Auth::user();
        return $this->sendResponse($user_data, 'Users retrieved successfully');
    }

    public function update_admail(Request $request)
    {
        //Note: Ldap Connection start
        $ldaphost = "SSG-DC01.ssgbd.com";
        $ldapport = 389;
        $userkey = "abcd";
        $credentials = $request->only('email', 'password');

        $userpass = "BW4iQqRiGJFSBPes";
        $exclusivename = "app@ssgbd.com";

        $ds = ldap_connect($ldaphost, $ldapport) or die("Could not connect to $ldaphost");

        ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

        try {
            $bind = ldap_bind($ds, $exclusivename, $userpass);
        } catch (\Exception $e) {
            return $this->sendResponse([], 'Please check your credentials');
        }

        $base_dn = 'dc=ssgbd,dc=com';
        $filter = "(&(objectClass=user)(objectCategory=person)(userPrincipalName=" . ldap_escape($request->admail, null, LDAP_ESCAPE_FILTER) . "))";

        $sr = ldap_search($ds, $base_dn, $filter, array("cn", "dn", "memberof", "mail", "telephonenumber", "othertelephone", "mobile", "ipphone", "department", "title", 'thumbnailphoto', 'enabled'));

        $info = ldap_get_entries($ds, $sr);
        if (isset($info[0]['useraccountcontrol'][0]) != 514) {
            $data = User::where('email', $request->email)->first();
            $data->ad_mail = $request->admail;
            $data->employee_id = $request->employee_id;
            $data->phone = $request->phone_number;
            if (!$data->pro_image) {
                $png_url = $data->ad_mail . ".jpeg";
                $path = public_path() . '/thumbnailphoto/' . $png_url;

                if (isset($info[0]['thumbnailphoto'][0])) {
                    Image::make($info[0]['thumbnailphoto'][0])->save($path);
                } else {
                    $png_url = '';
                }

                $data->pro_image = $png_url;
            }
            $data->save();


            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Update successfully',
                ]);
        } else {
            return response()
                ->json([
                    'status' => 1,
                    'message' => 'invalid Email Address',
                ]);
        }
        return $this->sendResponse(['ok'], 'Users retrieved successfully');

        // return $this->sendResponse($info, 'Users retrieved successfully');

        // $checkAccess = User::where('email', $email)
        // ->first();



        // $user_data = Auth::user();
        return $this->sendResponse($request->all(), 'Users retrieved successfully');
    }

    /*public function tour_plan_users(Request $request)
    {
        $users = DB::table('users')
            // ->where('iactive', 0)
            ->where('tour_permission', '=', 1)
            ->orderBy('users.id')->get();
        // $users = DB::table('users')
        // ->where('iactive', 0)
        // ->where(function($query)
        // {
        //     $query->where('tour_permission','=',1)
        //     ->orWhere('designation','=','GM')
        //     ->orWhere('designation','=','SM')
        //     ->orWhere('designation','=','ADSM')
        //     ->orWhere('designation','=','ASM');
        // })
        // ->orderBy('id')->get();


        // $user_data = Auth::user();

        // //print_r($user_data );

        // $supervisor = DB::table('users')
        // ->where('iactive',0);

        // if($user_data->designation=='SM' || $user_data->designation=='AGM'){

        //     $supervisorResult = $supervisor->where('sm',$user_data->id)->orderBy('id')->get();
        // }
        // elseif($user_data->designation=='ASM'){

        //     $supervisorResult = $supervisor->where('asm',$user_data->id)->orderBy('id')->get();
        // }
        // elseif($user_data->designation=='DSM'){

        //     $supervisorResult = $supervisor->where('dsm',$user_data->id)->orderBy('id')->get();
        // } 
        // elseif($user_data->designation=='ADSM'){

        //     $supervisorResult = $supervisor->where('adsm',$user_data->id)->orderBy('id')->get();
        // }
        // elseif($user_data->designation=='RSM'){

        //     $supervisorResult = $supervisor->where('rsm',$user_data->id)->orderBy('id')->get();
        // }

        // else{
        //     if($user_data->usertype=='A')
        //     {
        //         $supervisorResult = $supervisor->where(function($query)
        //         {
        //             $query->where('email','>',30)
        //             ->orWhere('email','=','sysadmin')
        //             ->orWhere('email','=','ibrahim')
        //             ->orWhere('email','=','sales');
        //         })
        //         ->where('tour_program','=', 1)
        //         ->orderBy('id')->get();

        //     }
        //     elseif($user_data->dept_id>0)
        //     {
        //         $supervisorResult = $supervisor->where('rsm',$user_data->dept_id)->orderBy('id')->get();
        //     }  else{

        //         $supervisorResult = $supervisor->where('vuser',$user_data->email)->orderBy('id')->get();
        //     }
        // }

        //print_r( $supervisorResult);


        //$users = User::all();

        //  //$request['role_id'] = 10 ; 
        //  $users = $this->userRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // ); 

        // $users  =   UserTourResource::collection($users);

        return $this->sendResponse($users, 'Users retrieved successfully');
    }*/

    /**
     * NEW FUNCTION
     * @param Request $request
     * @return mixed
     */
    public function tour_plan_users(Request $request)
    {
        $userData = Auth::user();
        $designation = $request->designation;
        $sattus = $request->actv_status;

        $users = DB::table('users')
            ->select('users.*', 'tour_user_profiles.base_station_address')
            ->leftJoin('tour_user_profiles', 'tour_user_profiles.user_id', '=', 'users.id')
            ->where('tour_permission', '=', 1)
            ->where(function ($query) use ($sattus) {
                if ($sattus == 0) {
                    $query->where('status', 1);
                } else {
                    $query->where('status', 0);
                }
            })
            ->where(function ($query) use ($designation) {
                if ($designation) {
                    $query->where('designation', $designation);
                }
            })
            ->orderBy('users.id')->get();

        return $this->sendResponse($users, 'Users retrieved successfully');
    }

    /**
     * DATEWISE NOT TOUR ENTRY USER LIST
     */
    public function tour_plan_not_submit_users(Request $request)
    {
        $designation = $request->designation;
        $employee = $request->hq;
        $userData = Auth::user();

        $date = $request->date ? $request->date : date('Y-m-d');

        $users = DB::table('users')
            ->select('users.*', 'tour_user_profiles.base_station_address')
            ->leftJoin('tour_user_profiles', 'tour_user_profiles.user_id', '=', 'users.id')
            ->where(function ($query) use ($userData, $designation, $employee) {
                if (($userData->role_id == 5) && (!$designation)) {
                    $query->where('designation', 'AGM');
                    $query->where('dmd_tour', '=', 1);
                } elseif ($employee) {
                    $query->where('users.id', $employee);
                } else {
                    $query->where('designation', $designation);
                }
            })
            // ->where(function($query)use($designation,$employee){
            //     if($employee){
            //        
            //     }else{
            //         $query->where('designation',$designation); 
            //     }
            // })
            ->where('status', 1)
            ->where('tour_permission', '=', 1)->orderBy('users.id')
            ->whereNotIn('users.id', TourEntry::select('user_id')->where('date', $date)->get()->toArray())->get();

        return $this->sendResponse($users, 'Users retrieved successfully');
    }




    public function userWing(Request $request)
    {
        //$users = User::all();
        $user_data = Auth::user();

        if ($request->dept_id) {
            $request['dept_id'] = $request->dept_id;
        } else if ($user_data->role_id == 5 || $user_data->role_id == 6) {
            $request['dept_id'] = $user_data->dept_id;
        }
        //$request['role_id'] =  6 ;
        $users = $this->userRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        $users = UserResource::collection($users);

        //return $this->sendResponse($supervisorResult, 'Users retrieved successfully');
        return $this->sendResponse($users, 'Users retrieved successfully');
    }
    public function wing_change(Request $request)
    {
        //$users = User::all();
        // $user_data = User::where('id', $request->id); // Auth::user();

        //DB::table('tour_user_profiles')
        User::where('id',  $request->id)
            ->update(['wing_id' => $request->wing_id]);

        $data = KRA::where('user_id', $request->id)
            ->update(['wing_id' => $request->wing_id]);

        // if($request->dept_id){
        //     $request['dept_id'] = $request->dept_id ;
        // }else if($user_data->role_id == 5 || $user_data->role_id == 6 ){
        //     $request['dept_id'] = $user_data->dept_id ;
        // }
        // //$request['role_id'] =  6 ;
        // $users = $this->userRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

        // $users = UserResource::collection($users);

        // //return $this->sendResponse($supervisorResult, 'Users retrieved successfully');
        return $this->sendResponse($data, 'Users wing change successfully');
    }

    /**
     * Store a newly created User in storage.
     * POST /Users
     *
     * @param CreateUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->except(['password']);
        $input['password'] = bcrypt($request->password);

        $user = $this->userRepository->create($input);

        return $this->sendResponse($user->toArray(), 'User saved successfully');
    }

    public function add_tour_user(CreateUserAPIRequest $request)
    {
        $user_data = Auth::user();
        $input = $request->except(['password']);
        $input['password'] = bcrypt($request->password);
        $input['dept_id'] = $user_data->dept_id;

        $user = $this->userRepository->create($input);
        // $user = $user->toArray() ;
        TourUser::create([
            'user_id' => $user->id,
            'business_type' => $request->la,
            'head_of_sales' => $request->head_of_sales ? $request->head_of_sales : 0,
            //'division_head' => $request->division_head ? $request->division_head : 0,
            'base_station_address' => $request->base_station_address ? $request->base_station_address : 0,
            'sm' => $request->sm ? $request->sm : 0,
            'dsm' => $request->dsm ? $request->dsm : 0,
            'asm' => $request->asm ? $request->asm : 0,
            'adsm' => $request->adsm ? $request->adsm : 0,
            'rsm' => $request->rsm ? $request->rsm : 0

        ]);


        //SAVE TOUR USER PROFILE
        TourUserProfile::create([
            'user_id' => $user->id,
            'base_station_address' => $request->base_station_address
        ]);

        return $this->sendResponse($user, 'User saved successfully');
    }

    /**
     * Display the specified User.
     * GET|USER /users/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $user */
        $user = DB::table('users')
            ->select('users.*', 'tour_user_profiles.base_station_address')
            ->leftJoin('tour_user_profiles', 'tour_user_profiles.user_id', '=', 'users.id')
            ->where('users.id', $id)
            ->first();

        if (empty($user)) {
            return $this->sendError('User not found');
        }
        return $this->sendResponse($user, 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     *
     * @param int $id
     * @param UpdateUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserAPIRequest $request)
    {
        //GET ALL INPUT WITHOUT is_password_change INPUT VALUE
        $input = $request->except(['is_password_change']);

        //IF PASSWORD INPUT NOT EMPTY
        if (!empty($request->password)) {
            $input['password'] = bcrypt($request->password);
        }
        /** @var Head $head */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user = $this->userRepository->update($input, $id);

        //users

        if ($request->tour_permission) {
            if (TourUser::where('user_id', $id)->count() > 0) {
                //TourUser::update($dataInfo); 
            } else {
                $dataInfo = array('user_id' => $id);
                TourUser::create($dataInfo);
            }
        }
        if (TourUser::where('user_id', $id)->count() > 0) {
            $dataInfo =  array(
                'rsm' => $request->rsm,
                'adsm' => $request->adsm,
                'dsm' => $request->dsm,
                'asm' => $request->asm,
                'sm' => $request->sm,
                'division_id' => $request->division_id,
                'business_type' => $request->business_type,
                'head_of_sales' => $request->head_of_sales,
                'division_head' => $request->division_head,

            );
            TourUser::where('user_id', $id)->update($dataInfo);
        }

        //UPDATE TOUR USER PROFILE
        if ($request->base_station_address) {
            $tour_user_profiles = DB::table('tour_user_profiles')->where('user_id', $id)->get();

            if (isset($tour_user_profiles->tour_user_profiles)) {
                DB::table('tour_user_profiles')
                    ->where('user_id', $id)
                    ->update(['base_station_address' => $request->base_station_address]);
            } else {
                DB::table('tour_user_profiles')->insert([
                    'user_id' => $id,
                    'base_station_address' => $request->base_station_address
                ]);
            }
        }


        return $this->sendResponse($user->toArray(), 'User ddd updated successfully');
    }

    public function users_change(Request $request)
    {
        $user = User::find($request->id);

        $user->wing_id = $request->wing_id;

        $return = $user->save();

        return $this->sendResponse($return, 'User updated successfully');
    }

    public function profile_update($id, Request $request)
    {
        $user = User::find($id);
        $input = $request->all();

        //IF PASSWORD INPUT NOT EMPTY
        // if (!empty($request->current_password)) {
        //     if (Hash::check('passwordToCheck', $user->password)) {
        //         $input['password'] = bcrypt($request->password);
        //     } else {
        //         return $this->sendError('Current password not match!');
        //     }
        // }

        // $user = $this->userRepository->update($input, $id);
        $user->phone = $request->phone;
        if ($request->con_password) {
            if ($request->password != $request->con_password) {
                return $this->sendResponse($user, 'password and confirm password not match');
            }
            $user->password = bcrypt($request->con_password);
        }
        $user->ad_mail = $request->ad_mail;
        $user->employee_id = $request->employee_id;
        $user->save();

        return $this->sendResponse($user, 'User updated successfully');
    }

    /**
     * Remove the specified Head from storage.
     * DELETE /users/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var User $user */
        // $user = $this->userRepository->find($id);

        // if (empty($user)) {
        //     return $this->sendError('User not found');
        // }

        // $user->delete();

        // //TOUR USER PROFILE DELETE
        // \Illuminate\Support\Facades\DB::table('tour_user_profiles')->where('user_id', $id)->delete();

        $user  = User::find($id);
        $user->status = 0;
        $user->save();
        return $this->sendSuccess('User deleted successfully');
    }

    //SVE OR DELETE QUICK LINK
    public function saveOrDeleteQuickLink(Request $request)
    {
        //GET EXISTING DATA
        $savedQuickLink = QuickLink::where(array('user_id' => $request->user_id, 'route' => $request->route))->get();

        if ($savedQuickLink->count() == 0) {
            QuickLink::create([
                'title' => $request->title,
                'module' => $request->module,
                'route' => $request->route,
                'status' => 'active',
                'user_id' => $request->user_id
            ]);
        } else {
            $deleteLink = QuickLink::where(
                array(
                    'user_id' => $request->user_id,
                    'route' => $request->route,
                )
            )->delete();
        }

        //GET ALL DATA
        $getQuickLink = QuickLink::where(array('user_id' => $request->user_id))->get();

        return Response::json($getQuickLink);
    }

    /**
     * GET QUICK LINK LIST
     * @param Request $request
     * @return mixed
     */
    public function quickLinkList($userId)
    {
        $getQuickLink = QuickLink::where(array('user_id' => $userId))->get();

        return Response::json($getQuickLink);
    }

    public function dep_transfer(Request $request)
    {
        // DB::beginTransaction();
        // try {
        $Department_transfer = new Department_transfer;
        $Department_transfer->user_id = $request->user_id;
        $Department_transfer->current_dept = $request->current_dept;
        $Department_transfer->new_dept = $request->new_dept;
        $Department_transfer->kra_data = $request->kra_data ? 1 : 0;
        $Department_transfer->daily_data = $request->daily_data ? 1 : 0;
        $Department_transfer->tour_data = $request->tour_data ? 1 : 0;
        $Department_transfer->save();


        $user_info = User::where('id', $request->user_id)->first();
        if ($user_info->role_id == 5) {
            $DepartmentAssign = DepartmentAssign::where('user_id', $request->user_id)->first();
            $DepartmentAssign->dept_id = $request->new_dept;
            $DepartmentAssign->save();
        }
        $User = User::find($request->user_id);
        $User->dept_id = $request->new_dept;
        $User->save();

        if ($request->tour_data) {
            TourEntry::where('user_id', $request->user_id)
                ->update(['dept_id' => $request->new_dept]);
        }

        if ($request->daily_data) {
            DailySchedule::where('user_id', $request->user_id)
                ->update(['dept_id' => $request->new_dept]);
        }

        if ($request->kra_data) {
            KRA::where('user_id', $request->user_id)
                ->update(['dept_id' => $request->new_dept]);

            KPI::join('k_r_a_s', 'k_p_i_s.kra_id', 'k_r_a_s.id')
                ->where('k_r_a_s.user_id', $request->user_id)
                ->update(['k_p_i_s.dept_id' => $request->new_dept]);

            MOS::join('k_r_a_s', 'm_o_s.kra_id', 'k_r_a_s.id')
                ->where('k_r_a_s.user_id', $request->user_id)
                ->update(['m_o_s.dept_id' => $request->new_dept]);

            MosFeadback::where('user_id', $request->user_id)
                ->update(['dept_id' => $request->new_dept]);

            MosData::join('m_o_s', 'm_o_s.id', 'mos_datas.mos_id')
                ->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id')
                ->where('k_r_a_s.user_id', $request->user_id)
                ->update(['mos_datas.dept_id' => $request->new_dept]);
        }
        return $this->sendResponse($User, 'Department Changed successfully');
        // } catch (\Exception $e){
        //     DB::rollback();
        //     return $this->sendResponse($e, 'Department Changed successfully');
        // }    
    }
    public function dep_transfer_sync(Request $request)
    {
        ini_set('max_execution_time', 100000000);
        $usersQ = User::select('id', 'employee_id', 'dept_id','role_id');
        $usersQ->whereIn('role_id', [6, 7, 8, 9, 10]);
        $usersQ->whereIn('dept_id', [1,40, 41, 42]);
        $usersQ->where('status', 1);
        if ($request->employee_id) {
            $usersQ->where('employee_id', $request->employee_id);
        }
        $users = $usersQ->get();

     
        foreach ($users->toArray() as $key => $user) {
            $url = "http://magpie.hris.ssgbd.com/api/EmployeeInfoBPT?empCode=" . $user['employee_id'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $hrisResult = json_decode($response, true);
            if (is_array($hrisResult) && count($hrisResult) > 0) {
                $hrisResult = $hrisResult[0];
                if (isset($hrisResult['departmentHeadId']) && $hrisResult['departmentName'] == 'Sales') {
                    $departmentHeadId = $hrisResult['departmentHeadId'];
                    $supervisorId = isset($hrisResult['departmentHeadId']) ? $hrisResult['departmentHeadId'] : $hrisResult['supervisorId'];
                    $headInfo = User::select('id', 'employee_id', 'dept_id')->where('employee_id', $hrisResult['departmentHeadId'])->where('status', 1)->first();
                    // if (!$headInfo) {
                    //     $headInfo = User::select('id', 'employee_id', 'dept_id')->where('employee_id', $departmentHeadId)->where('status', 1)->first();
                    // }
                    // if (!$headInfo && $hrisResult['departmentName'] == 'Sales') {
                    //     $headInfo = User::select('id', 'employee_id', 'dept_id')->where('role_id', 5)->where('dept_id', 42)->where('status', 1)->first();
                    // }
                    if ($headInfo && $hrisResult['departmentHeadId'] == '10350') {
                        $headInfo = $headInfo->toArray();
                        if (($headInfo['dept_id'] != $user['dept_id'])) {
                            DB::beginTransaction();
                            try {
                                // Update Department_transfer table
                                $Department_transfer = new Department_transfer;
                                $Department_transfer->user_id = $user['id'];
                                $Department_transfer->current_dept = $user['dept_id'];
                                $Department_transfer->new_dept = $headInfo['dept_id'];
                                $Department_transfer->kra_data = 1;
                                $Department_transfer->daily_data = 1;
                                $Department_transfer->tour_data = 1;
                                $Department_transfer->save();

                                // Update User table
                                $User = User::find($user['id']);
                                $User->dept_id = $headInfo['dept_id'];
                                $User->save();


                                TourEntry::where('user_id', $user['id'])
                                    ->update(['dept_id' => $headInfo['dept_id']]);

                                DailySchedule::where('user_id', $user['id'])
                                    ->update(['dept_id' => $headInfo['dept_id']]);
                                KRA::where('user_id', $user['id'])
                                    ->update(['dept_id' => $headInfo['dept_id']]);

                                KPI::join('k_r_a_s', 'k_p_i_s.kra_id', 'k_r_a_s.id')
                                    ->where('k_r_a_s.user_id', $user['id'])
                                    ->update(['k_p_i_s.dept_id' => $headInfo['dept_id']]);

                                MOS::join('k_r_a_s', 'm_o_s.kra_id', 'k_r_a_s.id')
                                    ->where('k_r_a_s.user_id', $user['id'])
                                    ->update(['m_o_s.dept_id' => $headInfo['dept_id']]);

                                MosFeadback::where('user_id', $user['id'])
                                    ->update(['dept_id' => $headInfo['dept_id']]);

                                MosData::join('m_o_s', 'm_o_s.id', 'mos_datas.mos_id')
                                    ->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id')
                                    ->where('k_r_a_s.user_id', $user['id'])
                                    ->update(['mos_datas.dept_id' => $headInfo['dept_id']]);

                                DB::commit();
                            } catch (\Exception $e) {
                                DB::rollback();
                                //return $this->sendResponse($e, 'Department not Change');
                            }
                        }
                    }
                }
            }
        }

        return $this->sendResponse($users, 'Department Changed successfully');
    }

    // public function user_designation_sync(Request $request)
    // {
    //     ini_set('max_execution_time', 100000000);
    //     $usersQ = User::select('id', 'employee_id', 'dept_id');
    //     $usersQ->whereIn('role_id', [6, 7, 8, 9, 10]);
    //     //$usersQ->whereNotIn('dept_id', [40, 41, 42]);
    //     $usersQ->where('status', 1);
    //     if ($request->employee_id) {
    //         $usersQ->where('employee_id', $request->employee_id);
    //     }
    //     $users = $usersQ->get();
    //     //    print_r($users->toArray());
    //     //    exit();
    //     foreach ($users->toArray() as $key => $user) {
    //         $url = "http://magpie.hris.ssgbd.com/api/EmployeeInfoBPT?empCode=" . $user['employee_id'];
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         $response = curl_exec($ch);
    //         $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //         curl_close($ch);
    //         $hrisResult = json_decode($response, true);
    //         if (is_array($hrisResult) && count($hrisResult) > 0) {
    //             $hrisResult = $hrisResult[0];
    //             DB::beginTransaction();
    //             try {
    //                 $User = User::find($user['id']);
    //                 $User->designation = $hrisResult['designation'];
    //                 $User->save();
    //                 DB::commit();
    //             } catch (\Exception $e) {
    //                 DB::rollback();
    //                 //return $this->sendResponse($e, 'Department not Change');
    //             }
    //         }
    //     }
    //     return $this->sendResponse($users, 'Department Changed successfully');
    // }
    // public function user_info_sync_with_hr(Request $request)
    // {
    //     $url = "http://magpie.hris.ssgbd.com/api/EmployeeInfoBPT?empCode=" .
    //         $request->employee_id;
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     $response = curl_exec($ch);
    //     $hrisResult = json_decode($response, true);
        
    //     $hrisResult = $hrisResult[0];
    //     $employee = User::where("email", $request->employee_id)
    //         ->orWhere("employee_id", $request->employee_id)
    //         ->first();

    //     $departmentHead = [];
    //     $supervisorId = [];
    //     if ($hrisResult) {
    //         if (isset($hrisResult["departmentHeadId"])) {
    //             $departmentHead = User::where(
    //                 "email",
    //                 $hrisResult["departmentHeadId"]
    //             )
    //             ->orWhere(
    //                 "employee_id",
    //                 $hrisResult["departmentHeadId"]
    //             )
    //             ->first();
                
    //         }

    //         if (isset($hrisResult["supervisorId"])) {
    //             $supervisorId = User::where(
    //                 "email",
    //                 $hrisResult["supervisorId"]
    //             )
    //             ->orWhere("employee_id", $hrisResult["supervisorId"])
    //             ->first();
    //         }
    //     }

    //     $userData["dept_id"] = $departmentHead
    //         ? $departmentHead->dept_id
    //         : 1;
    //     $userData["wing_id"] =
    //         $supervisorId && $supervisorId->wing_id
    //         ? $supervisorId->wing_id
    //         : 0;

    //     $userData["name"] = $hrisResult
    //         ? $hrisResult["employeeName"]
    //         : $request->employee_id;
    //     $userData["ad_mail"] =
    //         $hrisResult && isset($hrisResult["email"])
    //         ? $hrisResult["email"]
    //         : "";
    //     $userData["role_id"] = 7;
    //     $userData["status"] = 1;
    //     $userData["designation"] = $hrisResult
    //         ? $hrisResult["designation"]
    //         : "";
    //     $userData["employee_id"] = $request->employee_id;
    //     try {
    //         if (!$employee) {
    //             $userData["email"] = $request->employee_id;
    //             $userData["password"] = bcrypt(12345);
    //             $employee = User::create($userData);
    //             return $this->sendResponse($employee, 'New Employee created successfully');
    //         } else {
    //             $employee = User::where('id', $employee->id)
    //                 ->update($userData);
    //             return $this->sendResponse($employee, 'Employee Update successfully');    
    //         }
    //     } catch (\Exception $e) {
    //         // If an exception is thrown, return an error response
    //         return $this->sendError('Error updating or creating Employee');
    //     }         
    // }
}
