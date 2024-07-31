<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMonthlyDateRangeAPIRequest;
use App\Http\Requests\API\UpdateMonthlyDateRangeAPIRequest;
use App\Models\MonthlyDateRange;
use App\Models\Department;
use App\Models\DepartmentTemplates;
use App\Repositories\MonthlyDateRangeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TemplatesResource;
use Response;
use Auth;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
/**
 * Class MonthlyDateRangeController
 * @package App\Http\Controllers\API
 */

class MonthlyDateRangeAPIController extends AppBaseController
{
    /** @var  MonthlyDateRangeRepository */
    private $monthlyDateRangeRepository;

    public function __construct(MonthlyDateRangeRepository $monthlyDateRangeRepo)
    {
        $this->monthlyDateRangeRepository = $monthlyDateRangeRepo;
    }

    /**
     * Display a listing of the MonthlyDateRange.
     * GET|HEAD /monthlyDateRanges
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $monthlyDateRanges = $this->monthlyDateRangeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($monthlyDateRanges->toArray(), 'Monthly Date Ranges retrieved successfully');
    }
    public function department_monthly_report_update(Request $request){
        $items = $request['items'] ;
        $permission_for = $request->permission_for;
        foreach ($items as $key => $value) {
            if(isset($value['monthly_date_range']['start_date']) && isset($value['monthly_date_range']['end_date'])){
                $value['monthly_date_range']['start_date'] = date('Y-m-d',strtotime($value['monthly_date_range']['start_date']));   
                $value['monthly_date_range']['end_date'] = date('Y-m-d',strtotime($value['monthly_date_range']['end_date'])); 
                $value['monthly_date_range']['permission_for'] = (int) $permission_for;           
                $this->monthlyDateRangeRepository->update($value['monthly_date_range'], $value['monthly_date_range']['id']);    
            } 
        } 
        return $this->sendResponse($request, 'Department date settings Update retrieved successfully');
       
    }
    
    public function monthly_permission(Request $request){
        $MonthlyDateRange = MonthlyDateRange::where('dept_id',$request->department)
        ->where('permission_for',0)
        ->first();
        if(isset($MonthlyDateRange)){
            $MonthlyDateRange->start_date = $request->todate;
            $MonthlyDateRange->end_date = $request->fromdate;
            $MonthlyDateRange->status = 1;
            $MonthlyDateRange->save();
        }

        $departments =  Department::find($request->department)->toArray();
        if ($departments['hod_email']) {
            $phpMail = new PHPMailer();
            $message = "";
            $phpMail->AddAddress($departments['hod_email'],  'System');
            $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
            $phpMail->AddCC('tanim.hr@ssgbd.com', "System CC");
            $phpMail->AddCC('sayed@ssgbd.com', 'System CC');
            $phpMail->AddCC("tasnim.tabassum@ssgbd.com","Tasnim Tabassum");            
            $nextmonth = "";
            $data['contena'] = 'Your Monthly Summery Report permission request approved, Please complete the changes by ' . $request->fromdate . '
                

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
            $phpMail->Subject = "Your Monthly report permission request approved";
            $phpMail->Body = $msg;
            $phpMail->SMTPAuth = false;

            if (!$phpMail->Send()) {
                echo "Message could not be sent.";
                echo "Mailer Error: " . $phpMail->ErrorInfo;
                // exit;
            }

            return redirect('https://bpt.ssgbd.com?permission=Permission approved successfully');
        }


        //return redirect('https://bpt.ssgbd.com/');
    }

    public function templates_updates(Request $request){
        $items = $request->items;
        foreach ($items as $key => $value) { 
            $type = 1;//(int) $value['type']['type']==1?1:0;
            $DepartmentTemplates = DepartmentTemplates::where('dept_id',$value['id'])->first();

            if($DepartmentTemplates){
                $DepartmentTemplates->type = $type;
                $DepartmentTemplates->save();
            }else{
                $DepartmentTemplates = new DepartmentTemplates();
                $DepartmentTemplates->type = $type;
                $DepartmentTemplates->dept_id = $value['id'];
                $DepartmentTemplates->save();
            }
        } 
        return $this->sendResponse($items, 'Department date settings Update retrieved successfully');
       
    }

    public function templates_department(){
        $user_data = Auth::user();  
        $result = DepartmentTemplates::where('dept_id',$user_data->dept_id)->first();
        $data_return = new TemplatesResource($result);  
        return $this->sendResponse($data_return, 'Data retrieved successfully');   
    }
    /**
     * Store a newly created MonthlyDateRange in storage.
     * POST /monthlyDateRanges
     *
     * @param CreateMonthlyDateRangeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMonthlyDateRangeAPIRequest $request)
    {
        $input = $request->all();

        $monthlyDateRange = $this->monthlyDateRangeRepository->create($input);

        return $this->sendResponse($monthlyDateRange->toArray(), 'Monthly Date Range saved successfully');
    }

    /**
     * Display the specified MonthlyDateRange.
     * GET|HEAD /monthlyDateRanges/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MonthlyDateRange $monthlyDateRange */
        $monthlyDateRange = $this->monthlyDateRangeRepository->find($id);

        if (empty($monthlyDateRange)) {
            return $this->sendError('Monthly Date Range not found');
        }

        return $this->sendResponse($monthlyDateRange->toArray(), 'Monthly Date Range retrieved successfully');
    }

    /**
     * Update the specified MonthlyDateRange in storage.
     * PUT/PATCH /monthlyDateRanges/{id}
     *
     * @param int $id
     * @param UpdateMonthlyDateRangeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonthlyDateRangeAPIRequest $request)
    {
        $input = $request->all();

        /** @var MonthlyDateRange $monthlyDateRange */
        $monthlyDateRange = $this->monthlyDateRangeRepository->find($id);

        if (empty($monthlyDateRange)) {
            return $this->sendError('Monthly Date Range not found');
        }

        $monthlyDateRange = $this->monthlyDateRangeRepository->update($input, $id);

        return $this->sendResponse($monthlyDateRange->toArray(), 'MonthlyDateRange updated successfully');
    }

    /**
     * Remove the specified MonthlyDateRange from storage.
     * DELETE /monthlyDateRanges/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MonthlyDateRange $monthlyDateRange */
        $monthlyDateRange = $this->monthlyDateRangeRepository->find($id);

        if (empty($monthlyDateRange)) {
            return $this->sendError('Monthly Date Range not found');
        }

        $monthlyDateRange->delete();

        return $this->sendSuccess('Monthly Date Range deleted successfully');
    }
}
