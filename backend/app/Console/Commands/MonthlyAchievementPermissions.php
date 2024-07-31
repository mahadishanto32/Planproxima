<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MOSAchievementPermission;
class MonthlyAchievementPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'achievement:permission';

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
        $monthValues = [
            1 => 'jan',
            2 => 'feb',
            3 => 'mar',
            4 => 'apr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'aug',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dec',
        ];
        
        $startDate = date('Y').'-'.date('m').'-01';
        $endDate =  date('Y').'-'.date('m').'-05';
        $monthNumber = date('n');
    
        if($monthNumber > 2){
            $data = [
                'start_date' => $startDate,
                'end_date' => $endDate,
                $monthValues[$monthNumber-2] => 0,
                $monthValues[$monthNumber-1] => 1,
            ];

             
        }else{
            if($monthNumber == 1){
                $data = [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'nov' => 0,
                    'dec' => 1,
                ];                
            }else{
                $data = [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'dec' => 0,
                    'jan' => 1,
                ];                    
            }
        }
        $data['request_status'] = 2 ;
        $year  =  date('Y') ;
        if($monthNumber >  6 ){
            $year += 1 ;  
        }
        $responce = MOSAchievementPermission::where('year', '=',$year )
            ->update($data);
            
        return 'Update Successfullly............!!!!!!';
    }
}
