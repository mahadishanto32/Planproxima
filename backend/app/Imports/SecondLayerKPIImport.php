<?php
namespace App\Imports;      
use App\Models\KRA;
use App\Models\KPI;
use App\Models\MOS; 
use App\Models\User; 
use App\Models\MosData; 
use Illuminate\Database\Eloquent\Model;  
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash; 
use Auth ;
class SecondLayerKPIImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    { 
       // $product = Product::where('material_code',$row['products_code'])->first(); 
       $user_data = Auth::user(); 
       if($row){ 
        $employee  =  User::where('email', $row['employee_id'])->orWhere('employee_id', $row['employee_id'])->first(); 
        if(!$employee){ 
            $url = "http://magpie.hris.ssgbd.com/api/EmployeeInfoBPT?empCode=".$row['employee_id'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            $response = curl_exec($ch); 
            $hrisResult =  json_decode($response, true);
            $hrisResult =  $hrisResult[0];
            
            //return $this->sendResponse($result[0], 'Test API'); 
            $departmentHead = array();
            $supervisorId = array();
            if($hrisResult){
                if( isset($hrisResult['departmentHeadId'])){
                    $departmentHead  =  User::where('email', $hrisResult['departmentHeadId'])->orWhere('employee_id', $hrisResult['departmentHeadId'])->first();  
                } 
               
                if( isset($hrisResult['supervisorId'])){
                    $supervisorId  =  User::where('email', $hrisResult['supervisorId'])->orWhere('employee_id', $hrisResult['supervisorId'])->first(); 
                } 
            }
           
           
            $userData['password'] = bcrypt(12345);
            $userData['dept_id'] = $departmentHead ? $departmentHead->dept_id : $user_data->dept_id; 
            $userData['wing_id'] = $supervisorId && $supervisorId->wing_id ? $supervisorId->wing_id : 0; 
            $userData['email'] = $row['employee_id'];
            $userData['name'] = $hrisResult ? $hrisResult['employeeName'] : $row['employee_id'] ;
            $userData['ad_mail'] =  $hrisResult && isset($hrisResult['email']) ? $hrisResult['email'] : '';
            $userData['role_id'] = 7 ;
            $userData['status'] = 1 ;
            $userData['designation'] =  $hrisResult ? $hrisResult['designation'] : '';
            $userData['employee_id'] =  $row['employee_id'];  
            $employee = User::create($userData);
        }
        $year =   $row['year'] ? $row['year'] : date("Y");
        $kra = KRA::where('kra_name',$row['kra'])->where('user_id',$employee ? $employee->id :  0)->where('year',$year)->first();
        if($employee){
            if(!$kra){  
                $kra =  KRA::create([ 
                    'kra_name' =>  $row['kra'] ,
                    'dept_id' => $employee->dept_id ?  $employee->dept_id : $user_data->dept_id , 
                    'year' => $year,
                    'user_id' => $employee ? $employee->id :  0 , 
                    'role_id' => $employee ? $employee->role_id :  0 , 
                    'wing_id' => $employee ? $employee->wing_id :  0 , 
                    'kra_weight' => 0, 
                    'created_by' => Auth::user()->id ,  
                    'updated_by' => Auth::user()->id ,
                ]);
            }
             if($kra){
                $kpi = KPI::where('kra_id',$kra['id'])->where('kpi_name',$row['kpi'])->first();
                if(!$kpi){
                   $kpi = KPI::create([ 
                        'kra_id' => $kra['id'] ,
                        'kpi_name' =>  $row['kpi'] ,
                        'dept_id' => $employee->dept_id ?  $employee->dept_id : $user_data->dept_id , 
                        'year' => $year,
                        //'user_id' => $employee ? $employee->id :  0 , 
                        //'role_id' => $employee ? $employee->role_id :  0 , 
                        'kpi_weight' =>  0, 
                        'created_by' => Auth::user()->id ,  
                        'updated_by' => Auth::user()->id ,
                    ]);
                }
                if($kpi){
                    $mOS = MOS::where('kpi_id',$kpi['id'])->where('mos_name',$row['mos'])->first();
                    if(!$mOS){ 
                        $mOS = MOS::create([ 
                            'kra_id' => $kra['id'] ,
                            'kpi_id' => $kpi['id'] ,
                            'mos_name' =>  $row['mos'] ?  $row['mos'] : $row['kpi'] ,
                            'dept_id' => $employee->dept_id ?  $employee->dept_id : $user_data->dept_id  , 
                            'year' => $year,
                            //'user_id' => $employee ? $employee->id :  0 , 
                            //'role_id' => $employee ? $employee->role_id :  0 , 
                            'weightage' => $row['mos_weightage'] ?  $row['mos_weightage']  : 0 , 
                            'created_by' => Auth::user()->id ,  
                            'updated_by' => Auth::user()->id ,
                        ]);

                        
                    }else{
                        MOS::where('id',$mOS->id)->update(['weightage'=> $row['mos_weightage'] ?  $row['mos_weightage']  : 0 ]);
                    }
                    if($mOS){ 
                        //target
                        
                        $target = MosData::where('mos_id',$mOS->id)->where('type','target')->first();
                        $data['mos_id'] = $mOS->id;
                        $data['type'] = 'target';
                        $data['year'] =  $year; 
                        if(isset($row['yearly_target']) && $row['yearly_target']){
                            $monthly_target =  $row['yearly_target'] ? $row['yearly_target'] /12 : 0 ;
                            //$monthly_target = number_format($monthly_target , 3) ;
                            $data['january'] =  $monthly_target ;
                            $data['february'] =  $monthly_target ;
                            $data['march'] =  $monthly_target ;
                            $data['april'] =  $monthly_target ;
                            $data['may'] =  $monthly_target ;
                            $data['june'] =  $monthly_target ;
                            $data['july'] =  $monthly_target ;
                            $data['august'] =  $monthly_target ;
                            $data['september'] =  $monthly_target ;
                            $data['october'] =  $monthly_target ;
                            $data['november'] =  $monthly_target ;
                            $data['december'] =  $monthly_target ; 
                            $data['total'] =  $row['yearly_target'] ; 
                        }else{
                            $data['january'] =  isset($row['target_jan']) ? $row['target_jan'] : 0 ;
                            $data['february'] =  isset($row['target_feb']) ? $row['target_feb'] : 0 ;
                            $data['march'] =  isset($row['target_mar']) ? $row['target_mar'] : 0 ;
                            $data['april'] =  isset($row['target_apr']) ? $row['target_apr'] : 0 ;
                            $data['may'] =  isset($row['target_may']) ? $row['target_may'] : 0 ;
                            $data['june'] =  isset($row['target_jun']) ? $row['target_jun'] : 0 ;
                            $data['july'] =  isset($row['target_jul']) ? $row['target_jul'] : 0 ;
                            $data['august'] =  isset($row['target_aug']) ? $row['target_aug'] : 0 ;
                            $data['september'] =  isset($row['target_sep']) ? $row['target_sep'] : 0 ;
                            $data['october'] =  isset($row['target_oct']) ? $row['target_oct'] : 0 ;
                            $data['november'] =  isset($row['target_nov']) ? $row['target_nov'] : 0 ;
                            $data['december'] =  isset($row['target_dec']) ? $row['target_dec'] : 0 ; 
                        }
                        
                        $data['dept_id'] =  $employee->dept_id ?  $employee->dept_id : $user_data->dept_id ; 
                        if($target){
                            MosData::where('id',$target->id)->update($data);
                        }else{
                            MosData::create($data); 
                        } 
                        // module
                        $data2['mos_id'] = $mOS->id;
                        $data2['type'] = 'module'; 
                        $data2['year'] =   $year;
                        $data2['dept_id'] =  $employee->dept_id ?  $employee->dept_id : $user_data->dept_id ; 
                        MosData::create($data2);   
                        
                        
                        // achievement
                        $achievement = MosData::where('mos_id',$mOS->id)->where('type','achievement')->first();
                        $data3['mos_id'] = $mOS->id;
                        $data3['type'] = 'achievement';
                        if(isset($row['yearly_achv']) && $row['yearly_achv']){
                            $monthly_achv =  $row['yearly_achv'] ? $row['yearly_achv'] /12 : 0 ;
                            //$monthly_achv = number_format($monthly_achv , 3) ;
                            $data3['january'] =  $monthly_achv ;
                            $data3['february'] =  $monthly_achv ;
                            $data3['march'] =  $monthly_achv ;
                            $data3['april'] =  $monthly_achv ;
                            $data3['may'] =  $monthly_achv ;
                            $data3['june'] =  $monthly_achv ;
                            $data3['july'] =  $monthly_achv ;
                            $data3['august'] =  $monthly_achv ;
                            $data3['september'] =  $monthly_achv ;
                            $data3['october'] =  $monthly_achv ;
                            $data3['november'] =  $monthly_achv ;
                            $data3['december'] =  $monthly_achv ; 
                            $data3['total'] =  $row['yearly_achv'] ; 
                        }else{
                            $data3['january'] =  isset($row['achv_jan']) ? $row['achv_jan'] : 0 ;
                            $data3['february'] =  isset($row['achv_feb']) ? $row['achv_feb'] : 0 ;
                            $data3['march'] =  isset($row['achv_mar']) ? $row['achv_mar'] : 0 ;
                            $data3['april'] =  isset($row['achv_apr']) ? $row['achv_apr'] : 0 ;
                            $data3['may'] =  isset($row['achv_may']) ? $row['achv_may'] : 0 ;
                            $data3['june'] =  isset($row['achv_jun']) ? $row['achv_jun'] : 0 ;
                            $data3['july'] =  isset($row['achv_jul']) ? $row['achv_jul'] : 0 ;
                            $data3['august'] =  isset($row['achv_aug']) ? $row['achv_aug'] : 0 ;
                            $data3['september'] =  isset($row['achv_sep']) ? $row['achv_sep'] : 0 ;
                            $data3['october'] =  isset($row['achv_oct']) ? $row['achv_oct'] : 0 ;
                            $data3['november'] =  isset($row['achv_nov']) ? $row['achv_nov'] : 0 ;
                            $data3['december'] =  isset($row['achv_dec']) ? $row['achv_dec'] : 0 ;
                        }
                       
                        $data3['year'] =   $year;
                        $data3['dept_id'] =  $employee->dept_id ?  $employee->dept_id : $user_data->dept_id ; 
                       // $data3['dept_id'] =  $employee ? $employee->dept_id :  0 ; 
                        //MosData::create($data3);  
                        if($achievement){
                            MosData::where('id',$achievement->id)
                            ->update($data3);
                        }else{
                            MosData::create($data3); 
                        } 
                    } 

                     $kpi_weightage = MOS::where('kpi_id',$kpi['id'])->sum('weightage');
                     KPI::where('id',$kpi['id'])->update(['kpi_weight'=> $kpi_weightage ]);

                     $kra_weightage = KPI::where('kra_id',$kra['id'])->sum('kpi_weight');
                     KRA::where('id',$kra['id'])->update(['kra_weight'=> $kra_weightage ]);

                } 
             }
        }
             return $kra ; 
        }
    }  
}
