<?php

namespace App\Exports;

use App\Models\User;
use App\Models\MOS;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB, Auth;
use DateTime;

class UsersAchivExport implements FromCollection, WithHeadings
{
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function collection()
    {
        $user_id = '';
        if ($this->data['user_id']) {
            $user_id = $this->data['user_id'];
        } else {
            $user_data = Auth::user();
            $user_id =   $user_data->id;
        }

        $user = User::find($user_id);
        $dt1 = new DateTime();
        $today = $dt1->format("Y-m-d");
        $dt2 = new DateTime("-1 month");
        $month = $dt2->format("F");
        $month = strtolower($month);

        $query =  MOS::select(
            'mos_datas.mos_id',
            'k_r_a_s.year',
            DB::raw($user->employee_id ? $user->employee_id :  $user->email . ' as employee_id'),
            'k_r_a_s.kra_name',
            'k_p_i_s.rep_id',
            'k_p_i_s.kpi_name',
            'm_o_s.mos_name',
            'm_o_s.weightage',
            DB::raw('mos_datas.' . $month . ' as target'),
            DB::raw('"0" as achiv'),
            DB::raw('"' . $month . '" as month'),
            DB::raw('"" as comment'),
        );
        $query->join('k_p_i_s', 'k_p_i_s.id', 'm_o_s.kpi_id');
        $query->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id');
        $query->leftjoin('users', 'users.id', 'k_r_a_s.user_id');
        //$query->leftJoin('mos_datas' , 'mos_datas.mos_id' ,'m_o_s.id'); 
        //$query->where('mos_datas.type','target');
        $query->leftJoin('mos_datas', function ($join) {
            $join->on('mos_datas.mos_id', '=', 'm_o_s.id')
                ->where('mos_datas.type', 'target');
        });
        if ($user->role_id == 5) {
            $query->where('k_r_a_s.dept_id', $user->dept_id);
            $query->where('k_r_a_s.role_id', 5);
        } else {
            $query->where('k_r_a_s.user_id', $this->data['user_id']);
        }

        $query->where('k_r_a_s.year', $this->data['year']);
        $result = $query->get();
        return $result;
    }
    public function headings(): array

    {

        return [
            'mos_id',
            'year',
            'employee_id',
            'kra_name',
            'kpi_reference_id',
            'kpi_name',
            'mos_name',
            'mos_weightage',
            'target',
            'achiv',
            'month',
            'comment'
        ];
    }
}
