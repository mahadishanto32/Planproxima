<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMOSAchievementPermissionAPIRequest;
use App\Http\Requests\API\UpdateMOSAchievementPermissionAPIRequest;
use App\Models\MOSAchievementPermission;
use App\Models\Department;
use App\Models\DepartmentAssign;
use App\Http\Resources\DepartmentMOSPermissionResource;
use App\Repositories\MOSAchievementPermissionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\MOS;
use App\Models\User;
use Response;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
use Auth;

/**
 * Class MOSAchievementPermissionController
 * @package App\Http\Controllers\API
 */

class MOSAchievementPermissionAPIController extends AppBaseController
{
    /** @var  MOSAchievementPermissionRepository */
    private $mOSAchievementPermissionRepository;

    public function __construct(MOSAchievementPermissionRepository $mOSAchievementPermissionRepo)
    {
        $this->mOSAchievementPermissionRepository = $mOSAchievementPermissionRepo;
    }

    /**
     * Display a listing of the MOSAchievementPermission.
     * GET|HEAD /mOSAchievementPermissions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // $mOSAchievementPermissions = $this->mOSAchievementPermissionRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );
        
        /*if ($request->user_id && $request->user_id != 0) {
            $query->where('m_o_s_achievement_permissions.user_id', $request->user_id);
        }elseif($request->user_id == 0){
            // $query->where('m_o_s_achievement_permissions.dept_id', $request->dept_id);
        }*/
       // return $request->user_id;
        $user_data = Auth::user();
        $query =  MOSAchievementPermission::select('m_o_s.mos_name', 'm_o_s_achievement_permissions.*');
        $query->join('m_o_s', 'm_o_s.id', 'm_o_s_achievement_permissions.mos_id');
        if(!isset($request->user_id) || $request->user_id != 0){
            
            if ($user_data->role_id ==  1) {
                $query->where('m_o_s_achievement_permissions.role_id', 5);
            } elseif ($user_data->role_id ==  5) {
                
                if ($request->wing_id) {
                } elseif ($request->user_id) { 
                    // return $this->sendResponse($request->user_id, ' test M O S Achievement Permissions retrieved successfully');
                    $query->where('m_o_s_achievement_permissions.user_id', $request->user_id);
                } else {
                    $query->where('m_o_s_achievement_permissions.role_id', 5);
                }
            } else {
                //return $this->sendResponse(1, ' test M O S Achievement Permissions retrieved successfully');
                $query->where('m_o_s_achievement_permissions.user_id', $user_data->id);
            }
        }
        if ($request->dept_id) {
            $query->where('m_o_s_achievement_permissions.dept_id', $request->dept_id);
        }
        $query->where('m_o_s_achievement_permissions.year',  $request->year);
        $query->limit(8000);
        $data  = $query->get();


        return $this->sendResponse($data, 'M O S Achievement Permissions retrieved successfully');
    }

    public function all_dept_mos_permission_update(Request $request)
    {
       
        //if ($request->only_dept || $request->all_user) {
            // $updateData  = array(
            //     $request->month => $request->month_value,
            //     'start_date' => $request->start_date ? $request->start_date : date('Y-m-d'),
            //     'end_date' =>  $request->end_date ?   $request->end_date : date('Y-m-d')
            // );

            // $q = MOSAchievementPermission::query();

            // if ($request->only_dept && $request->all_user) {
            //     $q->whereIn('role_id', [5, 6, 7, 8, 9, 10]);
            // } elseif ($request->only_dept) {
            //     $q->where('role_id', 5);
            // } elseif ($request->all_user) {
            //     $q->whereIn('role_id', [6, 7, 8, 9, 10]);
            // } 
            // else {
            //     $q->where('role_id', 4);
            // }
            

            // if ($request->dept_id ) {
            //     $q->where('dept_id', $request->dept_id);
            // }
            // $q->update($updateData);
            // return $this->sendResponse($updateData,  '1 Permission update successfully');

            $updateData  = array(
                $request->month => $request->month_value,
                'start_date' => $request->start_date ?? date('Y-m-d'), // Use provided start date or current date
                'end_date' => $request->end_date ?? date('Y-m-d') // Use provided end date or current date
            );

 

            $q = MOSAchievementPermission::where('role_id', 5);
            if ($request->dept_id) {
                $q->where('dept_id', $request->dept_id);
            }
            $q->update($updateData);
         
        return $this->sendResponse($updateData,  'Permission update successfully');

        //MOSAchievementPermission
        // $user_data = Auth::user(); 

        // $q = Department::where('status', 1); 
        // if($user_data->role_id !=1){
        //     $q->where('id',$user_data->dept_id);
        // }
        // $departments = $q->get();

        // $departments = DepartmentMOSPermissionResource::collection($departments);
        // return $this->sendResponse($departments, 'Departments retrieved successfully');
    }

    public function department_mos_permission_settings_update(Request $request)
    {
    }

    public function m_o_s_permission($id, Request $request)
    {
        $result = MOSAchievementPermission::where('mos_id', $id)->first();
        return $this->sendResponse($result,  'Permission Data');
    }

    public function department_mos_setting(Request $request)
    {
        $user_data = Auth::user();

        $q = Department::where('status', 1);
        if ($user_data->role_id != 1) {
            $q->where('id', $user_data->dept_id);
        }
        $departments = $q->get();

        $departments = DepartmentMOSPermissionResource::collection($departments);
        return $this->sendResponse($departments, 'Departments retrieved successfully');
    }

    public function m_o_s_achievement_permissions_update(Request $request)
    {
        $user_data = Auth::user();
        $items = $request->items;
        foreach ($items as $key => $item) {
            $updateData  = array(
                'jan' => $item['jan'],
                'feb' => $item['feb'],
                'mar' => $item['mar'],
                'apr' => $item['apr'],
                'may' => $item['may'],
                'jun' => $item['jun'],
                'jul' => $item['jul'],
                'aug' => $item['aug'],
                'sep' => $item['sep'],
                'oct' => $item['oct'],
                'nov' => $item['nov'],
                'dec' => $item['dec'],
                'start_date' => $item['start_date'],
                'end_date' => $item['end_date'],
                'request_status' => $this->checkRequestStatus($item) ? $item['user_id'] == $user_data->id ? 1 : 2 : 0
            );
            MOSAchievementPermission::where('id', $item['id'])
                ->update($updateData);
        }

        if ($items[0]['user_id'] == $user_data->id) {
            $phpMail = new PHPMailer();
            $message = "";
            $dept =  Department::find($user_data->dept_id);
            if ($user_data->role_id == 5) { 
                $phpMail->AddAddress("shahidul.alam@ssgbd.com","Syed Shahidul Alam"); 
                $phpMail->AddCC('sayed@ssgbd.com', "System CC");
                $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
                $phpMail->AddCC('tanim.hr@ssgbd.com', "System CC");
                $phpMail->AddCC("tasnim.tabassum@ssgbd.com","Tasnim Tabassum");
            } else {
                $phpMail->AddAddress($dept->hod_email, $dept->name);
                //$phpMail->AddAddress('sayed@ssgbd.com', "System CC");
                $phpMail->AddCC('sayed@ssgbd.com', "System CC");
                $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
            }
            $nextmonth = "";
            $query =  MOSAchievementPermission::select('k_r_a_s.kra_name', 'k_p_i_s.kpi_name', 'm_o_s.mos_name', 'm_o_s_achievement_permissions.*');
            $query->join('m_o_s', 'm_o_s.id', 'm_o_s_achievement_permissions.mos_id');
            $query->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id');
            $query->join('k_p_i_s', 'k_p_i_s.id', 'm_o_s.kpi_id');
            if ($user_data->role_id ==  5) {
                $query->where('m_o_s_achievement_permissions.role_id', 5);
                $query->where('m_o_s_achievement_permissions.dept_id', $user_data->dept_id);
            } else {
                $query->where('m_o_s_achievement_permissions.user_id', $user_data->id);
            }
            $query->where('m_o_s_achievement_permissions.year', $request->year);
            $query->where('m_o_s_achievement_permissions.request_status', 1);
            $query->limit(200);
            $returndata  = $query->get();
            $data['items'] = $returndata;
            $data['bpt_link'] =  'https://bpt.ssgbd.com/m_o_s_achievement_permissions/' . $user_data->dept_id . '?user_id=' . $user_data->id;

            $data['approved_link'] = 'https://bpt.ssgbd.com/backend/public/api/mos_permission_approved?user_id=' . $user_data->id . '&year =' . $request->year;

            $data['content'] = 'Achievement permissions request for ' . $user_data->name;
            $message = view('mail.m_o_s_achievement_permissions')->with(['data' => $data]);
            //$message = view('mail.achievements_panel')->with(['data' => $data]);

            $user = $user_data->name;
            $user_email = "management.desk@ssgbd.com";

            $phpMail->AddReplyTo("management.desk@ssgbd.com", "Check Mail");

            $msg = nl2br($message);

            $phpMail->FromName = $user;
            $phpMail->From = "management.desk@ssgbd.com";
            $phpMail->Sender = $user_email;
            $phpMail->IsHTML(true);
            $phpMail->Host = "mail.ssgbd.com:25";
            $phpMail->IsSMTP();
            $phpMail->Mailer  = "smtp";
            $phpMail->Subject = "Achievement Permission Requerst";
            $phpMail->Body = $msg;
            $phpMail->SMTPAuth = false;

            if (!$phpMail->Send()) {
                echo "Message could not be sent.";
                echo "Mailer Error: " . $phpMail->ErrorInfo;
            }



            return $this->sendResponse(1,  'Successfully submitted to permission request ');
        }

        //m_o_s_achievement_permissions_update
    }
    public function mos_permission_approved(Request $request)
    {
        $updateData = array('request_status' => 2);
        MOSAchievementPermission::where('user_id', $request->user_id)
            ->where('request_status', 1)
            ->update($updateData);

        $users = User::find($request->user_id);
        if ($users) {


            $phpMail = new PHPMailer();
            $message = "";
            $phpMail->AddAddress($users['ad_mail'],  $users['name']); 
           $phpMail->AddCC("shahidul.alam@ssgbd.com","Syed Shahidul Alam");
           $phpMail->AddCC("tasnim.tabassum@ssgbd.com","Tasnim Tabassum ");
            
            $phpMail->AddCC('sayed@ssgbd.com', "System CC");

            $nextmonth = "";
            $data['contena'] = 'Your achievement permission request approved, Please update your achievement 
            

            Regards,
            Team BPT
            ';
            $message = view('mail.default_theme')->with(['data' => $data]);

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
            $phpMail->Mailer  = "smtp";
            $phpMail->Subject = "Your achievement permission request approved";
            $phpMail->Body = $msg;
            $phpMail->SMTPAuth = false;

            if (!$phpMail->Send()) {
                echo "Message could not be sent.";
                echo "Mailer Error: " . $phpMail->ErrorInfo;
                // exit;
            }
        }


        return redirect('https://bpt.ssgbd.com?permission=Permission approved successfully');
    }
    public function checkRequestStatus($item)
    {
        if ($item['jan'] == 1) {
            return true;
        } elseif ($item['feb'] == 1) {
            return true;
        } elseif ($item['mar'] == 1) {
            return true;
        } elseif ($item['apr'] == 1) {
            return true;
        } elseif ($item['may'] == 1) {
            return true;
        } elseif ($item['jun'] == 1) {
            return true;
        } elseif ($item['jul'] == 1) {
            return true;
        } elseif ($item['aug'] == 1) {
            return true;
        } elseif ($item['sep'] == 1) {
            return true;
        } elseif ($item['oct'] == 1) {
            return true;
        } elseif ($item['nov'] == 1) {
            return true;
        } elseif ($item['dec'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Store a newly created MOSAchievementPermission in storage.
     * POST /mOSAchievementPermissions
     *
     * @param CreateMOSAchievementPermissionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMOSAchievementPermissionAPIRequest $request)
    {
        $input = $request->all();

        $mOSAchievementPermission = $this->mOSAchievementPermissionRepository->create($input);

        return $this->sendResponse($mOSAchievementPermission->toArray(), 'M O S Achievement Permission saved successfully');
    }

    /**
     * Display the specified MOSAchievementPermission.
     * GET|HEAD /mOSAchievementPermissions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MOSAchievementPermission $mOSAchievementPermission */
        $mOSAchievementPermission = $this->mOSAchievementPermissionRepository->find($id);

        if (empty($mOSAchievementPermission)) {
            return $this->sendError('M O S Achievement Permission not found');
        }

        return $this->sendResponse($mOSAchievementPermission->toArray(), 'M O S Achievement Permission retrieved successfully');
    }

    /**
     * Update the specified MOSAchievementPermission in storage.
     * PUT/PATCH /mOSAchievementPermissions/{id}
     *
     * @param int $id
     * @param UpdateMOSAchievementPermissionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMOSAchievementPermissionAPIRequest $request)
    {
        $input = $request->all();

        /** @var MOSAchievementPermission $mOSAchievementPermission */
        $mOSAchievementPermission = $this->mOSAchievementPermissionRepository->find($id);

        if (empty($mOSAchievementPermission)) {
            return $this->sendError('M O S Achievement Permission not found');
        }

        $mOSAchievementPermission = $this->mOSAchievementPermissionRepository->update($input, $id);

        return $this->sendResponse($mOSAchievementPermission->toArray(), 'MOSAchievementPermission updated successfully');
    }

    /**
     * Remove the specified MOSAchievementPermission from storage.
     * DELETE /mOSAchievementPermissions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MOSAchievementPermission $mOSAchievementPermission */
        $mOSAchievementPermission = $this->mOSAchievementPermissionRepository->find($id);

        if (empty($mOSAchievementPermission)) {
            return $this->sendError('M O S Achievement Permission not found');
        }

        $mOSAchievementPermission->delete();

        return $this->sendSuccess('M O S Achievement Permission deleted successfully');
    }

    public function m_o_s_achievement_permissions_sync(Request $request)
    {

        $query  = MOS::select('k_r_a_s.user_id', 'k_r_a_s.role_id', 'm_o_s.id as mos_id', 'k_r_a_s.dept_id', 'k_r_a_s.year')
            ->where('m_o_s.year', $request->year)
            ->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id')
            ->leftJoin('m_o_s_achievement_permissions', 'm_o_s_achievement_permissions.mos_id', '=', 'm_o_s.id')
            ->whereNull('m_o_s_achievement_permissions.mos_id');
        if ($request->dept_id) {
            $query->where('k_r_a_s.dept_id', $request->dept_id);
        } 
        $data = $query->get()->toArray();
        MOSAchievementPermission::insert($data);

        return $this->sendSuccess($data, 'M O S Achievement Permission deleted successfully');
    }
}
