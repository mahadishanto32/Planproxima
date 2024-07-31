<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFollowUpDeptAPIRequest;
use App\Http\Requests\API\UpdateFollowUpDeptAPIRequest;
use App\Models\FollowUpDept;
use App\Models\Department;
use App\Repositories\FollowUpDeptRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class FollowUpDeptController
 * @package App\Http\Controllers\API
 */

class FollowUpDeptAPIController extends AppBaseController
{
    /** @var  FollowUpDeptRepository */
    private $followUpDeptRepository;

    public function __construct(FollowUpDeptRepository $followUpDeptRepo)
    {
        $this->followUpDeptRepository = $followUpDeptRepo;
    }

    /**
     * Display a listing of the FollowUpDept.
     * GET|HEAD /followUpDepts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $followUpDepts = $this->followUpDeptRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($followUpDepts->toArray(), 'Follow Up Depts retrieved successfully');
    }

    public function follow_up_dept(Request $request){

        //$user_data = Auth::user(); 
        $query =  Department:: select('departments.*'); 
        $query->where('departments.status',1);
        $query->where('follow_up_depts.activity_id',$request['activity_id']);
        $query->leftjoin('follow_up_depts','follow_up_depts.dept_id', '=', 'departments.id');
        $departments =  $query->get(); 
        return $this->sendResponse($departments->toArray(), 'Departments retrieved successfully');

    }

    /**
     * Store a newly created FollowUpDept in storage.
     * POST /followUpDepts
     *
     * @param CreateFollowUpDeptAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFollowUpDeptAPIRequest $request)
    {
        $input = $request->all();

        $followUpDept = $this->followUpDeptRepository->create($input);

        return $this->sendResponse($followUpDept->toArray(), 'Follow Up Dept saved successfully');
    }

    /**
     * Display the specified FollowUpDept.
     * GET|HEAD /followUpDepts/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var FollowUpDept $followUpDept */
        $followUpDept = $this->followUpDeptRepository->find($id);

        if (empty($followUpDept)) {
            return $this->sendError('Follow Up Dept not found');
        }

        return $this->sendResponse($followUpDept->toArray(), 'Follow Up Dept retrieved successfully');
    }

    /**
     * Update the specified FollowUpDept in storage.
     * PUT/PATCH /followUpDepts/{id}
     *
     * @param int $id
     * @param UpdateFollowUpDeptAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFollowUpDeptAPIRequest $request)
    {
        $input = $request->all();

        /** @var FollowUpDept $followUpDept */
        $followUpDept = $this->followUpDeptRepository->find($id);

        if (empty($followUpDept)) {
            return $this->sendError('Follow Up Dept not found');
        }

        $followUpDept = $this->followUpDeptRepository->update($input, $id);

        return $this->sendResponse($followUpDept->toArray(), 'FollowUpDept updated successfully');
    }

    /**
     * Remove the specified FollowUpDept from storage.
     * DELETE /followUpDepts/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var FollowUpDept $followUpDept */
        $followUpDept = $this->followUpDeptRepository->find($id);

        if (empty($followUpDept)) {
            return $this->sendError('Follow Up Dept not found');
        }

        $followUpDept->delete();

        return $this->sendSuccess('Follow Up Dept deleted successfully');
    }
}
