<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserManualFileAPIRequest;
use App\Http\Requests\API\UpdateUserManualFileAPIRequest;
use App\Models\UserManualFile;
use App\Repositories\UserManualFileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class UserManualFileController
 * @package App\Http\Controllers\API
 */

class UserManualFileAPIController extends AppBaseController
{
    /** @var  UserManualFileRepository */
    private $userManualFileRepository;

    public function __construct(UserManualFileRepository $userManualFileRepo)
    {
        $this->userManualFileRepository = $userManualFileRepo;
    }

    /**
     * Display a listing of the UserManualFile.
     * GET|HEAD /userManualFiles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $userManualFiles = $this->userManualFileRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($userManualFiles->toArray(), 'User Manual Files retrieved successfully');
    }

    /**
     * Store a newly created UserManualFile in storage.
     * POST /userManualFiles
     *
     * @param CreateUserManualFileAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserManualFileAPIRequest $request)
    {
        $input = $request->all();

        $userManualFile = $this->userManualFileRepository->create($input);

        return $this->sendResponse($userManualFile->toArray(), 'User Manual File saved successfully');
    }

    /**
     * Display the specified UserManualFile.
     * GET|HEAD /userManualFiles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UserManualFile $userManualFile */
        $userManualFile = $this->userManualFileRepository->find($id);

        if (empty($userManualFile)) {
            return $this->sendError('User Manual File not found');
        }

        return $this->sendResponse($userManualFile->toArray(), 'User Manual File retrieved successfully');
    }

    /**
     * Update the specified UserManualFile in storage.
     * PUT/PATCH /userManualFiles/{id}
     *
     * @param int $id
     * @param UpdateUserManualFileAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserManualFileAPIRequest $request)
    {
        $input = $request->all();

        /** @var UserManualFile $userManualFile */
        $userManualFile = $this->userManualFileRepository->find($id);

        if (empty($userManualFile)) {
            return $this->sendError('User Manual File not found');
        }

        $userManualFile = $this->userManualFileRepository->update($input, $id);

        return $this->sendResponse($userManualFile->toArray(), 'UserManualFile updated successfully');
    }

    /**
     * Remove the specified UserManualFile from storage.
     * DELETE /userManualFiles/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UserManualFile $userManualFile */
        $userManualFile = $this->userManualFileRepository->find($id);

        if (empty($userManualFile)) {
            return $this->sendError('User Manual File not found');
        }

        $userManualFile->delete();

        return $this->sendSuccess('User Manual File deleted successfully');
    }
}
