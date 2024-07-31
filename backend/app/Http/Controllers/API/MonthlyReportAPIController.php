<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMonthlyReportAPIRequest;
use App\Http\Requests\API\UpdateMonthlyReportAPIRequest;
use App\Models\MonthlyReport;
use App\Models\MonthlyReportFile;
use App\Models\MonthlyDateRange;
use App\Models\Department;
use App\Models\DepartmentAssign;
use App\Repositories\MonthlyReportRepository;
use App\Repositories\MonthlyReportFileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MonthlyReportResource;
use App\Http\Resources\SummeryReportUpdateResource;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Models\Monthly_comment;
use Response;
use Auth, DB;
use App\Models\User;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;

/**
 * Class MonthlyReportController
 * @package App\Http\Controllers\API
 */

class MonthlyReportAPIController extends AppBaseController
{
    /** @var  MonthlyReportRepository */
    private $monthlyReportRepository;
    private $monthlyReportFileRepository;

    public function __construct(MonthlyReportRepository $monthlyReportRepo, MonthlyReportFileRepository  $monthlyReportFileRepo)
    {
        $this->monthlyReportRepository = $monthlyReportRepo;
        $this->monthlyReportFileRepository = $monthlyReportFileRepo;
    }

    /**
     * Display a listing of the MonthlyReport.
     * GET|HEAD /monthlyReports
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

    //  return $this->sendResponse(1, 'Test');
        $user_data = Auth::user();
        // if ($request->dept_id) {
        //     $request['dept_id'] = $request->dept_id;
        // } else {
        //     $request['dept_id'] = $user_data->dept_id;
        // }


        // if ($user_data->role_id == 5 || $user_data->role_id == 6 || $user_data->role_id == 7) {
        //     $request['dept_id'] = $user_data->dept_id;
        // }
        // if (!$request['month'] || $request['month'] != '') {
        //     $request['month'] = date("m", strtotime($request->month ? $request->month  :  Now()));
        // }
        // if (!$request['year'] || $request['year'] != '') {
        //     $request['year'] = date("Y", strtotime($request->year ? $request->year  :  Now()));
        // }

        // $monthlyReports = $this->monthlyReportRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

       $query =  MonthlyReport::query();
        if ($request->dept_id) {
            $query->where('dept_id',$request->dept_id );
        } else {
            $query->where('dept_id', $user_data->dept_id ); 
        }
        if($request->year){
            $query->where('year',$request->year );
        } 
        if($request->month){
            $query->where('month',$request->month );
        } 
        $query->limit(100);

        $monthlyReports = $query->get();


        $return_data = MonthlyReportResource::collection($monthlyReports);

        return $this->sendResponse($return_data, 'Monthly Reports retrieved successfully');
    }

    public function monthly_report_permission(Request $request)
    {
        $to_date = ($request->toDate != '' ? date('Y-m-d', strtotime($request->toDate)) : date('Y-m-d'));
        $from_date = ($request->fromDate != '' ? date('Y-m-d', strtotime($request->fromDate)) : date('Y-m-d'));
        $departments =  Department::find($request->dept_id);
        //return $this->sendResponse($request->dept_id, 'Mail send successfully');
        if (isset($departments['hod_email'])) {
            $phpMail = new PHPMailer();
            $message = "";
            $phpMail->AddAddress("management.desk@ssgbd.com",  "Chairman office");
            // $phpMail->AddCC('shahidul.alam@ssgbd.com', "System CC");
            if ($request->mailcc1 != "") {
                $phpMail->AddCC($request->mailcc1, "System CC");
            }
            if ($request->mailcc2 != "") {
                $phpMail->AddCC($request->mailcc2, "System CC");
            }
            if ($request->mailcc3 != "") {
                $phpMail->AddCC($request->mailcc3, "System CC");
            }
            $phpMail->AddReplyTo("management.desk@ssgbd.com", "Check Mail");

            $phpMail->AddCC("khushbu@ssgbd.com", "Khushbu Moni Lopa");
            $phpMail->AddCC("mohammd.karim@ssgbd.com", "Mohammd Karim");
            $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
            $phpMail->AddCC('sayed@ssgbd.com', 'System CC');
            //$phpMail->AddCC('raihan.bhuyian@ssgbd.com', "System CC");
            $nextmonth = "";


            $data['nextmonth'] = $to_date;
            $data['to_date'] = $to_date;
            $data['from_date'] = $from_date;
            $data['department'] = $departments;
            $data['all_dept_comm'] = $request->comment;
            $message = view('mail.monthly_permission')->with(['data' => $data]);

            $user = $departments['name'];
            $user_email = "management.desk@ssgbd.com";



            $msg = nl2br($message);

            $phpMail->FromName = $user;
            $phpMail->From = $departments['hod_email'];
            $phpMail->Sender = $user_email;
            $phpMail->IsHTML(true);
            $phpMail->Host = "mail.ssgbd.com:25";
            $phpMail->IsSMTP();
            $phpMail->Mailer  = "smtp";
            $phpMail->Subject = "Monthly Report Permission";
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
        return $this->sendResponse([], 'Mail send successfully');
    }

    public function monthly_not_update(Request $request)
    {

        $year =  $request->year  ;
        $month = ($request->month != '' ? $request->month : (int)date('m') - 1);
        $user_data = Auth::user();

        // $q= DepartmentAssign::where('user_id',$user_data->id);
        // $q->whereNotIn('id', function ($query) use ($year, $month) {
        //         $query->select('user_id')
        //             ->where('month', $month)
        //             ->where('year', $year)
        //             ->from('monthly_reports');
        //     });
        //   //  $q->with('deptjoin');
        // $data = $q->get();
        // $q= User::whereIn('users.id',$assign);
        //$q->where('users.role_id', 5);
        // if($request->dept_id){
        //     $q->where('users.dept_id', $request->dept_id);
        // } 
        // $q->where('users.wing_id', 0);
        if ($user_data->dept_id == 6) {
            $user_data->id = 1;
        }
        $q = DepartmentAssign::select('dept_id')->where('user_id', $user_data->id);
        $q->whereNotIn('dept_id', function ($query) use ($year, $month) {
            $query->select('dept_id')
                ->where('month', $month)
                ->where('year', $year)
                ->from('monthly_reports');
        });
        $q->with('deptjoin');
        $data = $q->get();

        // $q= DepartmentAssign::select('dept_id')->where('user_id',$user_data->id);
        // $data = $q->get();

        return $this->sendResponse($data->toArray(), 'Monthly not update List');
    }

    public function monthly_not_update_mail(Request $request)
    {
        $to_date = ($request->to_date != '' ? date('Y-m-d', strtotime($request->to_date)) : date('Y-m-d'));

        $departments =  $request->dept_selects;


        foreach ($departments as $key => $value) {

            if (isset($value['deptjoin']['hod_email']) && $value['deptjoin']['mail_allow'] == 1) {
                $phpMail = new PHPMailer();
                $message = "";
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

                //ccMail start
                $ccMailuser = User::select('users.name', 'users.ad_mail')
                    ->join('department_c_cmails', 'department_c_cmails.user_id', '=', 'users.id')
                    ->where('department_c_cmails.dept_id', $value['deptjoin']['id'])
                    ->whereNull('department_c_cmails.deleted_at')
                    ->get();
                foreach ($ccMailuser as $keyCc => $cc) {
                    if ($cc['ad_mail']) {
                        $phpMail->AddCC($cc['ad_mail'], $cc['name']);
                    }
                }
                // ccMail end

                //$phpMail->AddCC('khushbu@ssgbd.com', "System CC"); 
                $phpMail->AddCC('raihan.bhuyian@ssgbd.com', "System CC");
                $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
                // $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");

                $nextmonth = "";


                $data['nextmonth'] = $to_date;
                $data['all_dept_comm'] = $request->all_dept_comm;
                $message = view('mail.monthly_not_update_mail')->with(['data' => $data]);

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
                $phpMail->Subject = "Monthly summary report not updated";
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
        }

        return $this->sendResponse([], 'Mail send successfully');
    }

    public function monthly_report_mail($dept, $month)
    {

        $department = Department::find($dept);

        $monthname = date('F', strtotime("2021-" . $month . "-01"));
        $subject = "Monthly Report for " . $monthname;
        $subject = addslashes($subject);

        $user = $department->department_name;
        $user_email = $department->hod_email;

        $message = "";
        $message .= "Dear Sir,<br/>";
        $message .= "The monthly report for the department <u>" . $user . "</u>  is ready for the month of <u>" . $monthname . "</u>.<br/>";
        $message .= "Requesting you to review the detail report from Business Plan Tracker.<br/>";
        $message .= "<br/>";
        $message .= "Regards,<br/>";
        $message .= $user_email . "<br/>";
        $message .= $user . "<br/>";
        $phpMail = new PHPMailer();

        //$phpMail->AddAddress("sayed@ssgbd.com","Mizan");

        $phpMail->AddAddress("ibrahim@ssgbd.com", "Mohammed Ibrahim");
        $phpMail->AddCC("khushbu@ssgbd.com", "Khushbu Moni Lopa");
        $phpMail->AddCC("mohammd.karim@ssgbd.com", "Muhammad Nazmul Karim");

        $phpMail->AddReplyTo($user_email, $user);
        $msg = $message;

        $phpMail->FromName = $user;
        $phpMail->From = $user_email;
        $phpMail->Sender = $user_email;
        $phpMail->IsHTML(true);
        $phpMail->Host = "mail.ssgbd.com";
        $phpMail->IsSMTP();
        $phpMail->Mailer   = "smtp";
        $phpMail->Subject = $subject;
        $phpMail->Body = $msg;
        $phpMail->SMTPAuth = false;
        $phpMail->Port = 25;
        $msg = "";

        if ($phpMail->Send()) {
            // return Redirect::back()->with('success', 'Mail send done');
            return $this->sendResponse(0, 'Mail send done');
        } else {
            return $this->sendResponse(0, ' Error. Please Communicate With IT');
        }

        $phpMail->ClearAddresses();
        $phpMail->ClearAttachments();
        return $this->sendResponse(1, 'Mail send successfully');
    }

    public function monthly_mail(Request $request)
    {
        $year = ($request->year != '' ? $request->year : (int)date('Y'));
        $month = ($request->month != '' ? $request->month : (int)date('m') - 1);

        if ($request->all_dept) {
            $departments = User::where('status', 1)
                ->where('role_id', 5)
                ->whereNotIn('id', function ($query) use ($year, $month) {
                    $query->select('user_id')
                        ->where('month', $month)
                        ->where('year', $year)
                        ->from('monthly_reports');
                })
                ->get();
            foreach ($departments as $key => $value) {


                $phpMail = new PHPMailer();
                $message = "";
                $phpMail->AddAddress($value->ad_mail, $value->name);
                $phpMail->AddAddress("sazzadul.islam@ssgbd.com", "Sazzadul islam");
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


                $data['year'] = $year;
                $data['month'] = $month;
                $data['all_dept_comm'] = $request->all_dept_comm;
                $message = view('mail.monthly_mail')->with(['data' => $data]);

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
                $phpMail->Subject = "Comment on Monthly report";
                $phpMail->Body = $msg;
                $phpMail->SMTPAuth = false;


                if (!$phpMail->Send()) {
                    echo "Message could not be sent.";
                    echo "Mailer Error: " . $phpMail->ErrorInfo;
                    exit;
                }

                $phpMail->ClearAddresses();
                $phpMail->ClearAttachments();
                // dd();
            }
        } else {

            $departments = User::where('status', 1)
                ->where('role_id', 5)
                ->whereNotIn('id', function ($query) use ($year, $month) {
                    $query->select('user_id')
                        ->where('month', $month)
                        ->where('year', $year)
                        ->from('monthly_reports');
                })
                ->get();
            foreach ($departments as $key => $value) {
                if (isset($request->dept_check[$value->id])) {

                    $phpMail = new PHPMailer();
                    $message = "";
                    $phpMail->AddAddress($value->ad_mail, $value->name);
                    $phpMail->AddAddress("sayed@ssgbd.com", "Sazzadul islam");
                    if ($request->mailcc1 != "") {
                        $phpMail->AddCC($request->mailcc1, "System CC");
                    }
                    if ($request->mailcc2 != "") {
                        $phpMail->AddCC($request->mailcc2, "System CC");
                    }
                    if ($request->mailcc3 != "") {
                        $phpMail->AddCC($request->mailcc3, "System CC");
                    }
                    $data['year'] = $year;
                    $data['month'] = $month;
                    $data['all_dept_comm'] = $request->comm[$value->id];
                    $message = view('mail.monthly_mail')->with(['data' => $data]);

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
                    $phpMail->Subject = "Comment on Monthly report";
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
            }
        }
        return $this->sendResponse([], 'Mail send successfully');
    }


    public function store(CreateMonthlyReportAPIRequest $request)
    {
        $user_data = Auth::user();
        $month_data =  MonthlyDateRange::where('status', 1)->where('dept_id',  $user_data->dept_id)->first();

        //     $currentDate = date('Y-m-d');
        //     $currentDate=date('Y-m-d', strtotime($currentDate));
        //     //echo $paymentDate; // echos today! 
        //     $start_date = date('Y-m-d', strtotime($month_data ? $month_data->start_date :'' ));
        //     $end_date = date('Y-m-d', strtotime($month_data ? $month_data->end_date : ''));
        //    // return $this->sendResponse($end_date, 'Monthly Report saved successfully');    
        //     if (($currentDate >= $start_date) && ($currentDate <= $end_date)){



        $monthSet = ($request->month != '' ? $request->month : (int)date('m'));
        if ($request->month == '' && $monthSet == 1) {
            $month = 12;
        } else {
            $month = ($request->month != '' ? $request->month : (int)date('m') - 1);
        }

        $request['dept_id'] = $user_data->dept_id;
        $request['user_id'] = $user_data->id;
        $request['role_id'] = $user_data->role_id;
        $request['year'] = $request->years;
        $request['month'] = $month;
        $request['date'] = date("d-m-Y", strtotime($request->date ? $request->date  :  Now()));
        $input = $request->all();
        $monthlyReport = $this->monthlyReportRepository->create($input);

        // $file_name = Storage::disk('public')->put('report', $request->file('reportFile')); 
        // $file['report_id'] = $monthlyReport->id ;
        // $file['file_name'] = $file_name ;
        // $this->monthlyReportFileRepository->create($file);  
        if ($request->reportFile) {
            foreach ($request->reportFile as $key => $value) {
                $file_caption = $value->getClientOriginalName();
                $extension = $value->getClientOriginalExtension();
                $file_name = Storage::disk('public')->put('report', $value);
                $file['report_id'] = $monthlyReport->id;
                $file['file_name'] = $file_name;
                $file['file_caption'] = $file_caption;
                $file['file_type'] = $extension;
                $this->monthlyReportFileRepository->create($file);
            }
        }

        return $this->sendResponse($monthlyReport->toArray(), 'Monthly Report saved successfully');

        // }else{
        //     return $this->sendResponse(0 , 'Data not updated, contact IT ');
        // }
    }

    public function new_file(Request $request)
    {
        $user_data = Auth::user();

        $month_data =  MonthlyDateRange::where('status', 1)->where('dept_id',  $user_data->dept_id)->first();

        $currentDate = date('Y-m-d');
        $currentDate = date('Y-m-d', strtotime($currentDate));
        //echo $paymentDate; // echos today! 
        $start_date = date('Y-m-d', strtotime($month_data ? $month_data->start_date : ''));
        $end_date = date('Y-m-d', strtotime($month_data ? $month_data->end_date : ''));
        // return $this->sendResponse($end_date, 'Monthly Report saved successfully');    
        if (($currentDate >= $start_date) && ($currentDate <= $end_date)) {
            if ($request->reportFile) {
                foreach ($request->reportFile as $key => $value) {
                    $file_caption = $value->getClientOriginalName();
                    $extension = $value->getClientOriginalExtension();
                    $file_name = Storage::disk('public')->put('report', $value);
                    $file['report_id'] = $request->id;
                    $file['file_name'] = $file_name;
                    $file['file_caption'] = $file_caption;
                    $file['file_type'] = $extension;
                    $this->monthlyReportFileRepository->create($file);
                }
            }

            return $this->sendResponse($month_data, 'Monthly Report new file saved successfully');
        } else {
            return $this->sendResponse(0, 'Data not updated, contact IT ');
        }
    }

    /**
     * Display the specified MonthlyReport.
     * GET|HEAD /monthlyReports/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function monthly_report_download($id)
    {
        $file = MonthlyReportFile::find($id);
        $file_name = $file['file_name'];
        $file_caption = $file['file_caption'];

        // print($file_caption);

        // exit();
        //return Response::download(storage_path('app/public/'.$file_name,  $file_caption);

        //  try {
        //     return response()->download(storage_path('app/public/'.$file_name) , $file_caption ); 
        //   } 
        //   //catch exception
        //   catch(Exception $e) {
        //     echo 'Message: ' .$e->getMessage();
        //   }

        $path = storage_path() . '/' . 'app' . '/public/' . $file_name;
        if (file_exists($path)) {
            return Response::download($path, $file_caption);
        }
    }




    // public function monthly_report_download($id){



    //     $file = MonthlyReportFile::find($id);
    //     $file_name = $file['file_name']; 
    //     // print_r(public_path('storage').'/'.$file_name);

    //     // exit();

    //        // $file_path = Storage::disk('public').$file_name;
    //        // $file_path = public_path('storage').'/'.$file_name;
    //         $strr = explode('/', $file_name);
    //         $strr_ext = explode('.', $strr[1]);
    //         //dd($strr_ext);
    //         $headers = array(
    //             'Content-Type: application/'.$strr[1] 
    //         );
    //    // }
    //   // $contents = Storage::get($file_name);

    //    return Response::download('D:\xampp-7.3\htdocs\bptv3\bptv3\storage\report\0Ymzw4j02SLcmD27GMbzdHAiqlq6zTFaWeC1Y7yO.xlsx', $strr[1], $headers);
    // }
    public function show($id)
    {
        /** @var MonthlyReport $monthlyReport */
        $monthlyReport = $this->monthlyReportRepository->find($id);

        if (empty($monthlyReport)) {
            return $this->sendError('Monthly Report not found');
        }

        $monthlyReport  = new  MonthlyReportResource($monthlyReport);

        return $this->sendResponse($monthlyReport, 'Monthly Report retrieved successfully');
    }

    /**
     * Update the specified MonthlyReport in storage.
     * PUT/PATCH /monthlyReports/{id}
     *
     * @param int $id
     * @param UpdateMonthlyReportAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonthlyReportAPIRequest $request)
    {
        $input = $request->all();

        /** @var MonthlyReport $monthlyReport */
        $monthlyReport = $this->monthlyReportRepository->find($id);

        if (empty($monthlyReport)) {
            return $this->sendError('Monthly Report not found');
        }

        $monthlyReport = $this->monthlyReportRepository->update($input, $id);

        return $this->sendResponse($monthlyReport->toArray(), 'MonthlyReport updated successfully');
    }

    /**
     * Remove the specified MonthlyReport from storage.
     * DELETE /monthlyReports/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MonthlyReport $monthlyReport */
        $monthlyReport = $this->monthlyReportRepository->find($id);

        if (empty($monthlyReport)) {
            return $this->sendError('Monthly Report not found');
        }

        $monthlyReport->delete();

        return $this->sendSuccess('Monthly Report deleted successfully');
    }
    public function summay_report_update(Request $request)
    {
        $user_data = Auth::user();
        $q = Department::where('status', 1);
        if ($request->dept_id) {
            $q->where('id', $request->dept_id);
        }
        $q->where('iskra', 1);
        $departments = $q->get();
        $departments = SummeryReportUpdateResource::collection($departments, $request->all());
        return $this->sendResponse($departments, 'Data retrieved successfully');
    }

    public function monthly_report_comment(Request $request)
    {
        $item  =  $request->item;
        //$test =  'sayed@ssgbd.com';
        $test =  '';
        if ($item) {
            $department = Department::find($item['dept_id']);
            $user_data = Auth::user();
            $month =  $item['month'];
            $worktype =  $item['worktype'];

            $phpMail = new PHPMailer();

            $phpMail = new PHPMailer();
            $message = "";

            if ($request->mailcc1 != "") {
                $phpMail->AddCC($request->mailcc1, "System CC");
            }
            if ($request->mailcc2 != "") {
                $phpMail->AddCC($request->mailcc2, "System CC");
            }
            if ($request->mailcc3 != "") {
                $phpMail->AddCC($request->mailcc3, "System CC");
            }
            if ($worktype == "work_with_plan" || $worktype == "without_plan" || $worktype == "undone") {
                if ($test == "") {
                    $phpMail->AddAddress($department->hod_email, $department->name);
                    // $phpMail->AddCC("management.desk@ssgbd.com", "Management Desk's Office");
                    $phpMail->AddCC("khushbu@ssgbd.com", "Khushbu Moni Lopa");
                    $phpMail->AddCC("mohammd.karim@ssgbd.com", "Muhammad Nazmul Karim");
                    // $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");
                    // $phpMail->AddCC("shahidul.alam@ssgbd.com","Syed Shahidul Alam");
                } else {
                    $phpMail->AddAddress($test, $department->name);
                    $phpMail->AddCC($test, "Khushbu Moni Lopa");
                }

                if ($worktype == "work_with_plan") {
                    $message = $message . "Type: With Plan <br/>";
                    $message = $message . "<span style='color:blue;'>" . $department->name . "</span> <br/>";
                    $message = $message . $item['monthly_work'] . " <br/>";
                    $message = $message . "<span style='color:orange;'>Comments: </span>" . $request->comment . " <br/>";
                } elseif ($worktype == "without_plan") {
                    $message = $message . "Type: Without Plan <br/>";
                    $message = $message . "<span style='color:blue;'>" . $department->name . "</span> <br/>";
                    $message = $message . $item['monthly_work'] . " <br/>";
                    $message = $message . "<span style='color:orange;'>Comments: </span>" . $request->comment . " <br/>";
                } elseif ($worktype == "undone") {
                    $message = $message . "Type: Undone <br/>";
                    $message = $message . "<span style='color:blue;'>" . $department->name . "</span> <br/>";
                    $message = $message . $item['monthly_work'] . " <br/>";
                    // $message=$message.$value['reason']." <br/>"; 
                    $message = $message . "<span style='color:orange;'>Comments: </span>" . $request->comment . " <br/>";
                }
            }

            if ($item["man_power_efficiency"] != '' || $item["topforcurrentmonth"] != "") {



                // $phpMail->AddAddress("sazzadul.islam@ssgbd.com","IT");
                if ($test == "") {
                    $phpMail->AddAddress($department->hod_email, $department->name);
                    $phpMail->AddCC("khushbu@ssgbd.com", "Khushbu Moni Lopa");
                    $phpMail->AddCC("mohammd.karim@ssgbd.com", "Muhammad Nazmul Karim");
                    // $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");
                    // $phpMail->AddCC("management.desk@ssgbd.com", "Management Desk's Office");
                } else {
                    $phpMail->AddAddress($test, $department->name);
                    $phpMail->AddCC($test, "Khushbu Moni Lopa");
                }


                if ($item["topforcurrentmonth"] != '') {
                    $message = $message . "TOP PRIORITY <br/>";
                    $message = $message . "<span style='color:blue;'>" . $item["topforcurrentmonth"] . "</span> <br/>";
                    $message = $message . "<span style='color:orange;'>Comments: </span>" . $request->comment . " <br/>";
                }
                if ($item["man_power_efficiency"] != '') {
                    $message = $message . "MAN POWER EFFICIENCY REPORT <br/>";
                    $message = $message . "<span style='color:blue;'>" . $item["man_power_efficiency"] . "</span> <br/>";
                    $message = $message . "<span style='color:orange;'>Comments: </span>" . $request->comment . " <br/>";
                }
            }

            if ($test == '') {
                if ($user_data->email == "ibrahim") {
                    $user = "Management Desk";
                    $user_email = "ibrahim@ssgbd.com";
                    $phpMail->AddReplyTo("ibrahim@ssgbd.com", "Management Desk");
                } elseif ($user_data->email == "hrashid") {
                    $user = "DMD Operation";
                    $user_email = "hrashid@ssgbd.com";
                    $phpMail->AddReplyTo("hrashid@ssgbd.com", "DMD Operation");
                }
            } else {
                $user = "Management Desk";
                $user_email =  $test;
                //$phpMail->AddAddress( $test , $department->name);
                $phpMail->AddReplyTo($test, "Khushbu Moni Lopa");
            }

            $msg = ($message);
            $phpMail->FromName = $user;
            if ($user_data->email == "ibrahim") {
                $phpMail->From = "ibrahim@ssgbd.com";
            } elseif ($user_data->email == "hrashid") {
                $phpMail->From = "hrashid@ssgbd.com";
            }
            $phpMail->Sender = $user_email;
            $phpMail->IsHTML(true);
            $phpMail->Host = "mail.ssgbd.com:25";
            $phpMail->IsSMTP();
            $phpMail->Mailer  = "smtp";
            $phpMail->Subject = "Comments on Monthly Report";
            $phpMail->Body = $msg;
            $phpMail->SMTPAuth = false;
            if (!$phpMail->Send()) {
                return $this->sendSuccess("Mailer Error: " . $phpMail->ErrorInfo);
            } else {
                return $this->sendSuccess('Monthly Report mail send successfully');
            }


            // if(!$phpMail->Send())
            // {

            //     return $this->sendSuccess( 0 ,"Mailer Error: " . $phpMail->ErrorInfo);
            // }else{

            //     $comment =  Monthly_comment::create(
            //         [
            //             'report_id' => $item['id'], 
            //             'user_id' =>  $user_data->id , 
            //             'dept_id' =>  $item['dept_id'], 
            //             'comment' =>  $request->comment
            //         ]
            //     );

            //     $phpMail->ClearAddresses();
            //     $phpMail->ClearAttachments(); 
            //     return $this->sendSuccess( 1,'Monthly Report mail send successfully'); 
            // } 



        }



        //     $user_data = Auth::user();
        //     $type = $request->type;
        //     if($type=="withplan" || $type=="withoutplan" || $type=="undone")
        //     {
        //         if($user_data->email=='ibrahim')
        //         {
        //             $input['mdcommentsdate'] = date("Y-m-d");
        //             $input['mdcomments'] = $request->comment;
        //             DB::table('tbl_monthly_work')->where('id', $request->id)->update($input);

        //         }
        //         if($user_data->email=='hrashid')
        //         {
        //             $input['dmdcommentsdate'] = date("Y-m-d");
        //             $input['dmdcomments'] = $request->comment;
        //             DB::table('tbl_monthly_work')->where('id', $request->id)->update($input);
        //         }               


        //     }

        //     if($type=="toppriority")
        //     {
        //         if($user_data->email=='ibrahim')
        //         {

        //             $input['mdcommentsdate'] = date("Y-m-d");
        //             $input['mdcomments'] = $request->comment;
        //             DB::table('tbl_monthly_undone')
        //             ->where('id', $request->id)
        //             ->where('month', $request->month)
        //             ->where('dept', $request->dept)
        //             ->where('year', $request->year)->update($input);

        //         }
        //         if($user_data->email=='hrashid')
        //         {
        //             $input['dmdcommentsdate'] = date("Y-m-d");
        //             $input['dmdcomments'] = $request->comment;
        //             DB::table('tbl_monthly_undone')
        //             ->where('id', $request->id)
        //             ->where('month', $request->month)
        //             ->where('dept', $request->dept)
        //             ->where('year', $request->year)->update($input);

        //         }               


        //     }

        //     if($request->comment!="")
        //     {

        //         $phpMail=new PHPMailer(); 
        //         $message="";

        //         if($request->mailcc1!="")
        //         {
        //             $phpMail->AddCC($request->mailcc1,"System CC");
        //         }
        //         if($request->mailcc2!="")
        //         {
        //             $phpMail->AddCC($request->mailcc2,"System CC");
        //         }
        //         if($request->mailcc3!="")
        //         {
        //             $phpMail->AddCC($request->mailcc3,"System CC");
        //         }

        //         if($type=="withplan" || $type=="withoutplan" || $type=="undone")
        //         {

        //             $queryDataShow = DB::table('tbl_monthly_work')
        //             ->join('tbl_department', 'tbl_department.id', 'tbl_monthly_work.dept')
        //             ->where('tbl_monthly_work.id', $request->id)->first();
        //             // 
        //             //$phpMail->AddAddress("kaniz.fatima@ssgbd.com","IT");
        //             $phpMail->AddAddress($queryDataShow->hod_email, $queryDataShow->hod_name);
        //             $phpMail->AddCC("khushbu@ssgbd.com","Khushbu Moni Lopa");
        //             $phpMail->AddCC("management.desk@ssgbd.com","Management Desk's Office");
        //             $phpMail->AddCC("muntasir.shovon@ssgbd.com","Muntasir");
        //             //$phpMail->AddCC("kaniz.fatima@ssgbd.com","IT");



        //             if($type=="withplan")
        //             {
        //                 $message=$message."Type: With Plan <br/>";
        //                 $message=$message."<span style='color:blue;'>".$queryDataShow->groupname."</span> <br/>";
        //                 $message=$message.$queryDataShow->work." <br/>";
        //                 $message=$message."<span style='color:orange;'>Comments: </span>".$request->comment." <br/>";

        //             }
        //             elseif($type=="withoutplan")
        //             {
        //                 $message=$message."Type: Without Plan <br/>";
        //                 $message=$message."<span style='color:blue;'>".$queryDataShow->groupname."</span> <br/>";
        //                 $message=$message.$queryDataShow->work." <br/>";
        //                 $message=$message."<span style='color:orange;'>Comments: </span>".$request->comment." <br/>";
        //             }
        //             elseif($type=="undone")
        //             {
        //                 $message=$message."Type: Undone <br/>";
        //                 $message=$message."<span style='color:blue;'>".$queryDataShow->groupname."</span> <br/>";
        //                 $message=$message.$queryDataShow->work." <br/>"; 
        //                 // $message=$message.$value['reason']." <br/>"; 
        //                 $message=$message."<span style='color:orange;'>Comments: </span>".$request->comment." <br/>";
        //             }

        //         }

        //         if($type=="toppriority" || $type=="manpower")
        //         {

        //             $queryDataShow = DB::table('tbl_monthly_undone')
        //             ->join('tbl_department', 'tbl_department.id', 'tbl_monthly_undone.dept')
        //             ->where('tbl_monthly_undone.id', $request->id)->first();

        //                 // $phpMail->AddAddress("sazzadul.islam@ssgbd.com","IT");
        //             $phpMail->AddAddress($queryDataShow->hod_email, $queryDataShow->hod_name);
        //             $phpMail->AddCC("khushbu@ssgbd.com","Khushbu Moni Lopa");
        //             $phpMail->AddCC("management.desk@ssgbd.com","Management Desk's Office"); 


        //             if($type=="toppriority")
        //             {
        //                 $message=$message."TOP PRIORITY <br/>";
        //                 $message=$message."<span style='color:blue;'>".$queryDataShow->topforcurrentmonth."</span> <br/>"; 
        //                 $message=$message."<span style='color:orange;'>Comments: </span>".$request->comment." <br/>";
        //             }
        //             if($type=="manpower")
        //             {
        //                 $message=$message."MAN POWER EFFICIENCY REPORT <br/>";
        //                 $message=$message."<span style='color:blue;'>".$queryDataShow->manpower."</span> <br/>"; 
        //                 $message=$message."<span style='color:orange;'>Comments: </span>".$request->comment." <br/>";
        //             }

        //         }

        //         if($user_data->email=="ibrahim")
        //         {   
        //             $user="Management Desk";  
        //             $user_email="ibrahim@ssgbd.com";
        //             $phpMail->AddReplyTo("ibrahim@ssgbd.com","Management Desk");
        //         }
        //         elseif($user_data->email=="hrashid")
        //         {
        //             $user="DMD Operation";  
        //             $user_email="hrashid@ssgbd.com";
        //             $phpMail->AddReplyTo("hrashid@ssgbd.com","DMD Operation");

        //         }

        //         $msg=($message);    

        //         $phpMail->FromName = $user; 

        //         if($user_data->email=="ibrahim")
        //         {
        //             $phpMail->From="ibrahim@ssgbd.com";
        //         }
        //         elseif($user_data->email=="hrashid")
        //         {
        //             $phpMail->From="hrashid@ssgbd.com";
        //         }


        //         $phpMail->Sender= $user_email;
        //         $phpMail->IsHTML(true);
        //         $phpMail->Host = "mail.ssgbd.com:25"; 
        //         $phpMail->IsSMTP();
        //         $phpMail->Mailer  = "smtp";
        //         $phpMail->Subject="Comments on Monthly Report";
        //         $phpMail->Body=$msg;            
        //         $phpMail->SMTPAuth=false; 


        //         if(!$phpMail->Send())
        //         {
        //             echo "Message could not be sent.";
        //             echo "Mailer Error: " . $phpMail->ErrorInfo;
        //             exit;
        //         }

        //         $phpMail->ClearAddresses();
        //         $phpMail->ClearAttachments();

        //     }

        // // return Redirect::back()->with('success', 'Mail send done');


    }
}
