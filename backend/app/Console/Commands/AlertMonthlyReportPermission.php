<?php

namespace App\Console\Commands;
use App\Models\MonthlyReport; 
use App\Models\Department; 
use Illuminate\Console\Command;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
class AlertMonthlyReportPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:alertmonthlyreport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monthly Summary Report Submission alert every month 4 date';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-05');

        // $MonthlyReport = MonthlyReport::select('departments.hod_name','departments.name')
        // ->join('departments','departments.id','monthly_reports.dept_id')
        // ->whereNotBetween('date',[$start_date,$end_date])
        // ->whereNotIn('departments.id',[52,53,93,94])
        // // ->where('departments.iskra',0)
        // ->groupBy('departments.name')
        // ->groupBy('departments.hod_email')
        // ->groupBy('departments.hod_name')
        // ->get();

        $monthly_report = Department::select('departments.hod_name','departments.name','departments.hod_email')
        ->whereNotIn('departments.id',[52,53,93,94])
        ->where('departments.status',0)
        ->whereNotIn('id', MonthlyReport::select('dept_id')
        ->whereNotBetween('date',[$start_date,$end_date])
        ->groupBy('dept_id')
        ->get())->get();


        foreach($monthly_report as $departments){           
            if(isset($departments['hod_email'])){ 
                $phpMail = new PHPMailer();
                $message = "";
                $phpMail->AddAddress($departments['hod_email'],  $departments['hod_name']);
                //$phpMail->AddAddress('sayed@gmail.com',  $departments['hod_name']);
               // $phpMail->AddAddress('asif.imran@ssgbd.com',  $departments['hod_name']);
                //$phpMail->AddCC('khushbu@ssgbd.com', "System CC");
                $phpMail->AddCC("shahidul.alam@ssgbd.com","Syed Shahidul Alam");
                $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
                $phpMail->AddCC("tasnim.tabassum@ssgbd.com","Tasnim Tabassum");
                $phpMail->AddCC('raihan.bhuyian@ssgbd.com', "System CC");
                $phpMail->AddCC('tanim.hr@ssgbd.com', "System CC");
                $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");
                $nextmonth = "";
                // '.date('F').'.'.
                $data['nextmonth'] = $end_date;
                $data['all_dept_comm'] = 'Today is the last date of submitting MSM (Monthly Summary Report) for the month of April.Please submit on due time and cooperate';
                $message = view('mail.summary_report')->with(['data' => $data]);

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
        }
    }
}
