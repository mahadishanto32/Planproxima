<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDailyScheduleTypeAPIRequest;
use App\Http\Requests\API\UpdateDailyScheduleTypeAPIRequest;
use App\Models\DailyScheduleType;
use App\Repositories\DailyScheduleTypeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DailyScheduleTypeController
 * @package App\Http\Controllers\API
 */

class DailyScheduleTypeAPIController extends AppBaseController
{
    /** @var  DailyScheduleTypeRepository */
    private $dailyScheduleTypeRepository;

    public function __construct(DailyScheduleTypeRepository $dailyScheduleTypeRepo)
    {
        $this->dailyScheduleTypeRepository = $dailyScheduleTypeRepo;
    }

    /**
     * Display a listing of the DailyScheduleType.
     * GET|HEAD /dailyScheduleTypes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $dailyScheduleTypes = $this->dailyScheduleTypeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($dailyScheduleTypes->toArray(), 'Daily Schedule Types retrieved successfully');
    }

    /**
     * Store a newly created DailyScheduleType in storage.
     * POST /dailyScheduleTypes
     *
     * @param CreateDailyScheduleTypeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDailyScheduleTypeAPIRequest $request)
    {
        $input = $request->all();

        $dailyScheduleType = $this->dailyScheduleTypeRepository->create($input);

        return $this->sendResponse($dailyScheduleType->toArray(), 'Daily Schedule Type saved successfully');
    }

    /**
     * Display the specified DailyScheduleType.
     * GET|HEAD /dailyScheduleTypes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DailyScheduleType $dailyScheduleType */
        $dailyScheduleType = $this->dailyScheduleTypeRepository->find($id);

        if (empty($dailyScheduleType)) {
            return $this->sendError('Daily Schedule Type not found');
        }

        return $this->sendResponse($dailyScheduleType->toArray(), 'Daily Schedule Type retrieved successfully');
    }

    /**
     * Update the specified DailyScheduleType in storage.
     * PUT/PATCH /dailyScheduleTypes/{id}
     *
     * @param int $id
     * @param UpdateDailyScheduleTypeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDailyScheduleTypeAPIRequest $request)
    {
        $input = $request->all();

        /** @var DailyScheduleType $dailyScheduleType */
        $dailyScheduleType = $this->dailyScheduleTypeRepository->find($id);

        if (empty($dailyScheduleType)) {
            return $this->sendError('Daily Schedule Type not found');
        }

        $dailyScheduleType = $this->dailyScheduleTypeRepository->update($input, $id);

        return $this->sendResponse($dailyScheduleType->toArray(), 'DailyScheduleType updated successfully');
    }

    /**
     * Remove the specified DailyScheduleType from storage.
     * DELETE /dailyScheduleTypes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DailyScheduleType $dailyScheduleType */
        $dailyScheduleType = $this->dailyScheduleTypeRepository->find($id);

        if (empty($dailyScheduleType)) {
            return $this->sendError('Daily Schedule Type not found');
        }

        $dailyScheduleType->delete();

        return $this->sendSuccess('Daily Schedule Type deleted successfully');
    }
}
