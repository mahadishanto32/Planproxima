<?php

namespace App\Console\Commands;
use App\Http\Controllers\API\MOSAPIController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
class FoTsmAchivAndTargetCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:fotsmcheck';

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
    public function __construct(MOSAPIController $mos)
    {
        parent::__construct();
        $this->mos = $mos ;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {
        // Send a GET request to the specified URL
        $tsm_response = Http::get('https://ssforcenew.ssgbd.com/api/user-fo-tsm?user_type=tsm');

        // Get the response body as a string
        $body = $tsm_response->json();
        foreach ($body as $key => $value) {
            $year = [2022, 2023];
            foreach ($year as $key => $s_year) {
                $request->merge(['year' => $s_year]);
                $request->merge(['foid' => $value->Employee_Id]);          
                $request->merge(['type' => 'tsm']);          
                $this->mos->fo_performance_month_wise($request); 
            }            
        }


        $fo_response = Http::get('https://ssforce.ssgbd.com/api/user-fo-tsm?user_type=fo');

        // Get the response body as a string
        $body = $tsm_response->json();
        foreach ($body as $key => $value) {
            $year = [2022, 2023];
            foreach ($year as $key => $s_year) {
                $request->merge(['year' => $s_year]);
                $request->merge(['foid' => $value->Employee_Id]);          
                $request->merge(['type' => 'fo']);          
                $this->mos->fo_performance_month_wise($request); 
            }            
        }        

    }
}
