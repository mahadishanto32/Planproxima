<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMosDataLogAPIRequest;
use App\Http\Requests\API\UpdateMosDataLogAPIRequest;
use App\Models\MosDataLog;
use App\Repositories\MosDataLogRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class MosDataLogController
 * @package App\Http\Controllers\API
 */

class MosDataLogAPIController extends AppBaseController
{
    /** @var  MosDataLogRepository */
    private $mosDataLogRepository;

    public function __construct(MosDataLogRepository $mosDataLogRepo)
    {
        $this->mosDataLogRepository = $mosDataLogRepo;
    }

    /**
     * Display a listing of the MosDataLog.
     * GET|HEAD /mosDataLogs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $mosDataLogs = $this->mosDataLogRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($mosDataLogs->toArray(), 'Mos Data Logs retrieved successfully');
    }

    /**
     * Store a newly created MosDataLog in storage.
     * POST /mosDataLogs
     *
     * @param CreateMosDataLogAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMosDataLogAPIRequest $request)
    {
        $input = $request->all();

        $mosDataLog = $this->mosDataLogRepository->create($input);

        return $this->sendResponse($mosDataLog->toArray(), 'Mos Data Log saved successfully');
    }

    /**
     * Display the specified MosDataLog.
     * GET|HEAD /mosDataLogs/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MosDataLog $mosDataLog */
        $mosDataLog = $this->mosDataLogRepository->find($id);

        if (empty($mosDataLog)) {
            return $this->sendError('Mos Data Log not found');
        }

        return $this->sendResponse($mosDataLog->toArray(), 'Mos Data Log retrieved successfully');
    }

    /**
     * Update the specified MosDataLog in storage.
     * PUT/PATCH /mosDataLogs/{id}
     *
     * @param int $id
     * @param UpdateMosDataLogAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMosDataLogAPIRequest $request)
    {
        $input = $request->all();

        /** @var MosDataLog $mosDataLog */
        $mosDataLog = $this->mosDataLogRepository->find($id);

        if (empty($mosDataLog)) {
            return $this->sendError('Mos Data Log not found');
        }

        $mosDataLog = $this->mosDataLogRepository->update($input, $id);

        return $this->sendResponse($mosDataLog->toArray(), 'MosDataLog updated successfully');
    }

    /**
     * Remove the specified MosDataLog from storage.
     * DELETE /mosDataLogs/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MosDataLog $mosDataLog */
        $mosDataLog = $this->mosDataLogRepository->find($id);

        if (empty($mosDataLog)) {
            return $this->sendError('Mos Data Log not found');
        }

        $mosDataLog->delete();

        return $this->sendSuccess('Mos Data Log deleted successfully');
    }
}
