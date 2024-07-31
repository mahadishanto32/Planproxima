<?php

namespace App\Exports;

use App\Models\User;
use App\Models\MOS;
use App\Models\TourEntry;
use App\Models\DailySchedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Resources\TourPlanResource;
use DB , Auth;
class TourUsersTaskExport implements FromCollection , WithHeadings
{
    private $data; 

    public function __construct(array $data = [])
    {
        $this->data = $data; 
    }

    public function collection()
    {
        $user_data = Auth::user();
        $designation = $this->data['designation'];
        $hq = $this->data['hq'];
        $user_data = Auth::user();
        //return $this->sendResponse($user_data, 'Tour Entries retrieved successfully');
        $emp_code = $this->data['emp_code'];
        $division_id = $this->data['division_id'];
        $business_type = $this->data['business_type'];
 
        $tourViewQuery = TourEntry::with(['touruser' => function($query){
                $query->select('id','business_type','division_id','user_id');
            }])
            ->whereHas('touruser', function($query) use ($division_id, $business_type)  {
                $query->when($division_id, function($query, $division_id){
                    if($division_id !='all'){
                        return $query->where('division_id', $division_id);
                    }                    
                })->when($business_type, function($query, $business_type){
                    if($business_type !='all'){
                        return $query->where('business_type', $business_type);
                    }
                });  
            })->select( 'tour_entries.*','users.designation','users.employee_id','users.name')
            ->when($designation, function($query, $designation){
                if($designation !='all'){
                    return $query->where('users.designation', $designation);
                }                
            })
            ->when($emp_code, function($query, $emp_code){
                return $query->where('users.employee_id', $emp_code);
            })
            ->when($hq, function($query, $hq){
                return $query->where('users.id', $hq);
            })
            ->join('users', 'users.id', 'tour_entries.user_id'); 
        if ($this->data['start_date'] != '' and $this->data['end_date'] != '') {
            $start_date = date('Y-m-d', strtotime($this->data['start_date']));
            $end_date = date('Y-m-d', strtotime($this->data['end_date']));
            $tourViewQuery->whereBetween('tour_entries.date', array($start_date, $end_date));
        } 
        if (!$this->data['hq'] && !$division_id && !$business_type && !$designation) {
            if (($user_data->designation == 'Director' || $user_data->designation == "MD's")) {
                $tourViewQuery->whereIn('users.designation', ['AGM', 'GM']);
            } elseif ($user_data->designation == 'Sales Admin' && $user_data->role_id == 5) {
                $tourViewQuery->whereIn('users.designation', ['AGM']);
            } elseif ($user_data->designation == 'GM') {
                $tourViewQuery->whereIn('users.designation', ['AGM', 'GM']);
                $tourViewQuery->where('users.dept_id', $user_data->dept_id);
            } elseif ($user_data->role_id == 9) { 
                $tourViewQuery->where('users.dept_id', $user_data->dept_id);
            } elseif ($user_data->designation == 'SM' || $user_data->designation == 'AGM' || $user_data->designation == 'ASM' || $user_data->designation == 'DSM' || $user_data->designation == 'ADSM' || $user_data->designation == 'RSM') {
                $tourViewQuery->where(function ($query) use ($user_data) {
                    $query->where('tour_entries.user_id', $user_data->id)
                        ->orwhere('tour_entries.hos', '=', $user_data->id)
                        ->orwhere('tour_entries.sm', '=', $user_data->id)
                        ->orwhere('tour_entries.asm', '=', $user_data->id)
                        ->orwhere('tour_entries.adsm', '=', $user_data->id)
                        ->orwhere('tour_entries.dsm', '=', $user_data->id)
                        ->orwhere('tour_entries.rsm', '=', $user_data->id); 
                 
                });
            } else {
                $tourViewQuery->where('tour_entries.user_id', $user_data->id);
            }            
        }
        $tourViewQuery->orderBy('tour_entries.id', 'DESC');
        $tourViewQuery->limit(600);
        //echo $data_return = $tourViewQuery->toSql();
        //exit;
        $data_return = $tourViewQuery->get();
        //return response()->json($data_return);
        $data_return  = TourPlanResource::collection($data_return);
        return $data_return;
    }
    public function headings() :array{
        return [ 
            "id",
            "user_id" ,
            "point",
            "route_name",
            "objectives" ,
            "specia_objective",
            "contactperson",
            "hq",
            "remarks",
            "feedback",
            "status",
            "work_station",
            "work_with",
            "date", 
            "approval",
            "created_at",
            "updated_at",
            "deleted_at",
            "approval_count",
            'objective_id',
            "last_approval_date",
            "designation",
            "employee_id",
            "name",  
            'touruser',
        ];
    }
}
