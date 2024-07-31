<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserManualAPIRequest;
use App\Http\Requests\API\UpdateUserManualAPIRequest;
use App\Models\UserManual;
use App\Repositories\UserManualRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class UserManualController
 * @package App\Http\Controllers\API
 */

class UserManualAPIController extends AppBaseController
{
    /** @var  UserManualRepository */
    private $userManualRepository;

    public function __construct(UserManualRepository $userManualRepo)
    {
        $this->userManualRepository = $userManualRepo;
    }

    /**
     * Display a listing of the UserManual.
     * GET|HEAD /userManuals
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $userManuals = $this->userManualRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($userManuals->toArray(), 'User Manuals retrieved successfully');
    }

    /**
     * Store a newly created UserManual in storage.
     * POST /userManuals
     *
     * @param CreateUserManualAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserManualAPIRequest $request)
    {
        $input = $request->all();

        if($request->hasFile('thumbnail')) {
            $logoFile  = $request->file('thumbnail');
            $fileName   = $this->uploadFile($logoFile, 'thumbnail', 'thumbnail_');

            $input['thumbnail']  = $fileName;
        }

        $userManual = $this->userManualRepository->create($input);

        return $this->sendResponse($userManual->toArray(), 'User Manual saved successfully');
    }

    public function userManual(){
        $userManual = UserManual::latest()->first();

        if (empty($userManual)) {
            return $this->sendError('User Manual not found');
        }

        return $this->sendResponse($userManual->toArray(), 'User Manual retrieved successfully');
    }

    /**
     * Display the specified UserManual.
     * GET|HEAD /userManuals/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UserManual $userManual */
        $userManual = $this->userManualRepository->find($id);

        if (empty($userManual)) {
            return $this->sendError('User Manual not found');
        }

        return $this->sendResponse($userManual->toArray(), 'User Manual retrieved successfully');
    }

    /**
     * Update the specified UserManual in storage.
     * PUT/PATCH /userManuals/{id}
     *
     * @param int $id
     * @param UpdateUserManualAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserManualAPIRequest $request)
    {
        $input = $request->all(); 

        //return response()->json($input);
        $userManual = $this->userManualRepository->find($id);

        if (empty($userManual)) {
            return $this->sendError('User Manual not found');
        }

        //$oldLogo    = $userManual->thumbnail;
        
        //return response()->json($request->file('thumbnail'));
        // if ($request->thumbnail) { 
        //     $value = $request->thumbnail; 
        //     $file_caption = $value->getClientOriginalName();
        //     $extension = $value->getClientOriginalExtension();
        //     $file_name = Storage::disk('public')->put('report', $value); 
        //     $this->monthlyReportFileRepository->create($file); 
        // }

        // if($request->hasFile('thumbnail')) {            
        //     $logoFile  = $request->file('thumbnail');
        //     $fileName   = $this->uploadFile($logoFile, 'thumbnail', 'thumbnail_');
        //     $input['thumbnail']  = $fileName;

        //     //$this->removeFile($oldLogo, 'thumbnail');
        // }

        $userManual = $this->userManualRepository->update($input, $id);

        return $this->sendResponse($userManual->toArray(), 'UserManual updated successfully');
    }

    /**
     * Remove the specified UserManual from storage.
     * DELETE /userManuals/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UserManual $userManual */
        $userManual = $this->userManualRepository->find($id);

        if (empty($userManual)) {
            return $this->sendError('User Manual not found');
        }

        $userManual->delete();

        return $this->sendSuccess('User Manual deleted successfully');
    }
}
