<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MonthlyDateRange;
use App\Models\Department;
use App\Models\User;
use App\Models\KRA;
use App\Models\DepartmentSetting;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP;
class AchievmentNotUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:achievements_not_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
  
        $currentMonth = date('F'); 
        $previousMonth = date('F', strtotime('-1 month'));
        $month =  strtolower($previousMonth);
        
        $currentYear = date('Y');
       
        $currentMonthNumber = date('n');
        if($currentMonthNumber > 6){
            $currentYear = $currentYear + 1 ;
        }  

    
 
        // $mosQuery = KRA::select('k_r_a_s.dept_id', 'k_r_a_s.user_id', 'k_r_a_s.year', 'users.ad_mail', 'users.name', 'users.employee_id', 'mos_datas.' . $month .' as achievement')
        // ->join('users', 'users.id', '=', 'k_r_a_s.user_id')
        // ->join('k_p_i_s', 'k_p_i_s.kra_id', '=', 'k_r_a_s.id')
        // ->join('m_o_s', 'm_o_s.kpi_id', '=', 'k_p_i_s.id')
        // ->join('mos_datas', 'mos_datas.mos_id', '=', 'm_o_s.id')
        // ->where('k_r_a_s.year', $currentYear)
        // ->whereIn('k_r_a_s.role_id', [6, 7, 8, 9, 10])
        // ->where('users.ad_mail', 'like', '%@ssgbd.com')
        // ->whereNotIn('users.designation', ['Field Officer', 'Senior Field Officer', 'Territory Sales Manager', 'Junior Territory Sales Manager', 'Regional Sales Manager'])
        // ->where('mos_datas.type', 'achievement')
        // ->where('users.dept_id',8)
       
        // ->groupBy('k_r_a_s.user_id')
        // ->whereRaw("(
        //     SELECT COUNT(id) 
        //     FROM mos_datas
        //     WHERE type = 'achievement'
        //     AND $month > 0
        // ) > 0") 
        // ->get()
        // ->toArray();

        $mosQuery = KRA::select('k_r_a_s.dept_id', 'k_r_a_s.user_id', 'k_r_a_s.year', 'users.ad_mail', 'users.name', 'users.employee_id', 'mos_datas.' . $month .' as total_achievement')
        ->join('users', 'users.id', '=', 'k_r_a_s.user_id')
        ->join('k_p_i_s', 'k_p_i_s.kra_id', '=', 'k_r_a_s.id')
        ->join('m_o_s', 'm_o_s.kpi_id', '=', 'k_p_i_s.id')
        ->join('mos_datas', 'mos_datas.mos_id', '=', 'm_o_s.id')
        ->where('k_r_a_s.year', $currentYear)
        ->whereIn('k_r_a_s.role_id', [6, 7, 8, 9, 10])
        ->where('users.ad_mail', 'like', '%@ssgbd.com')
        ->whereNotIn('users.designation', ['Field Officer', 'Senior Field Officer', 'Territory Sales Manager', 'Junior Territory Sales Manager', 'Regional Sales Manager'])
        ->where('mos_datas.type', 'achievement')
        //->where('users.dept_id', 8)
        ->groupBy('k_r_a_s.user_id')
        ->havingRaw('SUM(mos_datas.' . $month . ') < 1')
        ->get()
        ->toArray();
 
        foreach($mosQuery as $user){           
            if($user['total_achievement'] == 0){  
                if(isset($user['ad_mail'])){ 
                    try {
                        $phpMail = new PHPMailer();
                        $message = "";
                        $phpMail->AddAddress(str_replace(' ', '', $user['ad_mail']),  $user['name']);
                        
                        $phpMail->AddCC('sayed@ssgbd.com', "Abu Sayed ");
                    
                        $nextmonth = "";
                        $data['nextmonth'] = '';
                        $data['name'] = $user['name'];
                        $data['contena'] = 'Soft Reminder!  </br> 
                        You are requested to update your monthly achievement ('.$month.') in BPT.
                        </br> 
                        Regards,
                        Team BPT 
                        ';
                        $message = view('mail.not_updated_monthly_achievement')->with(['data' => $data]); 
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
                        $phpMail->Subject = "For not updated monthly achievement in BPT";
                        $phpMail->Body = $msg;
                        $phpMail->SMTPAuth = false; 
                        if (!$phpMail->Send()) {
                            throw new Exception("Message could not be sent.");
                        } 
                        $phpMail->ClearAddresses();
                        $phpMail->ClearAttachments(); 
                    } catch (Exception $e) {
                        continue ;
                        
                    }
                    
                }   
            } 
        }

 
    }
}
