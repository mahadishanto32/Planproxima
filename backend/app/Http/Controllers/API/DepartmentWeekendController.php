<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\DepartmentWeekend;
use App\Models\WeekendGroup;
use App\Models\Department;
use App\Models\WeekendGroupAssign;

class DepartmentWeekendController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dept_id = $request->group_id;
        $DepartmentWeekend = DepartmentWeekend::where('group_id', $dept_id)->get();
        return $this->sendResponse($DepartmentWeekend, 'DepartmentWeekend Retrived successfully');
    }

    public function weekend_group(Request $request)
    {
        $WeekendGroup = WeekendGroup::all();
        return $this->sendResponse($WeekendGroup, 'WeekendGroup Retrived successfully');
    }

    public function weekend_assign(Request $request)
    {
        $Department = Department::where('status', 1)->get();

        $WeekendGroupAssign = WeekendGroupAssign::select('weekendg_assign.*', 'weekendgroup.name')
            ->join('weekendgroup', 'weekendgroup.id', 'weekendg_assign.group_id')
            ->get()
            ->keyBy('department_id');

        foreach ($Department as $key => $value) {
            if (isset($WeekendGroupAssign[$value->id])) {
                $value['assign_id'] = $WeekendGroupAssign[$value->id]->group_id;
                $value['assign_name'] = $WeekendGroupAssign[$value->id]->name;
            } else {
                $value['assign_id'] = 0;
            }
        }
        return $this->sendResponse($Department, 'WeekendGroup Retrived successfully');
    }


    public function dep_weekend_assign(Request $request)
    {
        $check = WeekendGroupAssign::where('department_id', $request->department_id)->latest()->first();

        if (empty($check)) {
            $WeekendGroupAssign = WeekendGroupAssign::Create(
                [
                    'department_id' => $request->department_id,
                    'group_id' => $request->group_id
                ],
            );
        } else {
            $check->group_id = $request->group_id;
            $WeekendGroupAssign = $check->save();
        }
        return $this->sendResponse($WeekendGroupAssign, ' Assign successfully');
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
    public function weekendgroup_add(Request $request)
    {
        if ($request->group_name) {
            $WeekendGroup = new WeekendGroup();
            $WeekendGroup->name = $request->group_name;
            $WeekendGroup->save();
        }
        return $this->sendResponse(WeekendGroup::all(), 'WeekendGroup Add successfully');
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
    public function update(Request $request)
    {
        $group_id = $request->group_id;
        $date = $request->date;
        $check = $request->check;
        if ($check && $group_id) {
            $DepartmentWeekend = DepartmentWeekend::updateOrCreate(
                ['group_id' => $group_id, 'date' => $date]
            );
        } else {
            $DepartmentWeekend = DepartmentWeekend::where('date', $date)->delete();
        }

        return $this->sendResponse($check, 'Update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DepartmentWeekend::where('group_id', $id)->delete();
        WeekendGroupAssign::where('group_id', $id)->delete();
        WeekendGroup::where('id', $id)->delete();

        return $this->sendResponse(WeekendGroup::all(), 'WeekendGroup delete successfully');
    }
}
