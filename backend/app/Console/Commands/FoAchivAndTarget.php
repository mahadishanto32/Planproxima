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
use App\Http\Controllers\API\MOSAPIController;
use URL ;
use Illuminate\Http\Request;

class FoAchivAndTarget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:fo_achiv_target';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fo Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MOSAPIController $mos)
    {
        parent::__construct();
        $this->mos = $mos ;
    }
    // public function __construct(PayrollHelper $payroll_helper)
    // {
    //   parent::__construct();
    //   $this->payroll_helper = $payroll_helper;
    // }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {
        $base_url  =   URL::to("/"); 

        $users = User::select('employee_id' , 'designation')
        ->where('status',1)
        //->whereIn('employee_id', [7741])
        ->whereIn('dept_id', [1, 40, 41 , 42, 124 ])
        // ->whereNotIn('designation', ['TSM', 'RSM', 'SM',''])
        ->whereIn('role_id',[7,10])
        ->whereIn('designation', ['Field Officer', 'Senior Field Officer', 'Territory Sales Manager', 'Junior Territory Sales Manager', 'Regional Sales Manager'])
        ->get();
 
       
        foreach ($users as $key => $value) {
            // print_r($value->employee_id);
            // exit();
            # code...
          
            // $request->year = date('Y');
            $year = [2022, 2023];
            foreach ($year as $key => $s_year) {
                $request->merge(['year' => $s_year]);
                $request->merge(['foid' => $value->employee_id]);          
                $request->merge(['type' => $value->designation]);          
                $this->mos->fo_performance_month_wise($request); 
            }
        }
        echo  'Done' ;
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
