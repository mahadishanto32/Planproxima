<?php

namespace App\Exports;

use App\Models\User;
use App\Models\MOS;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB,Auth;
class UsersMosExport implements FromCollection , WithHeadings
{
    private $data; 

    public function __construct(array $data = [])
    {
        $this->data = $data; 
    }

    public function collection()
    {
        $user_data_check = User::find($this->data['user_id']);
        $user_id = ''; 
        if($this->data['user_id']){
            $user_id = $this->data['user_id'];
        }else{
            $user_data = Auth::user();
            $user_id =   $user_data->id ; 
        }

        if($this->data['all_check']){
            $user = User::Where('dept_id', $this->data['dept_id'])
            ->where('role_id' , '!=' , 5)->get();
        }else{
            $user = User::find($user_id);
        }   
        
        if($this->data['all_check']){
            $employee_code = DB::raw( $user_data_check->employee_id ? $user_data_check->employee_id :  $user_data_check->email.' as employee_id');
        }else{
          $employee_code = DB::raw( $user->employee_id ? $user->employee_id :  $user->email.' as employee_id');
        }
        if($this->data['year'] > 2023){
            $query =  MOS::select(
                'k_r_a_s.year',  
                'users.employee_id',
                'k_r_a_s.kra_name',
                'k_p_i_s.rep_id as kpi_reference_id',
                'm_o_s.rep_per as reference_per',
                'k_p_i_s.kpi_name', 
                'm_o_s.mos_name' , 
                'm_o_s.weightage',
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.july, NULL)) AS target_jul"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.july, NULL)) AS achv_jul"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.august, NULL)) AS target_aug"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.august, NULL)) AS achv_aug"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.september, NULL)) AS target_sep"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.september, NULL)) AS achv_sep"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.october, NULL)) AS target_oct"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.october, NULL)) AS achv_oct"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.november, NULL)) AS target_nov"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.november, NULL)) AS achv_nov"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.december, NULL)) AS target_dec"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.december, NULL)) AS achv_dec"),                
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.january, NULL)) AS target_jan"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.january, NULL)) AS achv_jan"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.february, NULL)) AS target_feb"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.february, NULL)) AS achv_feb"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.march, NULL)) AS target_mar"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.march, NULL)) AS achv_mar"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.april, NULL)) AS target_apr"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.april, NULL)) AS achv_apr"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.may, NULL)) AS target_may"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.may, NULL)) AS achv_may"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.june, NULL)) AS target_jun"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.june, NULL)) AS achv_jun")            
            );
        }else{
            $query =  MOS::select(
                'k_r_a_s.year',  
                'users.employee_id',
                'k_r_a_s.kra_name',
                'k_p_i_s.rep_id as kpi_reference_id',
                'm_o_s.rep_per as reference_per',
                'k_p_i_s.kpi_name', 
                'm_o_s.mos_name' , 
                'm_o_s.weightage',
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.january, NULL)) AS target_jan"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.january, NULL)) AS achv_jan"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.february, NULL)) AS target_feb"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.february, NULL)) AS achv_feb"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.march, NULL)) AS target_mar"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.march, NULL)) AS achv_mar"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.april, NULL)) AS target_apr"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.april, NULL)) AS achv_apr"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.may, NULL)) AS target_may"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.may, NULL)) AS achv_may"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.june, NULL)) AS target_jun"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.june, NULL)) AS achv_jun"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.july, NULL)) AS target_jul"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.july, NULL)) AS achv_jul"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.august, NULL)) AS target_aug"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.august, NULL)) AS achv_aug"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.september, NULL)) AS target_sep"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.september, NULL)) AS achv_sep"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.october, NULL)) AS target_oct"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.october, NULL)) AS achv_oct"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.november, NULL)) AS target_nov"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.november, NULL)) AS achv_nov"),
                DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.december, NULL)) AS target_dec"),
                DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.december, NULL)) AS achv_dec")            
            );
        }
        $query->join('k_p_i_s', 'k_p_i_s.id', '=', 'm_o_s.kpi_id')
        ->join('k_r_a_s', 'k_r_a_s.id', '=', 'm_o_s.kra_id')
        ->join('users', 'users.id', '=', 'k_r_a_s.user_id')
        ->leftJoin('mos_datas', function ($join) {
            $join->on('mos_datas.mos_id', '=', 'm_o_s.id')
                ->whereIn('mos_datas.type', ['target', 'achievement']);
        });


        if($user_data_check->role_id == 5 ){
            if($this->data['all_check']){
                $query->where('k_r_a_s.dept_id',$this->data['dept_id']);
                $query->where('k_r_a_s.role_id','!=',5);
            }else{
                $query->where('k_r_a_s.dept_id',$user->dept_id);
                $query->where('k_r_a_s.role_id',5);
            }
        }else{
            $query->where('k_r_a_s.user_id',$this->data['user_id']);
        }

        $query->where('k_r_a_s.year',$this->data['year']);
        $query->groupBy(
            'k_r_a_s.year',
            'k_r_a_s.kra_name',
            'm_o_s.id',
            'm_o_s.rep_per',
            'm_o_s.mos_name'
        );
        $query->orderby('users.employee_id');
        $result = $query->get(); 

            if($query->count() > 0 || $user->role_id == 5 ){
                return $result ;
            }else{
              //`january`, `february`, `march`, `april`, `may`, `june`, `july`, `august`, `september`, `october`, `november`, `december`,
                if($user->role_id == 7){
                    $userWing = User::where('role_id', 6 )->where('wing_id', $user->wing_id )->first(); 
                    $query =  MOS::select('k_r_a_s.year',  
                    DB::raw($this->data['user_id']." as employee_id"),
                    'k_r_a_s.kra_name', 
                    'm_o_s.id as kpi_reference_id',    
                    'm_o_s.rep_per as reference_per',
                    'm_o_s.mos_name as kpi_name',  
                    DB::raw("'0' as mos_name"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.january, NULL)) AS target_jan"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.january, NULL)) AS achv_jan"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.february, NULL)) AS target_feb"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.february, NULL)) AS achv_feb"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.march, NULL)) AS target_mar"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.march, NULL)) AS achv_mar"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.april, NULL)) AS target_apr"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.april, NULL)) AS achv_apr"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.may, NULL)) AS target_may"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.may, NULL)) AS achv_may"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.june, NULL)) AS target_jun"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.june, NULL)) AS achv_jun"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.july, NULL)) AS target_jul"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.july, NULL)) AS achv_jul"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.august, NULL)) AS target_aug"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.august, NULL)) AS achv_aug"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.september, NULL)) AS target_sep"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.september, NULL)) AS achv_sep"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.october, NULL)) AS target_oct"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.october, NULL)) AS achv_oct"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.november, NULL)) AS target_nov"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.november, NULL)) AS achv_nov"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.december, NULL)) AS target_dec"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.december, NULL)) AS achv_dec")
                );
                $query->join('k_p_i_s', 'k_p_i_s.id', '=', 'm_o_s.kpi_id')
                ->join('k_r_a_s', 'k_r_a_s.id', '=', 'm_o_s.kra_id')
                ->join('users', 'users.id', '=', 'k_r_a_s.user_id')
                ->leftJoin('mos_datas', function ($join) {
                    $join->on('mos_datas.mos_id', '=', 'm_o_s.id')
                        ->whereIn('mos_datas.type', ['target', 'achievement']);
                });
                    //$query->where('mos_datas.type','target');
                    $query->where('k_r_a_s.user_id',$userWing->id);
                    $query->where('k_r_a_s.year',$this->data['year']);
                    $query->groupBy(
                        'k_r_a_s.year',
                        'k_r_a_s.kra_name',
                        'm_o_s.id',
                        'm_o_s.rep_per',
                        'm_o_s.mos_name'
                    );                    
                    $result = $query->get();  
                    
                    return $result ;
                }else if($user->role_id == 6){
                    $userDept = User::where('role_id', 5 )->where('dept_id', $user->dept_id )->first(); 
                    // print_r($userDept ) ;
                    // exit();
                    $query =  MOS::select('k_r_a_s.year',  
                    DB::raw($this->data['user_id']." as employee_id"), 
                    'k_r_a_s.kra_name',
                    'm_o_s.id as kpi_reference_id',
                    'm_o_s.rep_per as reference_per',
                    'm_o_s.mos_name as kpi_name', 
                    DB::raw("'0' as mos_name"),  
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.january, NULL)) AS target_jan"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.january, NULL)) AS achv_jan"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.february, NULL)) AS target_feb"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.february, NULL)) AS achv_feb"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.march, NULL)) AS target_mar"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.march, NULL)) AS achv_mar"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.april, NULL)) AS target_apr"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.april, NULL)) AS achv_apr"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.may, NULL)) AS target_may"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.may, NULL)) AS achv_may"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.june, NULL)) AS target_jun"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.june, NULL)) AS achv_jun"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.july, NULL)) AS target_jul"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.july, NULL)) AS achv_jul"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.august, NULL)) AS target_aug"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.august, NULL)) AS achv_aug"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.september, NULL)) AS target_sep"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.september, NULL)) AS achv_sep"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.october, NULL)) AS target_oct"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.october, NULL)) AS achv_oct"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.november, NULL)) AS target_nov"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.november, NULL)) AS achv_nov"),
                    DB::raw("MAX(IF(mos_datas.type = 'target', mos_datas.december, NULL)) AS target_dec"),
                    DB::raw("MAX(IF(mos_datas.type = 'achievement', mos_datas.december, NULL)) AS achv_dec")
                    );
                    $query->join('k_p_i_s' , 'k_p_i_s.id' ,'m_o_s.kpi_id'); 
                    $query->join('k_r_a_s' , 'k_r_a_s.id' ,'m_o_s.kra_id'); 
                    $query->join('users' , 'users.id' ,'k_r_a_s.user_id'); 
                    //$query->join('mos_datas' , 'mos_datas.mos_id' ,'m_o_s.id'); 
                    $query->join('k_p_i_s', 'k_p_i_s.id', '=', 'm_o_s.kpi_id')
                    ->join('k_r_a_s', 'k_r_a_s.id', '=', 'm_o_s.kra_id')
                    ->join('users', 'users.id', '=', 'k_r_a_s.user_id')
                    ->leftJoin('mos_datas', function ($join) {
                        $join->on('mos_datas.mos_id', '=', 'm_o_s.id')
                            ->whereIn('mos_datas.type', ['target', 'achievement']);
                    });
                    $query->where('k_r_a_s.user_id',$userDept->id);
                    //$query->where('mos_datas.type','target');
                    $query->where('k_r_a_s.year',$this->data['year']);
                    $query->groupBy(
                        'k_r_a_s.year',
                        'k_r_a_s.kra_name',
                        'm_o_s.id',
                        'm_o_s.rep_per',
                        'm_o_s.mos_name'
                    );                     
                    $result = $query->get();  
                    return $result ;
                }  
        }
        
    }
    public function headings() :array
    {
        if($this->data['year'] > 2023){
            return [ 
                'year', 
                'employee_id', 
                'kra_name',  
                'kpi_reference_id', 
                'reference_per',
                'kpi_name', 
                'mos_name',
                'mos_weightage',  
                'target_jul',
                'achv_jul',
                'target_aug',
                'achv_aug',
                'target_sep',
                'achv_sep',
                'target_oct',
                'achv_oct',
                'target_nov',
                'achv_nov',
                'target_dec',
                'achv_dec',
                'target_jan',
                'achv_jan',
                'target_feb',
                'achv_feb',
                'target_mar',
                'achv_mar',
                'target_apr',
                'achv_apr',
                'target_may',
                'achv_may',
                'target_jun',
                'achv_jun',                
            ];
        }else{
            return [ 
                'year', 
                'employee_id', 
                'kra_name',  
                'kpi_reference_id', 
                'reference_per',
                'kpi_name', 
                'mos_name',
                'mos_weightage',  
                'target_jan',
                'achv_jan',
                'target_feb',
                'achv_feb',
                'target_mar',
                'achv_mar',
                'target_apr',
                'achv_apr',
                'target_may',
                'achv_may',
                'target_jun',
                'achv_jun',
                'target_jul',
                'achv_jul',
                'target_aug',
                'achv_aug',
                'target_sep',
                'achv_sep',
                'target_oct',
                'achv_oct',
                'target_nov',
                'achv_nov',
                'target_dec',
                'achv_dec',
            ];
        }
    }
}
