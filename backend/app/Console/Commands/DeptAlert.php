<?php

namespace App\Console\Commands;

use App\Models\MonthlyDateRange;
use App\Models\Department;
use App\Models\DepartmentSetting;
use Illuminate\Console\Command;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;

class DeptAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:dept_alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dept. Alert';

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

        $todaye_date = date('d');

        //if($todaye_date == 18){ 
        $start_date = date('F');
        $end_date = date("F", strtotime("+1 month", strtotime(date('Y-m-d'))));
        $panel_open_date = date('Y-m-06');
        //$end_date = date("F", strtotime("+1 month", strtotime(date('Y-m-d'))));
        $panel_off_date = date('Y-m-d', strtotime("+1 month", strtotime(date('Y-m-05'))));

        $previous_date  = date('F', strtotime("-1 month", strtotime(date('Y-m-05'))));
        // print_r($panel_open_date);
        // exit();
        $Department = Department::select('departments.*')
            ->with('ccmail')
            ->whereNotIn('departments.id', [52, 53, 93, 94])
            ->where('departments.iskra', 1)
            //->where('departments.id',8)   
            ->get();
        // $Department = Department::with('ccmail')
        // ->where('status',1) 
        // ->get(); 
        $month_name  = strtolower(date('M'));  // Report submited Month  
        foreach ($Department as $value) {
            //print_r( $value->hod_email);
            if (isset($value->hod_email)) {
                $phpMail = new PHPMailer();
                $message = "";
                $phpMail->AddAddress($value->hod_email,  $value->hod_name);
                //$phpMail->AddAddress('sayed@ssgbd.com',  $value->hod_name);
                //$phpMail->AddCC('khushbu@ssgbd.com', "System CC");
                $phpMail->AddCC("shahidul.alam@ssgbd.com","Syed Shahidul Alam");
                $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
                $phpMail->AddCC('tanim.hr@ssgbd.com', "System CC");
                $phpMail->AddCC("tasnim.tabassum@ssgbd.com","Tasnim Tabassum");
                $phpMail->AddCC('sayed@ssgbd.com', "System CC");
                $phpMail->AddCC("mohammd.karim@ssgbd.com","Mohammd Karim");
                foreach ($value->ccmail as $key => $ccmail) {
                    if (isset($ccmail->ad_mail)) {
                        $phpMail->AddCC($ccmail->ad_mail, "System CC");
                    }
                } 
                $nextmonth = "";
                $data['nextmonth'] = $end_date; 
                $data['contena'] = '<p>'. $previous_date . ' Monthly KPI Achievement and Summary Report Panel will close on 5th ' . $start_date . '. Please update and submit on due time.
                </br> 
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
                $phpMail->Subject = "Monthly Achievement and Summary Report Alert";
                $phpMail->Body = $msg;
                $phpMail->SMTPAuth = false;

                if (!$phpMail->Send()) {
                    echo "Message could not be sent.";
                    echo "Mailer Error: " . $phpMail->ErrorInfo;
                    // exit;
                }
            } 
        }
        echo 'Success....!!';
        //}
    }
}
