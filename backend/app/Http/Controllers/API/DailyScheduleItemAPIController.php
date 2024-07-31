<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDailyScheduleItemAPIRequest;
use App\Http\Requests\API\UpdateDailyScheduleItemAPIRequest;
use App\Models\DailyScheduleItem;
use App\Repositories\DailyScheduleItemRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DailyScheduleItemController
 * @package App\Http\Controllers\API
 */

class DailyScheduleItemAPIController extends AppBaseController
{
    /** @var  DailyScheduleItemRepository */
    private $dailyScheduleItemRepository;

    public function __construct(DailyScheduleItemRepository $dailyScheduleItemRepo)
    {
        $this->dailyScheduleItemRepository = $dailyScheduleItemRepo;
    }

    /**
     * Display a listing of the DailyScheduleItem.
     * GET|HEAD /dailyScheduleItems
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $dailyScheduleItems = $this->dailyScheduleItemRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($dailyScheduleItems->toArray(), 'Daily Schedule Items retrieved successfully');
    }

    /**
     * Store a newly created DailyScheduleItem in storage.
     * POST /dailyScheduleItems
     *
     * @param CreateDailyScheduleItemAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDailyScheduleItemAPIRequest $request)
    {
        $input = $request->all();

        $dailyScheduleItem = $this->dailyScheduleItemRepository->create($input);

        return $this->sendResponse($dailyScheduleItem->toArray(), 'Daily Schedule Item saved successfully');
    }

    /**
     * Display the specified DailyScheduleItem.
     * GET|HEAD /dailyScheduleItems/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DailyScheduleItem $dailyScheduleItem */
        $dailyScheduleItem = $this->dailyScheduleItemRepository->find($id);

        if (empty($dailyScheduleItem)) {
            return $this->sendError('Daily Schedule Item not found');
        }

        return $this->sendResponse($dailyScheduleItem->toArray(), 'Daily Schedule Item retrieved successfully');
    }

    /**
     * Update the specified DailyScheduleItem in storage.
     * PUT/PATCH /dailyScheduleItems/{id}
     *
     * @param int $id
     * @param UpdateDailyScheduleItemAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDailyScheduleItemAPIRequest $request)
    {
        $input = $request->all();
        // return $this->sendResponse($request->all(), 'DailyScheduleItem updated successfully');
        /** @var DailyScheduleItem $dailyScheduleItem */
        $dailyScheduleItem = $this->dailyScheduleItemRepository->find($id);

        if (empty($dailyScheduleItem)) {
            return $this->sendError('Daily Schedule Item not found');
        }

        $dailyScheduleItem = $this->dailyScheduleItemRepository->update($input, $id);

        return $this->sendResponse($dailyScheduleItem->toArray(), 'DailyScheduleItem updated successfully');
    }

    /**
     * Remove the specified DailyScheduleItem from storage.
     * DELETE /dailyScheduleItems/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DailyScheduleItem $dailyScheduleItem */
        $dailyScheduleItem = $this->dailyScheduleItemRepository->find($id);

        if (empty($dailyScheduleItem)) {
            return $this->sendError('Daily Schedule Item not found');
        }

        $dailyScheduleItem->delete();

        return $this->sendSuccess('Daily Schedule Item deleted successfully');
    }
}
