<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Department;
use App\Models\MOS;
use Auth;
use DB;
use Illuminate\Http\Request;

/**
 * Class DepartmentController
 * @package App\Http\Controllers\API
 */

class DashboardAPIController extends AppBaseController
{
    public function index(Request $request)
    {
        $user_data = Auth::user();
        if ($user_data->role_id == 5 || $user_data->role_id == 6 || $user_data->role_id == 7) {
            $request['dept_id'] = $user_data->dept_id;
        }

        $achievement_biulder = MOS::select(DB::Raw('SUM(january) as january, SUM(february) as february
        , SUM(march) as march, SUM(april) as april, SUM(may) as may, SUM(june) as june, SUM(july) as july
        , SUM(august) as august, SUM(september) as september, SUM(october) as october, SUM(november) as november
        , SUM(december) as december'))
            ->join('mos_datas', 'mos_datas.mos_id', 'm_o_s.id')
            ->join('k_r_a_s','k_r_a_s.id','m_o_s.kra_id');
        if ($request->dept_id) {
            $achievement_biulder->where('m_o_s.dept_id', $request->dept_id);
        }
        if ($request->year) {
            $achievement_biulder->where('m_o_s.year', $request->year);
        }
        if( $request->wing_id){
            $achievement_biulder->where('k_r_a_s.wing_id', $request->wing_id);
        }
        if($request->user_id){
            $achievement_biulder->where('k_r_a_s.user_id', $request->user_id);
        }
        $achievement = $achievement_biulder->where('mos_datas.type', 'achievement')->first();
        // return $this->sendResponse($achievement, 'request retrieved successfully');
        $target_biulder = MOS::select(DB::Raw('SUM(january) as january, SUM(february) as february
        , SUM(march) as march, SUM(april) as april, SUM(may) as may, SUM(june) as june, SUM(july) as july
        , SUM(august) as august, SUM(september) as september, SUM(october) as october, SUM(november) as november
        , SUM(december) as december'))
        ->join('k_r_a_s','k_r_a_s.id','m_o_s.kra_id')
        ->join('mos_datas', 'mos_datas.mos_id', 'm_o_s.id');
        if ($request->dept_id) {
            $target_biulder->where('m_o_s.dept_id', $request->dept_id);
        }

        if ($request->year) {
            $target_biulder->where('m_o_s.year', $request->year);
        }
        if($request->wing_id){
            $target_biulder->where('k_r_a_s.wing_id', $request->wing_id);
        }
        if($request->user_id){
            $target_biulder->where('k_r_a_s.user_id', $request->user_id);
        }
        
        $target = $target_biulder->where('mos_datas.type', 'target')->first();

        ///$data['target'] = $target;
        $data['target'] = [
            round($target->january,2),
            round($target->february,2),
            round($target->march,2),
            round($target->april,2),
            round($target->may,2),
            round($target->june,2),
            round($target->july,2),
            round($target->august,2),
            round($target->september,2),
            round($target->october,2),
            round($target->november,2),
            round($target->december,2),
        ];
        $january_achv = ($achievement->january > 0 ? $achievement->january : $target->january);

        $february_achv  = ($achievement->february > 0 ? $achievement->february : $target->february);
        $march_achv     = ($achievement->march > 0 ? $achievement->march : $target->march);
        $april_achv     = ($achievement->april > 0 ? $achievement->april : $target->april);
        $may_achv       = ($achievement->may > 0 ? $achievement->may : $target->may);
        $june_achv      = ($achievement->june > 0 ? $achievement->june : $target->june);
        $july_achv      = ($achievement->july > 0 ? $achievement->july : $target->july);
        $august_achv    = ($achievement->august > 0 ? $achievement->august : $target->august);
        $september_achv = ($achievement->september > 0 ? $achievement->september : $target->september);
        $october_achv   = ($achievement->october > 0 ? $achievement->october : $target->october);
        $november_achv  = ($achievement->november > 0 ? $achievement->november : $target->november);
        $december_achv  = ($achievement->december > 0 ? $achievement->december : $target->december);

        $data['achievement'] = [
            round($achievement->january,2),
            round($achievement->february,2),
            round($achievement->march,2),
            round($achievement->april,2),
            round($achievement->may,2),
            round($achievement->june,2),
            round($achievement->july,2),
            round($achievement->august,2),
            round($achievement->september,2),
            round($achievement->october,2),
            round($achievement->november,2),
            round($achievement->december,2),
        ];

        $achievement_with_remaining = [
            round($january_achv,2),
            round($february_achv,2),
            round($march_achv,2),
            round($april_achv,2),
            round($may_achv,2),
            round($june_achv,2),
            round($july_achv,2),
            round($august_achv,2),
            round($september_achv,2),
            round($october_achv,2),
            round($november_achv,2),
            round($december_achv,2),

        ];
        $color     = ['#8601af', '#b5179e', '#be00cc', '#7209b7', '#9600a0', '#4c0052', '#8601af', '#b5179e', '#be00cc', '#7209b7', '#9600a0', '#4c0052'];
        $monthArr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $total_rem = 0;
        $total_achv = 0;

        $data['achievement_with_remaining'] = [];
        $data['color'] = [];
        $data['monthname'] = [];
        
        $month = ((int) $request->year == (int) date('Y'))?(int) date('m'):12;
        for ($l = 0; $l < count($achievement_with_remaining); $l++) {
            if ($month > $l) {
                array_push($data['achievement_with_remaining'], $achievement_with_remaining[$l]);
                array_push($data['monthname'], $monthArr[$l]);
                array_push($data['color'], $color[$l]);
                $total_achv += $achievement_with_remaining[$l];
            } else {
                //echo $achievement_with_remaining[$l]. "||";
                $total_rem += $achievement_with_remaining[$l];
                //array_push($data['monthname'], $monthArr[$l]);
                
            }
        }
        // return $this->sendResponse([(int) date('m')], 'Departments retrieved successfully');
        if($month < count($achievement_with_remaining)){
            array_push($data['monthname'], 'Remaining');
            array_push($data['color'], '#b3b8be');
            array_push($data['achievement_with_remaining'], $total_rem);

        }

        $data['performance_value'] = [round($total_achv,2),round($total_rem,2)];
        

        //print_r($data['achievement_with_remaining']);exit;
        // echo $data['achievement_with_remaining'][8];exit;

        // $data['achievement'] = [10,8,9,11,5,0,0,0,0,0,0,0];
        //$data['achievement'] = $achievement;

        return $this->sendResponse($data, 'Departments retrieved successfully');
    }

}
