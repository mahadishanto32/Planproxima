<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\API\CreateMOSAPIRequest;
use App\Http\Requests\API\UpdateMOSAPIRequest;
use App\Models\MOS;
use App\Models\KRA;
use App\Models\KPI;
use App\Models\MosData;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\UesrMos;
use App\Models\Wing;
use App\Models\MosFeadback;
use App\Repositories\MOSRepository;
use App\Repositories\MosDataRepository;
use App\Http\Resources\MosTreeResource;
use App\Http\Resources\MosTreeResourceUnassign;
use App\Http\Resources\KraResource;
use App\Http\Resources\KraEmployeeWiseResource;
use App\Http\Resources\MosEmployeeWiseResource;
use App\Http\Resources\MosItemResource;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\KpiTreeResource;
use Response;
use DB;
use Auth;
use Illuminate\Foundation\Auth\User;

class MonthlyPerformanceReportController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        if ($request->dept_id) {
            $user_data = Auth::user();
            $task = MOS::select('m_o_s.*', 'k_r_a_s.user_id', 'users.name as user_name', 'departments.name as dep_name')
                ->join('k_r_a_s', 'k_r_a_s.id', 'm_o_s.kra_id')
                ->leftjoin('departments', 'departments.id', 'k_r_a_s.dept_id')
                ->leftjoin('users', 'users.id', 'k_r_a_s.user_id'); 
            if ($request->user_id) {
                $task->where('k_r_a_s.user_id', $request->user_id);
            }
            if ($request->wing_id) {
                $task->where('k_r_a_s.wing_id', $request->wing_id);
                if (!$request->user_id) {
                    $task->where('k_r_a_s.role_id', 6);
                }
            }
            if ($request->dept_id) {
                $task->where('m_o_s.dept_id',  $request->dept_id);
            } else {
                $task->where('k_r_a_s.role_id', 5);
            }
            if ($request->kra_id) {
                $task->where('m_o_s.kra_id', $request->kra_id);
            }
            if ($request->kpi_id) {
                $task->where('m_o_s.kpi_id', $request->kpi_id);
            }
            if ($request->mos_id) {
                $task->where('m_o_s.mos_id', $request->mos_id);
            }
            if ($request->year) {
                $task->where('m_o_s.year', $request->year);
            }
            if (!$request->user_id && !$request->wing_id) {
                $task->where('k_r_a_s.role_id', 5);
            }
            $task->orderBy('k_r_a_s.dept_id', 'ASC');
            $task->orderBy('k_r_a_s.user_id', 'ASC');
            $task->orderBy('m_o_s.kra_id', 'ASC');
            $task->orderBy('m_o_s.kpi_id', 'ASC');
            $task->orderBy('m_o_s.id', 'ASC'); 
            $result  = $task->get();
            $data_return  = MosTreeResource::collection($result);
            return $this->sendResponse($data_return, 'K P I S retrieved successfully');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
