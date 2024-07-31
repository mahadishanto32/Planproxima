<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDepartmentCCmailAPIRequest;
use App\Http\Requests\API\UpdateDepartmentCCmailAPIRequest;
use App\Models\DepartmentCCmail;
use App\Repositories\DepartmentCCmailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DepartmentCCmailController
 * @package App\Http\Controllers\API
 */

class DepartmentCCmailAPIController extends AppBaseController
{
    /** @var  DepartmentCCmailRepository */
    private $departmentCCmailRepository;

    public function __construct(DepartmentCCmailRepository $departmentCCmailRepo)
    {
        $this->departmentCCmailRepository = $departmentCCmailRepo;
    }

    /**
     * Display a listing of the DepartmentCCmail.
     * GET|HEAD /departmentCCmails
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $departmentCCmails = $this->departmentCCmailRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($departmentCCmails->toArray(), 'Department C Cmails retrieved successfully');
    }

    /**
     * Store a newly created DepartmentCCmail in storage.
     * POST /departmentCCmails
     *
     * @param CreateDepartmentCCmailAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDepartmentCCmailAPIRequest $request)
    {
        $input = $request->all();

        $departmentCCmail = $this->departmentCCmailRepository->create($input);

        return $this->sendResponse($departmentCCmail->toArray(), 'Department C Cmail saved successfully');
    }

    /**
     * Display the specified DepartmentCCmail.
     * GET|HEAD /departmentCCmails/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DepartmentCCmail $departmentCCmail */
        $departmentCCmail = $this->departmentCCmailRepository->find($id);

        if (empty($departmentCCmail)) {
            return $this->sendError('Department C Cmail not found');
        }

        return $this->sendResponse($departmentCCmail->toArray(), 'Department C Cmail retrieved successfully');
    }

    /**
     * Update the specified DepartmentCCmail in storage.
     * PUT/PATCH /departmentCCmails/{id}
     *
     * @param int $id
     * @param UpdateDepartmentCCmailAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDepartmentCCmailAPIRequest $request)
    {
        $input = $request->all();

        /** @var DepartmentCCmail $departmentCCmail */
        $departmentCCmail = $this->departmentCCmailRepository->find($id);

        if (empty($departmentCCmail)) {
            return $this->sendError('Department C Cmail not found');
        }

        $departmentCCmail = $this->departmentCCmailRepository->update($input, $id);

        return $this->sendResponse($departmentCCmail->toArray(), 'DepartmentCCmail updated successfully');
    }

    /**
     * Remove the specified DepartmentCCmail from storage.
     * DELETE /departmentCCmails/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DepartmentCCmail $departmentCCmail */
        $departmentCCmail = $this->departmentCCmailRepository->find($id);

        if (empty($departmentCCmail)) {
            return $this->sendError('Department C Cmail not found');
        }

        $departmentCCmail->delete();

        return $this->sendSuccess('Department C Cmail deleted successfully');
    }
}
