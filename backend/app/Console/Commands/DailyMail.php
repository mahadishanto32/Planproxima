<?php

namespace App\Console\Commands;

use App\Models\MonthlyDateRange;
use App\Models\Department;
use App\Models\DepartmentSetting;
use Illuminate\Console\Command;
use App\Http\Resources\TaskReportResource;
use App\Models\DailySchedule;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
use App\Models\User;

use App\Models\DepartmentWeekend;
use App\Models\WeekendGroupAssign;
use Illuminate\Support\Facades\Http;

class DailyMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Mail Alert';

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
        $to_date = date('Y-m-d');

        $userList = User::select(
            'users.id',
            'users.employee_id',
            'users.name',
            'users.phone',
            'users.designation',
            'users.name as user_name',
            'departments.name as dept_name',
            'users.dept_id',
            'departments.hod_email'
        )
        ->where('users.role_id', 5)
        ->join('departments', 'departments.id', 'users.dept_id')
        ->where('departments.status', 1)
        // ->where('departments.id', 8)
        ->where('users.status', 1)
        ->where('departments.mail_allow', 1)
        ->orderBy('departments.name', 'ASC')
        ->get();

        $WeekendGroupAssign = WeekendGroupAssign::join('department_wekend', 'department_wekend.group_id', 'weekendg_assign.group_id')
        ->select('weekendg_assign.department_id', 'department_wekend.*')
        ->get()->groupBy('department_id');

        //$data_return = TaskReportResource::collection($userList);
        foreach ($userList as $key => $value) {
            $user_id = $value->id;
            $weekendCheck =  isset($WeekendGroupAssign[$value->dept_id]) ? $WeekendGroupAssign[$value->dept_id] : [];
            $weekendCheck = collect($weekendCheck)->groupBy('date');
            $weekendCheckData = isset($weekendCheck[date('Y-m-d')]) ? 1 : 0;

            $taskCount = DailySchedule::where('user_id', $user_id)
            ->where('date', $to_date)
            ->count();
            
            if (($taskCount == 0) && ($weekendCheckData == 0)) {
                if (isset($value->hod_email)) {

                    $phpMail = new PHPMailer();
                    $message = "";
                    
                    //Note: Check Code
                    // $phpMail->AddAddress('raihan.bhuyian@ssgbd.com', "Check System By IT");
                    // //$phpMail->AddAddress('sayed@ssgbd.com',  $value->hod_name);
                    // $phpMail->AddCC('sayed@ssgbd.com', "Check System By IT");
                    // $phpMail->AddCC('tasnim.tabassum@ssgbd.com', "Check System By IT");
                    // $phpMail->AddReplyTo("rashed.zzaman@ssgbd.com", "Check System By IT");
                    //Note: Check Code End

                    //Note: Live Code
                    $phpMail->AddAddress($value->hod_email,  $value->name);
                    $phpMail->AddCC("shahidul.alam@ssgbd.com", "Syed Shahidul Alam");
                    $phpMail->AddCC('rashed.zzaman@ssgbd.com', "System CC");
                    $phpMail->AddCC("mohammd.karim@ssgbd.com", "Mohammd Karim");

                    $phpMail->AddCC('tanim.hr@ssgbd.com', "System CC");
                    $phpMail->AddCC("tasnim.tabassum@ssgbd.com", "Tasnim Tabassum");
                    $phpMail->AddCC('sayed@ssgbd.com', "System CC");
                    $phpMail->AddCC('raihan.bhuyian@ssgbd.com', "System CC");
                    $phpMail->AddCC('tasnim.tabassum@ssgbd.com', "System CC");
                    $phpMail->AddReplyTo("management.desk@ssgbd.com", "Check Mail");
                    //Note: Live Code End

                    $nextmonth = "";
                    $data['nextmonth'] = '';
                    $data['contena'] = 'Soft Reminder!  </br> 
                    You are requested to update your today\'s work schedule in BPT.
                    </br> 
                    Regards,
                    Team BPT 
                    ';
                    $message = view('mail.default_theme')->with(['data' => $data]);

                    $user = "Management Desk";
                    $user_email = "management.desk@ssgbd.com";

                    $msg = nl2br($message);

                    $phpMail->FromName = $user;
                    $phpMail->From = "management.desk@ssgbd.com";
                    $phpMail->Sender = $user_email;
                    $phpMail->IsHTML(true);
                    $phpMail->Host = "mail.ssgbd.com:25";
                    $phpMail->IsSMTP();
                    $phpMail->Mailer  = "smtp";
                    $phpMail->Subject = "Daily Task Alert";
                    $phpMail->Body = $msg;
                    $phpMail->SMTPAuth = false;

                    if (!$phpMail->Send()) {
                        echo "Message could not be sent.";
                        echo "Mailer Error: " . $phpMail->ErrorInfo;
                        // exit;
                    }
                }
                $phone = $value->phone;
                $smg = "আজকের কর্ম-পরিকল্পনা গুলো বিপিটি তে আপডেট করার জন্য আপনাকে অনুরোধ করা হলো।";
                // $response = $this->send($phone, $smg);
                $response = sendSms($phone, $smg);
            }
        }
    }

    public function sendSmsHere($phone, $smg)
    {
        $url = smsApiUrl();
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, [
            'username' => "IRbulbadmin",
            'password' => "*Ssg@2023",
            'apicode' => "1",
            'msisdn' => $phone,
            'countrycode' => "880",
            'cli' => "S.S.G",
            'messagetype' => "3",
            'message' => $smg,
            'messageid' => "0"
        ]);
        // echo "Status code: " . $response->status() . "\n";
        // echo "Response body: " . $response->body() . "\n";
        // exit;
        if ($response->ok()) {
            echo 'SMS sent successfully.';
        } else {
            $a = 'Failed to send SMS. Error message: ' . $response->body();
            print_r($a);
            // exit;
        }
    }

    public function send($contacts, $msg)
    { ////Note: Curl Configuration sms
        $api_key  = "C20016585b5d65039143f5.68321617";
        $senderid = 'Super Star';
        $URL      = "www.bangladeshsms.com/smsapi?api_key=" . urlencode($api_key) . "&type=text&contacts=" . urlencode($contacts) . "&senderid=" . urlencode($senderid) . "&msg=" . urlencode($msg);
        return $responses = $this->curlFunc($URL);
    }

    public static function curlFunc($url)
    { //Note: Curl Resoponce MSG
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => array("Content-Type: text/html; charset=utf-8"),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; rv:19.0) Gecko/20100101 Firefox/19.0',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSLVERSION => 3
        ));
        $output = curl_exec($ch);
        curl_close($ch);
        if (!$output) {
            $output = file_get_contents($url);
        }
        return $output;
    }
}
