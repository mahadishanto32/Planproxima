<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DepartmentContribution;
use App\Models\Department; 
use App\Http\Controllers\AppBaseController;

class DepartmentContibution extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $departments = Department::select('department_contributions.objective' ,
        'department_contributions.contribution_value' ,
        'department_contributions.contribution_percentage' ,
        'departments.name' , 
        'departments.id as dept_id' , 
        )
        ->leftJoin('department_contributions','departments.id','department_contributions.dept_id')
        ->get();
  
        return $this->sendResponse($departments->toArray(), 'Departments retrieved successfully');
    }

    
public function saveChanges(Request $request)
{
    // Validate the incoming data
    $request->validate([
        'data' => 'required|array', 
        'data.*.objective' => 'required|string|max:255',
        'data.*.contribution_value' => 'required|numeric|min:0',
        'data.*.contribution_percentage' => 'required|numeric|min:0|max:100',
    ], [
        'data.required' => 'The data array is required.', 
        'data.*.objective.required' => 'The objective field is required.',
        'data.*.objective.string' => 'The objective must be a string.',
        'data.*.objective.max' => 'The objective may not be greater than 255 characters.',
        'data.*.contribution_value.required' => 'The contribution value is required.',
        'data.*.contribution_value.numeric' => 'The contribution value must be a number.',
        'data.*.contribution_value.min' => 'The contribution value must be at least 0.',
        'data.*.contribution_percentage.required' => 'The contribution percentage is required.',
        'data.*.contribution_percentage.numeric' => 'The contribution percentage must be a number.',
        'data.*.contribution_percentage.min' => 'The contribution percentage must be at least 0.',
        'data.*.contribution_percentage.max' => 'The contribution percentage may not be greater than 100.',
    ]);
    
   
    foreach ($request->data as $item) {
        // Check if a record with this dept_id already exists
        $departmentContribution = DepartmentContribution::where('dept_id', $item['dept_id'])->first();

        if ($departmentContribution) {
            // Update existing record 
            $departmentContribution->objective = $item['objective'];
            $departmentContribution->dept_id = $item['dept_id'];
            $departmentContribution->contribution_value = $item['contribution_value'];
            $departmentContribution->contribution_percentage = $item['contribution_percentage'];
            $departmentContribution->save();
        } else {
            // Insert new record
            DepartmentContribution::create([
                'dept_id' => $item['dept_id'],
                'objective' => $item['objective'],
                'contribution_value' => $item['contribution_value'],
                'contribution_percentage' => $item['contribution_percentage'],
            ]);
        }
    }

    // Return a response
    return response()->json(['message' => 'Changes saved successfully'], 200);
    
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
