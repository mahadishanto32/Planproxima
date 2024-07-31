<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateKRAAPIRequest;
use App\Http\Requests\API\UpdateKRAAPIRequest;
use App\Models\KRA;
use App\Models\KPI;
use App\Models\MOS;
use App\Models\User;
use App\Models\MosData;
use App\Models\Department;
use App\Models\YearLock;
use App\Imports\SecondLayerKPIImport;
use App\Imports\UserKPIMOSImport;
use App\Imports\UserKPIMOSAchivImport;
use App\Http\Resources\KraTreeResource;
use App\Repositories\KRARepository;
use App\Repositories\MosDataRepository;
use App\Http\Resources\KraResource;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersMosExport;
use App\Exports\UsersAchivExport;
use Auth, DB;

/**
 * Class KRAController
 * @package App\Http\Controllers\API
 */

class KRAAPIController extends AppBaseController
{
    /** @var  KRARepository */
    private $kRARepository;
    private $mosDataRepository;

    public function __construct(KRARepository $kRARepo, MosDataRepository $mosDataRepo)
    {
        $this->kRARepository = $kRARepo;
        $this->mosDataRepository = $mosDataRepo;
    }

    /**
     * Display a listing of the KRA.
     * GET|HEAD /kRAS
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        // $user_data = Auth::user();
        // if($user_data->role_id == 5 || $user_data->role_id == 6 || $user_data->role_id == 7 ){

        // }

        $user_data = Auth::user();
        if ($user_data->role_id == 1 || $user_data->role_id == 2 || $user_data->role_id == 3 || $user_data->role_id == 4 || $user_data->role_id == 5 || $user_data->role_id == 8) {
            $request['role_id'] =  5;
            $request['dept_id'] = $user_data->dept_id;
        } else {
            $request['user_id'] = $user_data->id;
        }


        if ($request->year) {
            $request['year'] = $request->year;
        }


        $kRAS = $this->kRARepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($kRAS->toArray(), 'KRA retrieved successfully');
    }

    /**
     * COPY KRA KPI MOS DATA FROM PREVIOUS YEAR TO NEXT YEAR
     */
    public function users_kra(Request $request){
        $wing_id = $request->wing_id;  
        $dept_id = $request->dept_id;     
        $user_id = $request->user_id; 
        
        $dataQuery =  KRA::where('dept_id' ,$dept_id );
        $dataQuery->where('user_id', $user_id  );
        $kRAS = $dataQuery->get();
        $result = KraTreeResource::collection($kRAS);
        return $this->sendResponse($result, 'KRA retrieved successfully');        
    }

    public function copy_kra_kpi_mos(Request $request)
    {
        $userData  = Auth::user() ;
        $year = $request->fromYear; //'2021' ; 
        $to_year = $request->toYear; //'2022';

        try {

            $checkDataQuery =  KRA::where('year',$to_year);
            $checkDataQuery->where('dept_id',  Auth::user()->dept_id );
            if ($request->user_id) {
                $checkDataQuery->where('user_id', $request->user_id  );
            }
            $checkDataQuery->whereNull('deleted_at');
            $checkDataQuery->get();
            $checkData =  $checkDataQuery->count() ;

            //CHECK ALREADY COPY DATA
            if (  $checkData > 0) {
                return $this->sendResponse(0, 'Data already copyied');
            } else {
                // $kras  = KRA::where(
                //     ['year' => $year, 
                //     'dept_id' => Auth::user()->dept_id])->get();

                // $checkDataQuery =  KRA::where('year',$to_year);
                // $checkDataQuery->where('dept_id', $userData->dept_id );
                // // if($userData->role_id == 6 || $userData->role_id == 7 || $userData->role_id == 10 ){
                // //     $checkDataQuery->where('user_id', $userData->id  );
                   
                // // }
                // // if ($request->user_id) { 
                // //     $checkDataQuery->where('user_id', $request->user_id  );
                // // }
                // // $checkDataQuery->whereNull('deleted_at');
                // $kras  = $checkDataQuery->get();
                // print_r( $userData->toArray());

               // print_r($userData);

               $dataQuery =  KRA::where('dept_id' ,$userData->dept_id );
               $dataQuery->where('year',$year);  
               $dataQuery->whereNull('deleted_at');
               if($userData->role_id == 6 || $userData->role_id == 7 || $userData->role_id == 10 ){
                 $dataQuery->where('user_id', $userData->id  );

               } 
               $kras =  $dataQuery ->get();

                

                //IF GET KRA FOR REQUESTED DEPARTMENT
                if ($kras->count() > 0) {
                    foreach ($kras as $key_kra => $kra) {
                        $kra->year =  $to_year;
                        $kra->created_at =  date('Y-m-d');
                        $kra->previous_id =  $kra->id ;
                        $kra_new =  KRA::create($kra->toArray());

                        $kpis  = KPI::where('kra_id', $kra->id)->get();

                        foreach ($kpis as $key_kpi => $kpi) {
                            $kpi->kra_id =  $kra_new->id;
                            $kpi->created_at =  date('Y-m-d');
                            $kpi->year =  $to_year;
                            $kpi->previous_id =  $kpi->id ;
                            $kpi_new  = KPI::create($kpi->toArray()); 
                            $moss = MOS::where('kpi_id', $kpi->id)->get(); 
                            foreach ($moss as $key => $mos) {
                                $mos->kra_id =  $kra_new->id;
                                $mos->kpi_id =  $kpi_new->id;
                                $mos->year =  $to_year;
                                $mos->previous_id =  $mos->id ;
                                $mos->created_at =  date('Y-m-d');
                                $mos_new  = MOS::create($mos->toArray()); 
                                $target['mos_id'] = $mos_new->id;
                                $target['type'] = 'target';
                                $target['year'] = $to_year;
                                $target['dept_id'] = $mos_new->dept_id;
                                $target['created_at'] = date('Y-m-d'); 
                                MosData::create($target); 
                                $module['mos_id'] = $mos_new->id;
                                $module['type'] = 'module';
                                $module['year'] = $to_year;
                                $module['dept_id'] = $mos_new->dept_id;
                                $module['created_at'] = date('Y-m-d');
                                MosData::create($module); 
                                $achievement['mos_id'] = $mos_new->id;
                                $achievement['type'] = 'achievement';
                                $achievement['year'] = $to_year;
                                $achievement['dept_id'] = $mos_new->dept_id;
                                $achievement['created_at'] = date('Y-m-d');
                                MosData::create($achievement);
                            }
                        }
                    }
                    return $this->sendResponse(1, 'Successfully data copy');
                } else {
                    return $this->sendResponse(0, 'DATA NOT FOUND FOR COPY');
                }
            }
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }


    // public function copy_kra_kpi_mos(Request $request)
    // {
    //     $year = $request->fromYear; //'2021' ; 
    //     $to_year = $request->toYear; //'2022';

    //     try {

    //         //CHECK ALREADY COPY DATA
    //         if (KRA::where(['year' => $to_year, 'dept_id' => Auth::user()->dept_id])->whereNull('deleted_at')->get()->count() > 0) {
    //             return $this->sendResponse(0, 'Data already copyied');
    //         } else {
    //             $kras  = KRA::where(
    //                 ['year' => $year, 
    //                 'dept_id' => Auth::user()->dept_id])->get();

    //             //IF GET KRA FOR REQUESTED DEPARTMENT
    //             if ($kras->count() > 0) {
    //                 foreach ($kras as $key_kra => $kra) {
    //                     $kra->year =  $to_year;
    //                     $kra->created_at =  date('Y-m-d');

    //                     $kra_new =  KRA::create($kra->toArray());

    //                     $kpis  = KPI::where('kra_id', $kra->id)->get();

    //                     foreach ($kpis as $key_kpi => $kpi) {
    //                         $kpi->kra_id =  $kra_new->id;
    //                         $kpi->created_at =  date('Y-m-d');
    //                         $kpi->year =  $to_year;
    //                         $kpi_new  = KPI::create($kpi->toArray());

    //                         $moss = MOS::where('kpi_id', $kpi->id)->get();

    //                         foreach ($moss as $key => $mos) {
    //                             $mos->kra_id =  $kra_new->id;
    //                             $mos->kpi_id =  $kpi_new->id;
    //                             $mos->year =  $to_year;
    //                             $mos->created_at =  date('Y-m-d');
    //                             $mos_new  = MOS::create($mos->toArray());

    //                             $target['mos_id'] = $mos_new->id;
    //                             $target['type'] = 'target';
    //                             $target['year'] = $to_year;
    //                             $target['dept_id'] = $mos_new->dept_id;
    //                             $target['created_at'] = date('Y-m-d');

    //                             MosData::create($target);

    //                             $module['mos_id'] = $mos_new->id;
    //                             $module['type'] = 'module';
    //                             $module['year'] = $to_year;
    //                             $module['dept_id'] = $mos_new->dept_id;
    //                             $module['created_at'] = date('Y-m-d');
    //                             MosData::create($module);

    //                             $achievement['mos_id'] = $mos_new->id;
    //                             $achievement['type'] = 'achievement';
    //                             $achievement['year'] = $to_year;
    //                             $achievement['dept_id'] = $mos_new->dept_id;
    //                             $achievement['created_at'] = date('Y-m-d');
    //                             MosData::create($achievement);
    //                         }
    //                     }
    //                 }
    //                 return $this->sendResponse(1, 'Successfully data copy');
    //             } else {
    //                 return $this->sendResponse(0, 'DATA NOT FOUND FOR COPY');
    //             }
    //         }
    //     } catch (\Exception $e) {

    //         return $e->getMessage();
    //     }
    // }

    public function kra_kpi_mos_tree(Request $request)
    {
        $user_data = Auth::user();
        if ($user_data->role_id == 5 || $user_data->role_id == 6 || $user_data->role_id == 7) {
            $request['dept_id'] = $user_data->dept_id;
        }
        $kRAS = $this->kRARepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );
        $result = KraTreeResource::collection($kRAS);
        return $this->sendResponse($result, 'KRA retrieved successfully');
    }
    public function kra_delete($id, Request $request)
    {
        $mos = MOS::where('kra_id', $id)->get();
        foreach ($mos as $key => $value) {
            $deletedRows = MosData::where('mos_id', $value->id)->delete();
        }
        //         use App\Models\KRA;
        // use App\Models\KPI;
        $deletedRows = MOS::where('kra_id', $id)->delete();
        $deletedRows = KPI::where('kra_id', $id)->delete();
        $deletedRows = KRA::where('id', $id)->delete();
        return $this->sendResponse($deletedRows, 'KRA deleted successfully');
    }

    //kra_all_delete

    public function kra_all_delete( Request $request)
    {
        $userData = Auth::user();

        $dataQuery =  KRA::where('dept_id' ,$userData->dept_id );
        $dataQuery->where('year',$request->year);  
        $dataQuery->whereNull('deleted_at');
        if($userData->role_id == 6 || $userData->role_id == 7 || $userData->role_id == 10 ){
          $dataQuery->where('user_id', $userData->id  );

        } 
        $kras =  $dataQuery ->get(); 
        foreach ($kras as $key => $value) { 
            $id  =  $value->id  ;  
            $mos = MOS::where('kra_id', $id)->get();
            foreach ($mos as $key => $value) {
                $deletedRows = MosData::where('mos_id', $value->id)->delete();
            } 
            $deletedRows = MOS::where('kra_id', $id)->delete();
            $deletedRows = KPI::where('kra_id', $id)->delete();
            $deletedRows = KRA::where('id', $id)->delete(); 
        } 
        return $this->sendResponse($deletedRows, 'KRA deleted successfully');
    }



    public function kpi_delete($id, Request $request)
    {
        $mos = MOS::where('kpi_id', $id)->get();
        foreach ($mos as $key => $value) {
            $deletedRows = MosData::where('mos_id', $value->id)->delete();
        }
        //         use App\Models\KRA;
        // use App\Models\KPI;
        $deletedRows = MOS::where('kpi_id', $id)->delete();
        $deletedRows = KPI::where('id', $id)->delete();
        // $deletedRows = KRA::where('id', $id)->delete();
        return $this->sendResponse($deletedRows, 'KPI deleted successfully');
    }
    public function mos_delete($id, Request $request)
    {
        //$mos = MOS::where('kra_id',$id)->get();
        $deletedRows = MosData::where('mos_id', $id)->delete();
        $deletedRows = MOS::where('id', $id)->delete();
        //$deletedRows = KPI::where('kra_id', $id)->delete();
        // $deletedRows = KRA::where('id', $id)->delete();
        return $this->sendResponse($deletedRows, 'MOD deleted successfully');
    }
    public function kra_details($id, Request $request)
    {
        $kRA = $this->kRARepository->find($id);

        $returnResult = new KraResource($kRA);
        return $this->sendResponse($returnResult, 'K P I S retrieved successfully');
    }
    public function kra_kpi_mos(Request $request)
    {
        // $user_data = Auth::user();
        // if ($user_data->role_id == 5 || $user_data->role_id == 6 || $user_data->role_id == 7) {
        //     $request['dept_id'] = $user_data->dept_id;
        // }

        // $kRAS = $this->kRARepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

        // $data_return  =   KraResource::collection($kRAS);
        // return $this->sendResponse($data_return, 'K P I S retrieved successfully');
   
        $user_data = Auth::user();

        //return $this->sendResponse( $user_data, 'K P I S retrieved successfully');
        if ($request->dept_id) {
            $dept_id = $request->dept_id;
        } else {
            $dept_id = $user_data->dept_id;
        }
        $task = KRA::select("k_r_a_s.*")
            ->join("m_o_s", "m_o_s.kra_id", "k_r_a_s.id")
            ->limit(300);

        if($request->user_id){
            $task->where("k_r_a_s.user_id", $request->user_id); 
        }else{
            if (
                $user_data->role_id == 7 ||
                $user_data->role_id == 6 ||
                $user_data->role_id == 10 ||
                $user_data->role_id == 9
            ) {
                if ($user_data->role_id == 6) {
                    $task->where("k_r_a_s.wing_id", $user_data->wing_id);
                    if ($request->user_id) {
                        $task->where("k_r_a_s.user_id", $request->user_id);
                    } else {
                        $task->where("k_r_a_s.user_id", $user_data->id);
                    }
                } else {
                    $task->where("k_r_a_s.user_id", $user_data->id);
                }
            } else {
                if ($request->user_id) {
                    $task->where("k_r_a_s.user_id", $request->user_id);
                } elseif ($request->wing_id) {
                    $task->where("k_r_a_s.wing_id", $request->wing_id);
                } else {
                    $task->where("k_r_a_s.role_id", 5);
                }
            }

            if ($dept_id) {
                $task->where("m_o_s.dept_id", $dept_id);
            }
        }
        if ($request->kra_id) {
            $task->where("m_o_s.kra_id", $request->kra_id);
        }
        if ($request->kpi_id) {
            $task->where("m_o_s.kpi_id", $request->kpi_id);
        }
        if ($request->mos_id) {
            $task->where("m_o_s.mos_id", $request->mos_id);
        }

        if ($request->user_id) {
            $userInfo = User::find($request->user_id);
            if ($userInfo->employee_status == 1) {
                $task->where("m_o_s.year", $request->year);
            }
        } else {
            $task->where("m_o_s.year", $request->year);
        }

        // }
        $task->orderBy("m_o_s.kra_id", "ASC");
        $task->orderBy("m_o_s.kpi_id", "ASC");
        $task->orderBy("m_o_s.id", "ASC");

        $result = $task->get();
        $data_return = KraResource::collection($result);
        return $this->sendResponse(
            $data_return,
            "K P I S retrieved successfully"
        );
    }
    public function bpt_report(Request $request)
    {
        $task = KRA::all();
        $data_return  =   KraResource::collection($task);
        return $this->sendResponse($data_return, 'K P I S retrieved successfully');
    }

    public function kar_kpi_mos_chart(Request $request)
    {
        $user_data = Auth::user();
        $department = Department::find($user_data->dept_id);
        $data = array(
            'value' => $department->name,
            'children' => array(
                'value' => 'KRA',
                'children' => array(
                    'value' => 'KPI',
                    'children' => array(
                        'value' => 'text',
                    )
                )
            )
        );
        return $this->sendResponse($data, 'KRA retrieved successfully');
    }

    /**
     * Store a newly created KRA in storage.
     * POST /kRAS
     *
     * @param CreateKRAAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateKRAAPIRequest $request)
    {
        $input = $request->all();

        $kRA = $this->kRARepository->create($input);

        return $this->sendResponse($kRA->toArray(), 'K R A saved successfully');
    }
    public function kra_kpi_setting(Request $request)
    {
        $user_data = Auth::user();
        $department = Department::find($user_data->dept_id);
        if ($request->arrayData['name'] != '') {
            $kar = array(
                'kra_name' => $request->arrayData['name'],
                'kra_weight' => $request->arrayData['kra_weight'],
                'dept_id'  =>   $department->id,
                'year'  =>  $request->year,
            );
            $kRA = $this->kRARepository->create($kar);

            $kriArray  =  $request->arrayData['children'];
            foreach ($kriArray as $key => $value) {
                if ($kriArray[$key]['name'] != '')
                    $kar = array(
                        'kpi_name' => $kriArray[$key]['name'],
                        'kpi_weight' => $kriArray[$key]['kpi_weight'],
                        'dept_id'  =>   $department->id,
                        'kra_id'   =>    $kRA->id,
                        'year'  =>  $request->year,
                        'created_at' => Now()
                    );
                $kpi_id = KPI::create($kar);
                $mosArray  =  $kriArray[$key]['children'];
                foreach ($mosArray as $key2 => $value) {
                    if ($mosArray[$key2]['name'] != '') {
                        $mos = array(
                            'mos_name' => $mosArray[$key2]['name'],
                            'dept_id'  =>   $department->id,
                            'kra_id'   =>    $kRA->id,
                            'kpi_id'   =>    $kpi_id->id,
                            'year'  =>  $request->year,
                            'created_at' => Now()
                        );
                        $mOS = MOS::create($mos);

                        //target
                        $data['mos_id'] = $mOS->id;
                        $data['type'] = 'target';
                        $data['year'] =  $request->year;
                        $data['dept_id'] =  $department->id;
                        $this->mosDataRepository->create($data);

                        // module
                        $data2['mos_id'] = $mOS->id;
                        $data2['type'] = 'module';
                        $data2['year'] =   $request->year;
                        $data2['dept_id'] =  $department->id;
                        $this->mosDataRepository->create($data2);

                        // achievement
                        $data3['mos_id'] = $mOS->id;
                        $data3['type'] = 'achievement';
                        $data3['year'] =   $request->year;
                        $data3['dept_id'] =  $department->id;
                        $this->mosDataRepository->create($data3);
                    }
                }
            }
        }
        return $this->sendResponse(0, 'K R A saved successfully');
    }

    /**
     * Display the specified KRA.
     * GET|HEAD /kRAS/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var KRA $kRA */
        $kRA = $this->kRARepository->find($id);

        if (empty($kRA)) {
            return $this->sendError('K R A not found');
        }

        return $this->sendResponse($kRA->toArray(), 'K R A retrieved successfully');
    }

    /**
     * Update the specified KRA in storage.
     * PUT/PATCH /kRAS/{id}
     *
     * @param int $id
     * @param UpdateKRAAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateKRAAPIRequest $request)
    {
        $input = $request->all();

        /** @var KRA $kRA */
        $kRA = $this->kRARepository->find($id);

        if (empty($kRA)) {
            return $this->sendError('K R A not found');
        }

        $kRA = $this->kRARepository->update($input, $id);

        return $this->sendResponse($kRA->toArray(), 'KRA updated successfully');
    }

    /**
     * Remove the specified KRA from storage.
     * DELETE /kRAS/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var KRA $kRA */
        $kRA = $this->kRARepository->find($id);

        if (empty($kRA)) {
            return $this->sendError('K R A not found');
        }

        $kRA->delete();

        return $this->sendSuccess('K R A deleted successfully');
    }
    //fileUpload second  Layer 
    public function fileUpload(Request $request)
    {
        if ($request->hasFile('csvFile')) {
            $file_name = $request->file('csvFile')->getClientOriginalName();
            $file_name_url = Storage::disk('public')->put('production_file', $request->csvFile);

            $additional_data = [
                'file_name' => $file_name,
                'year' => $request->year,
            ];

            $data = Excel::import(new SecondLayerKPIImport($additional_data), request()->file('csvFile'));
            return $this->sendResponse($data, 'SAP Data retrieved successfully');
        } else {
            return $this->sendResponse(0, 'Error');
        }
    }
    //fileUploadCsv

    public function fileUploadCsv(Request $request)
    {
        if ($request->hasFile('csvFile')) {
            $file_name = $request->file('csvFile')->getClientOriginalName();
            $yearLock = YearLock::all()->keyBy('year');

            // if(isset($yearLock[$request->year])){
            //     return $this->sendResponse([], 'MOS Modification Locak...!');
            // }
            $file_name_url = Storage::disk('public')->put('csv_file', $request->csvFile);

            $additional_data = [
                'file_name' => $file_name,
                'year' => $request->year,
                'yearLock' => $yearLock,
            ];
            
            $data = Excel::import(new UserKPIMOSImport($additional_data), request()->file('csvFile'));
            return $this->sendResponse($data, 'MOS retrieved successfully');
        } else {
            return $this->sendResponse(0, 'Error');
        }
    }

    public function achivUploadCsv(Request $request)
    {
        if ($request->hasFile('csvFile')) {
            $file_name = $request->file('csvFile')->getClientOriginalName();
            $file_name_url = Storage::disk('public')->put('csv_file', $request->csvFile);

            $additional_data = [
                'file_name' => $file_name,
                'year' => $request->year,
            ];

            $data = Excel::import(new UserKPIMOSAchivImport($additional_data), request()->file('csvFile'));
            return $this->sendResponse($data, 'MOS retrieved successfully');
        } else {
            return $this->sendResponse(0, 'Error');
        }
    }

    public function  download_mos_file_format(Request $request){
        $data = array(
            'user_id' => $request->user_id,
            'year' => $request->year?$request->year:date('Y'),
            'all_check' => $request->all_check?$request->all_check:0,
            'dept_id' => $request->dept_id,
        );

        return Excel::download(new UsersMosExport($data), 'users.xlsx');
    }
    
    public function  achiv_download_mos_file_format(Request $request)
    {
        $data = array(
            'user_id' => $request->user_id,
            'year' => $request->year
        );
        return Excel::download(new UsersAchivExport($data), 'users.xlsx');
    }
}
