<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDailyScheduleHeaderAPIRequest;
use App\Http\Requests\API\UpdateDailyScheduleHeaderAPIRequest;
use App\Models\DailyScheduleHeader;
use App\Repositories\DailyScheduleHeaderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DailyScheduleHeaderController
 * @package App\Http\Controllers\API
 */

class DailyScheduleHeaderAPIController extends AppBaseController
{
    /** @var  DailyScheduleHeaderRepository */
    private $dailyScheduleHeaderRepository;

    public function __construct(DailyScheduleHeaderRepository $dailyScheduleHeaderRepo)
    {
        $this->dailyScheduleHeaderRepository = $dailyScheduleHeaderRepo;
    }

    /**
     * Display a listing of the DailyScheduleHeader.
     * GET|HEAD /dailyScheduleHeaders
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $dailyScheduleHeaders = $this->dailyScheduleHeaderRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($dailyScheduleHeaders->toArray(), 'Daily Schedule Headers retrieved successfully');
    }

    /**
     * Store a newly created DailyScheduleHeader in storage.
     * POST /dailyScheduleHeaders
     *
     * @param CreateDailyScheduleHeaderAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDailyScheduleHeaderAPIRequest $request)
    {
        $input = $request->all();

        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->create($input);

        return $this->sendResponse($dailyScheduleHeader->toArray(), 'Daily Schedule Header saved successfully');
    }

    /**
     * Display the specified DailyScheduleHeader.
     * GET|HEAD /dailyScheduleHeaders/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DailyScheduleHeader $dailyScheduleHeader */
        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->find($id);

        if (empty($dailyScheduleHeader)) {
            return $this->sendError('Daily Schedule Header not found');
        }

        return $this->sendResponse($dailyScheduleHeader->toArray(), 'Daily Schedule Header retrieved successfully');
    }

    /**
     * Update the specified DailyScheduleHeader in storage.
     * PUT/PATCH /dailyScheduleHeaders/{id}
     *
     * @param int $id
     * @param UpdateDailyScheduleHeaderAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDailyScheduleHeaderAPIRequest $request)
    {
        $input = $request->all();

        /** @var DailyScheduleHeader $dailyScheduleHeader */
        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->find($id);

        if (empty($dailyScheduleHeader)) {
            return $this->sendError('Daily Schedule Header not found');
        }

        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->update($input, $id);

        return $this->sendResponse($dailyScheduleHeader->toArray(), 'DailyScheduleHeader updated successfully');
    }

    /**
     * Remove the specified DailyScheduleHeader from storage.
     * DELETE /dailyScheduleHeaders/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DailyScheduleHeader $dailyScheduleHeader */
        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->find($id);

        if (empty($dailyScheduleHeader)) {
            return $this->sendError('Daily Schedule Header not found');
        }

        $dailyScheduleHeader->delete();

        return $this->sendSuccess('Daily Schedule Header deleted successfully');
    }
}
