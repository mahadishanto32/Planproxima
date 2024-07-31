<?php

namespace App\Console\Commands; 
use Illuminate\Console\Command;  
use App\Models\Department;  
use App\Models\TourEntry;
use Carbon\Carbon;     
use  DB;
use Illuminate\Support\Facades\Http;

//use App\Models\PriorityTask;  

class TourEntryAccuracy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tour_entry_accuracy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'tour_entry_accuracy';

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
        $date =  Carbon::now()->toDateString() ;   
        $tourViewQuery = TourEntry::where('date', $date)
                ->select('tour_entries.id','users.employee_id', 'tour_point_details.point_id',) 
                ->join('users', 'users.id', 'tour_entries.user_id')
                ->join('tour_point_details', 'tour_point_details.tour_id', 'tour_entries.id')
                ->where('tour_entries.route_accuracy', 0);        
                $result = $tourViewQuery->get();
        //dd( $result);
        foreach ($result as $key => $item) { 
            $url = 'https://ssforce.ssgbd.com/api/route_accuracy_sync?point_id='.$item->point_id.'&employee_id='.$item->employee_id; 
            $response = Http::get( $url);  
            if ($response->successful()) {
                $apiData = $response->json(); 
                if ($apiData['success']) { 
                     TourEntry::where('id', $date)->update([
                        'route_accuracy' => $item->id,  
                    ]);
                } 
            } 
            
        }
  

    }

 
    
}
