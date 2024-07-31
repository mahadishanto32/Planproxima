<?php

namespace App\Imports;


use App\Models\MOS;
use App\Models\User;
use App\Models\MosData;
use App\Models\MosFeadback;
use App\Models\MOSAchievementPermission;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserKPIMOSAchivImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // $product = Product::where('material_code',$row['products_code'])->first(); 

        if ($row && $row['month']) {


            $data = MosData::select('k_r_a_s.user_id', 'k_r_a_s.dept_id', 'k_r_a_s.dept_id')
                ->where('mos_datas.mos_id', $row['mos_id'])
                ->where('mos_datas.type', 'achievement')
                ->join('m_o_s', 'm_o_s.id', 'mos_datas.mos_id')
                ->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id')
                ->first();


            $permission = MOSAchievementPermission::where('mos_id', $row['mos_id'])->first();
            // print_r($permission);

            // exit(); 
            $month_code = mb_substr($row['month'], 0, 3);



           if (($permission->$month_code == 1 && $permission->request_status == 2) && ($permission->end_date >= date('Y-m-d'))) {

                $valueTarget[$row['month']] =  $row['target'];
                $updat = MosData::where('mos_id', $row['mos_id'])
                    ->where('type', 'target')
                    ->update($valueTarget);

                // print_r($month_code); 
                // exit(); 
                // if($permission->)
                //valueAchiv
                // if($data->$row['month'].'_status') ==
                $valueAchiv[$row['month']] =  $row['achiv'];
                $updat = MosData::where('mos_id', $row['mos_id'])
                    ->where('type', 'achievement')
                    ->update($valueAchiv);


                $date = ($row['year'] == date('Y')) ? date("Y-m-d") : $row['year'] . '-12-31';
                $dateMonth = strtotime($row['month']);
                $dateMonthId = date("m", $dateMonth);

                $feed = array(
                    'mos_id' =>  $row['mos_id'],
                    'user_id' => $data->user_id,
                    'dept_id' => $data->dept_id,
                    'date' => $date,
                );
                $feed['msg'] = $row['comment'] ? $row['comment']  : '';
                $feed['fmonth'] = $dateMonthId;

                if (MosFeadback::where(['fmonth' => $dateMonthId, 'mos_id' => $row['mos_id']])->count() == 0) {
                    MosFeadback::create($feed);
                } else {
                    MosFeadback::where(['fmonth' => $dateMonthId, 'mos_id' => $row['mos_id']])->update(['msg' => $feed['msg']]);
                }
            }

            // $valueTarget[$row['month']] =  $row['target'];
            // $updat = MosData::where('mos_id', $row['mos_id'])
            //     ->where('type', 'target')
            //     ->update($valueTarget);
            return $data;
        }
    }

    public function collection()

    {

        return collect($this->data);
    }

    public function headings(): array

    {

        return [

            'ID',

            'Name',

            'Email',

        ];
    }
}
