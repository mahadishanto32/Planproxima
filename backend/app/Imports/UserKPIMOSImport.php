<?php

namespace App\Imports;

use App\Models\KRA;
use App\Models\KPI;
use App\Models\MOS;
use App\Models\User;
use App\Models\YearLock;
use App\Models\MosData;
use App\Models\MOSAchievementPermission;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Auth;
use Maatwebsite\Excel\Validators\ValidationException;
class UserKPIMOSImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        // $YearLock = YearLock::where('year', $row['year'])->first();
        // if ($YearLock) {
        //     return $YearLock;
        // }
    
        // $product = Product::where('material_code',$row['products_code'])->first(); 
        $piscal_year = $row['year']==2024 ? 14:0;

        $user_data = Auth::user();
        if ($row && $row['mos_name']) {
            $employee_id = trim($row['employee_id']);
            $employee  =  User::where('email', $employee_id)
                ->orWhere('employee_id', $employee_id)
                ->where('status', 1)
                ->first();

            //Note: New Employee From HRIS API 
            if (!$employee) {
                $url = "http://magpie.hris.ssgbd.com/api/EmployeeInfoBPT?empCode=" . $row['employee_id'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                $hrisResult =  json_decode($response, true);
                $hrisResult =  $hrisResult[0];
                //return $this->sendResponse($result[0], 'Test API'); 
                $departmentHead = array();
                $supervisorId = array();
                if ($hrisResult) {
                    if (isset($hrisResult['departmentHeadId'])) {
                        $departmentHead  =  User::where('email', $hrisResult['departmentHeadId'])->orWhere('employee_id', $hrisResult['departmentHeadId'])->first();
                    }

                    if (isset($hrisResult['supervisorId'])) {
                        $supervisorId  =  User::where('email', $hrisResult['supervisorId'])->orWhere('employee_id', $hrisResult['supervisorId'])->first();
                    }
                }

                $userData['password'] = bcrypt(12345);
                $userData['dept_id'] = $departmentHead ? $departmentHead->dept_id : $user_data->dept_id;
                $userData['wing_id'] = $supervisorId && $supervisorId->wing_id ? $supervisorId->wing_id : 0;
                $userData['email'] = $row['employee_id'];
                $userData['name'] = $hrisResult ? $hrisResult['employeeName'] : $row['employee_id'];
                $userData['ad_mail'] =  $hrisResult && isset($hrisResult['email']) ? $hrisResult['email'] : '';
                $userData['role_id'] = 7;
                $userData['status'] = 1;
                $userData['designation'] =  $hrisResult ? $hrisResult['designation'] : '';
                $userData['employee_id'] =  $row['employee_id'];
                $employee = User::create($userData);
            }

            $year = $row['year'] ? $row['year'] : date("Y");
            $kpi_data =  array();
            $kra_data = array();

            if ($row['kpi_reference_id'] != '') {
                $kpi_data = MOS::find($row['kpi_reference_id']);
                $kra_data = KPI::find($kpi_data ? $kpi_data->kpi_id : 0);
            }
            //mos_data 
            //$kra = KRA::where('kra_name', $row['kra_name'])->where('user_id', $employee ? $employee->id :  0)->where('year', $year)->first();
            $kraQ = KRA::where('kra_name', $row['kra_name']);
            if ($employee->role_id == 5) {
                $kraQ->where('dept_id', $employee->dept_id);
                $kraQ->where('role_id', 5);
            } else {
                $kraQ->where('user_id', $employee->id);
            }
            $kraQ->where('year', $year);
            $kra = $kraQ->first();

            if ($employee) {
                //Note : KRA Check or Create
                if (!$kra) {
                    $kra =  KRA::create([
                        'kra_name' =>  $row['kra_name'] ?  $row['kra_name'] : $kra_data->kpi_name,
                        'dept_id' => $employee->dept_id ?  $employee->dept_id : $user_data->dept_id,
                        'year' => $year,
                        'report_type' => 1,
                        'user_id' => $employee ? $employee->id :  0,
                        'role_id' => $employee ? $employee->role_id :  0,
                        'wing_id' => $employee ? $employee->wing_id :  0,
                        'rep_id'  =>  $row['kpi_reference_id']  ?  $kra_data->id : 0,
                        'kra_weight' => 0,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);
                } else {
                    if ($kra['rep_id'] == '' && $row['kpi_reference_id'] != '') {
                        KRA::where('id', $kra['id'])->update(['rep_id' => $kra_data->id]);
                    }
                }
                if ($kra) {
                    //Note : KPI Check or Create
                    $kpi = KPI::where('kra_id', $kra['id'])
                    ->where('kpi_name', $row['kpi_name'] ?  $row['kpi_name'] : $kpi_data->mos_name)
                    ->first();

                    if (!$kpi) {
                        $kpi = KPI::create([
                            'kra_id' => $kra['id'],
                            'kpi_name' => $row['kpi_name'],
                            'dept_id' => $employee->dept_id ?  $employee->dept_id : $user_data->dept_id,
                            'year' => $year,
                            'rep_id'  => $row['kpi_reference_id'] != '' ? $row['kpi_reference_id'] :  0,
                            //'user_id' => $employee ? $employee->id :  0 , 
                            //'role_id' => $employee ? $employee->role_id :  0 , 
                            'kpi_weight' =>  0,
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);
                    } else {
                        if ($row['kpi_reference_id'] != '') {
                            KPI::where('id', $kpi['id'])->update(['rep_id' => $row['kpi_reference_id']]);
                        }
                    }

                    if ($kpi) {
                        //Note : MOS Check or Create
                        $mOS = MOS::where('kpi_id', $kpi['id'])->where('mos_name', $row['mos_name'])->first();
                        if (!$mOS) {
                            $currentDate = date('Y-m-d');
                            $mOS = MOS::create([
                                'kra_id' => $kra['id'],
                                'kpi_id' => $kpi['id'],
                                'mos_name' =>  $row['mos_name'] ?  $row['mos_name'] : '',
                                'dept_id' => $employee->dept_id ?  $employee->dept_id : $user_data->dept_id,
                                'year' => $year,
                                'piscal_year' => $piscal_year,
                                'report_type' => 1,
                                'rep_id' => isset($row['mos_reference_id']) ? $row['mos_reference_id'] :  0,
                                //'user_id' => $employee ? $employee->id :  0 , 
                                'rep_per' => isset($row['reference_per']) ? $row['reference_per'] :  0,
                                'weightage' => $row['mos_weightage'] ?  $row['mos_weightage']  : 0,
                                'modification_status' => 2,
                                'modification_months' => '[{"name":"Jan","id":"jan"},{"name":"Feb","id":"feb"},{"name":"Mar","id":"mar"},{"name":"Apr","id":"apr"},{"name":"May","id":"may"},{"name":"Jun","id":"jun"},{"name":"Jul","id":"jul"},{"name":"Aug","id":"aug"},{"name":"Sep","id":"sep"},{"name":"Oct","id":"oct"},{"name":"Nov","id":"nov"},{"name":"Dec","id":"dec"}]',
                                'start_date' => $currentDate,
                                'end_date' => date('Y-m-d', strtotime($currentDate . ' +3 days')),
                                'isvalorper' => isset($row['value_or_percentage']) ?  $row['value_or_percentage'] == 'percentage' || $row['value_or_percentage'] ==  1 ? 1 : 0 : 0,
                                'created_by' => Auth::user()->id,
                                'updated_by' => Auth::user()->id,
                            ]);
                        } else {
                            MOS::where('id', $mOS->id)
                                ->update([
                                    'weightage' => $row['mos_weightage'] ?  $row['mos_weightage']  : 0,
                                    'isvalorper' => isset($row['value_or_percentage']) ?  $row['value_or_percentage'] == 'percentage' || $row['value_or_percentage'] ==  1 ? 1 : 0 : 0,
                                    'rep_per' => isset($row['reference_per']) ? $row['reference_per'] : $mOS->rep_per,
                                    'rep_id' => isset($row['mos_reference_id']) ? $row['mos_reference_id'] :  0,
                                ]);
                        }
                        if ($mOS) {
                            ////Note : MOS Data Check or Create
                            $target = MosData::where('mos_id', $mOS->id)->where('type', 'target')->first();
                            $data['mos_id'] = $mOS->id;
                            $data['type'] = 'target';
                            $data['year'] =  $year;

                            $currentDate = date('Y-m-d');
                            if (isset($row['yearly_target']) && $row['yearly_target']) {
                                $monthly_target =  $row['yearly_target'] ? $row['yearly_target'] / 12 : 0;
                                //$monthly_target = number_format($monthly_target , 3) ;
                                $data['january'] =  $monthly_target;
                                $data['february'] =  $monthly_target;
                                $data['march'] =  $monthly_target;
                                $data['april'] =  $monthly_target;
                                $data['may'] =  $monthly_target;
                                $data['june'] =  $monthly_target;
                                $data['july'] =  $monthly_target;
                                $data['august'] =  $monthly_target;
                                $data['september'] =  $monthly_target;
                                $data['october'] =  $monthly_target;
                                $data['november'] =  $monthly_target;
                                $data['december'] =  $monthly_target;
                                $data['total'] =  $row['yearly_target'];
                            } else {
                                echo $mOS['start_date']. "--" .$mOS['end_date'];
                                if (strtotime($mOS['start_date']) <= strtotime($currentDate) && strtotime($currentDate) <= strtotime($mOS['end_date'])) {
                                    $monthsData = json_decode($mOS['modification_months'], true);
                                    $monthNames = [];
                                    foreach ($monthsData as $monthData) {
                                        echo $monthData['id']. "--" .$monthData['name'];
                                        $monthNames[$monthData['id']] = $monthData['name'];
                                    }
                                    if (isset($row['target_jan']) && isset($monthNames['jan'])) {
                                        $data['january'] =  isset($row['target_jan']) ? $row['target_jan'] : 0;
                                    }
                                    if (isset($row['target_feb']) && isset($monthNames['feb'])) {
                                        $data['february'] =  isset($row['target_feb']) ? $row['target_feb'] : 0;
                                    }
                                    if (isset($row['target_mar']) && isset($monthNames['mar'])) {
                                        $data['march'] =  isset($row['target_mar']) ? $row['target_mar'] : 0;
                                    }
                                    if (isset($row['target_apr']) && isset($monthNames['apr'])) {
                                        $data['april'] =  isset($row['target_apr']) ? $row['target_apr'] : 0;
                                    }
                                    if (isset($row['target_may']) && isset($monthNames['may'])) {
                                        $data['may'] =  isset($row['target_may']) ? $row['target_may'] : 0;
                                    }
                                    if (isset($row['target_jun']) && isset($monthNames['jun'])) {
                                        $data['june'] =  isset($row['target_jun']) ? $row['target_jun'] : 0;
                                    }
                                    if (isset($row['target_jul']) && isset($monthNames['jul'])) {
                                        $data['july'] =  isset($row['target_jul']) ? $row['target_jul'] : 0;
                                    }
                                    if (isset($row['target_aug']) && isset($monthNames['aug'])) {
                                        $data['august'] =  isset($row['target_aug']) ? $row['target_aug'] : 0;
                                    }
                                    if (isset($row['target_sep']) && isset($monthNames['sep'])) {
                                        $data['september'] =  isset($row['target_sep']) ? $row['target_sep'] : 0;
                                    }
                                    if (isset($row['target_oct']) && isset($monthNames['oct'])) {
                                        $data['october'] =  isset($row['target_oct']) ? $row['target_oct'] : 0;
                                    }
                                    if (isset($row['target_nov']) && isset($monthNames['nov'])) {
                                        $data['november'] =  isset($row['target_nov']) ? $row['target_nov'] : 0;
                                    }
                                    if (isset($row['target_dec']) && isset($monthNames['dec'])) {
                                        $data['december'] =  isset($row['target_dec']) ? $row['target_dec'] : 0;
                                    }
                                }
                                $data['dept_id'] =  $employee->dept_id ?  $employee->dept_id : $user_data->dept_id;
                                if ($target) {
                                    MosData::where('id', $target->id)->update($data);
                                } else {
                                    MosData::create($data);
                                }
                                // module
                                $data2['mos_id'] = $mOS->id;
                                $data2['type'] = 'module';
                                $data2['year'] =   $year;
                                $data2['dept_id'] =  $employee->dept_id ?  $employee->dept_id : $user_data->dept_id;
                                MosData::create($data2);
                                // achievement
                                $achievement = MosData::where('mos_id', $mOS->id)->where('type', 'achievement')->first();
                                $data3['mos_id'] = $mOS->id;
                                $data3['type'] = 'achievement';
                                if (isset($row['yearly_achv']) && $row['yearly_achv']) {
                                    $monthly_achv =  $row['yearly_achv'] ? $row['yearly_achv'] / 12 : 0;
                                    //$monthly_achv = number_format($monthly_achv , 3) ;
                                    $data3['january'] =  $monthly_achv;
                                    $data3['february'] =  $monthly_achv;
                                    $data3['march'] =  $monthly_achv;
                                    $data3['april'] =  $monthly_achv;
                                    $data3['may'] =  $monthly_achv;
                                    $data3['june'] =  $monthly_achv;
                                    $data3['july'] =  $monthly_achv;
                                    $data3['august'] =  $monthly_achv;
                                    $data3['september'] =  $monthly_achv;
                                    $data3['october'] =  $monthly_achv;
                                    $data3['november'] =  $monthly_achv;
                                    $data3['december'] =  $monthly_achv;
                                    $data3['total'] =  $row['yearly_achv'];
                                } else {
                                    $permission_checkAchv = MOSAchievementPermission::where('mos_id', $mOS->id)->first();
                                    if(!$permission_checkAchv){                                     
                                        $permission_data = [
                                            'user_id' => $employee->id ?  $employee->id : $user_data->id,
                                            'role_id' => $employee->role_id ?  $employee->role_id : $user_data->role_id,
                                            'mos_id' => $mOS->id,
                                            'jan' => 1,
                                            'feb' => 1,
                                            'mar' => 1,
                                            'apr' => 1,
                                            'may' => 1,
                                            'jun' => 1,
                                            'jul' => 1,
                                            'aug' => 1,
                                            'sep' => 1,
                                            'oct' => 1,
                                            'nov' => 1,
                                            'dec' => 1,
                                            'dept_id' => $employee->dept_id ?  $employee->dept_id : $user_data->dept_id,
                                            'year' => $year,
                                            'type' => null, // You can set the appropriate value for 'type' here
                                            'start_date' => $currentDate,
                                            'end_date' => date('Y-m-d', strtotime($currentDate . ' +3 days')),
                                            'request_status' => 2,
                                        ];
                                        $permission_checkAchv = MOSAchievementPermission::create($permission_data);
                                    }

                                    if ($permission_checkAchv->start_date <= $currentDate && $permission_checkAchv->end_date >= $currentDate && $permission_checkAchv->request_status == 2) {                                     
                                        
                                        if (isset($row['achv_jan']) && $permission_checkAchv->jan == 1) {
                                            $data3['january'] =  isset($row['achv_jan']) ? $row['achv_jan'] : 0;
                                        }
                                        if (isset($row['achv_feb']) && $permission_checkAchv->feb == 1) {
                                            $data3['february'] =  isset($row['achv_feb']) ? $row['achv_feb'] : 0;
                                        }
                                        if (isset($row['achv_mar']) && $permission_checkAchv->mar == 1) {
                                            $data3['march'] =  isset($row['achv_mar']) ? $row['achv_mar'] : 0;
                                        }
                                        if (isset($row['achv_apr']) && $permission_checkAchv->apr == 1) {
                                            $data3['april'] =  isset($row['achv_apr']) ? $row['achv_apr'] : 0;
                                        }
                                        if (isset($row['achv_may']) && $permission_checkAchv->may == 1) {
                                            $data3['may'] =  isset($row['achv_may']) ? $row['achv_may'] : 0;
                                        }
                                        if (isset($row['achv_jun']) && $permission_checkAchv->jun == 1) {
                                            $data3['june'] =  isset($row['achv_jun']) ? $row['achv_jun'] : 0;
                                        }
                                        if (isset($row['achv_jul']) && $permission_checkAchv->jul == 1) {
                                            $data3['july'] =  isset($row['achv_jul']) ? $row['achv_jul'] : 0;
                                        }
                                       
                                        if (isset($row['achv_aug']) && $permission_checkAchv->aug == 1) {
                                            echo (isset($row['achv_aug']) .'___'. $permission_checkAchv->aug);
                                            $data3['august'] =  isset($row['achv_aug']) ? $row['achv_aug'] : 0;
                                        }
                                        if (isset($row['achv_sep']) && $permission_checkAchv->sep == 1) {
                                            $data3['september'] =  isset($row['achv_sep']) ? $row['achv_sep'] : 0;
                                        }

                                        if (isset($row['achv_oct']) && $permission_checkAchv->oct == 1) {
                                            $data3['october'] =  isset($row['achv_oct']) ? $row['achv_oct'] : 0;
                                        }
                                        if (isset($row['achv_nov']) && $permission_checkAchv->nov == 1) {
                                            $data3['november'] =  isset($row['achv_nov']) ? $row['achv_nov'] : 0;
                                        }

                                        if (isset($row['achv_dec']) && $permission_checkAchv->dec == 1) {
                                            $data3['december'] =  isset($row['achv_dec']) ? $row['achv_dec'] : 0;
                                        }

                                        $data3['year'] =   $year;
                                        $data3['dept_id'] =  $employee->dept_id ?  $employee->dept_id : $user_data->dept_id;

                                        if ($achievement) {
                                            // echo $data3['july'].'update';
                                            // echo '<br>';
                                            MosData::where('id', $achievement->id)
                                                ->update($data3);
                                        } else {
                                            // echo $data3['july'].'insert';
                                            // echo '<br>';
                                            MosData::create($data3);
                                        }
                                    }else{
                                        echo 'upps..!!';
                                        // echo collect($permission_checkAchv)->count().'n/';
                                    }
                                }

                                $kpi_weightage = MOS::where('kpi_id', $kpi['id'])->sum('weightage');
                                KPI::where('id', $kpi['id'])->update(['kpi_weight' => $kpi_weightage]);

                                $kra_weightage = KPI::where('kra_id', $kra['id'])->sum('kpi_weight');
                                KRA::where('id', $kra['id'])->update(['kra_weight' => $kra_weightage]);
                            }
                        }
                    }
                    return $kra;
                }
            }
        }
    }

    public function monthCheck($array, $searchName)
    {
        $result = false;
        foreach ($array as $item) {
            if ($item["name"] === $searchName) {
                $result = true;
                break;
            }
        }
        return $result;
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
