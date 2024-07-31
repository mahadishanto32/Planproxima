<?php

namespace App\Console\Commands;
use App\Models\MonthlyDateRange;
use App\Models\Department;
use App\Models\DepartmentSetting;
use Illuminate\Console\Command;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
class AchievementReportPermissionOff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:achievement_permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Achievement Report Permission Close';

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
        $start_date = date('F');
        $end_date = date("F", strtotime("+1 month", strtotime(date('Y-m-d'))));

        $Department = Department::with('ccmail')
        ->where('status',1)
        // ->where('id',6)
        ->get();

        foreach($Department as $value){
            if(isset($value['hod_email'])){ 
                $phpMail = new PHPMailer();
                $message = "";
                $phpMail->AddAddress($value['hod_email'],  $value['hod_name']); 
               // $phpMail->AddCC('khushbu@ssgbd.com', "System CC");
                $phpMail->AddCC("shahidul.alam@ssgbd.com","Syed Shahidul Alam");
                $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
                $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");
                $phpMail->AddCC('tanim.hr@ssgbd.com', "System CC");
                $phpMail->AddCC("tasnim.tabassum@ssgbd.com","Tasnim Tabassum");
                
                foreach($value['ccmail'] as $key=>$ccmail){
                    if(isset($ccmail['ad_mail'])){
                       $phpMail->AddCC($ccmail['ad_mail'], "System CC"); 
                    }
                }
               
                $nextmonth = "";
                $data['nextmonth'] = $end_date;
                $data['all_dept_comm'] = '
                From now on your monthly KPI update panel will remain open for the current month (E.g. '.$start_date.') till 5th of next month (E.g. '.$end_date.'). It is in LIVE now! You can update your this month KPI from today till 5th '.$end_date.'.
                However monthly summary report can be submitted according to previous timeframe, from 1-5th of every month.



                Regards,
                Team BPT
                ';
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
                $phpMail->Subject = "Monthly KRA & KPI Achievement Permission";
                $phpMail->Body = $msg;
                $phpMail->SMTPAuth = false;
        
                if (!$phpMail->Send()) {
                    echo "Message could not be sent.";
                    echo "Mailer Error: " . $phpMail->ErrorInfo;
                    exit;
                }
               // if($departments['hod_email'] ==8){
                    // if (!$phpMail->Send()) {
                    //     echo "Message could not be sent.";
                    //     echo "Mailer Error: " . $phpMail->ErrorInfo;
                    //     exit;
                    // }
                //} 
             
                $phpMail->ClearAddresses();
                $phpMail->ClearAttachments();
                // exit();
            }            
        }
        echo 'Success....!!';
    }
}
