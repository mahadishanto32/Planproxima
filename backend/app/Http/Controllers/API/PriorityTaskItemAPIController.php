<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePriorityTaskItemAPIRequest;
use App\Http\Requests\API\UpdatePriorityTaskItemAPIRequest;
use App\Models\PriorityTaskItem;
use App\Repositories\PriorityTaskItemRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class PriorityTaskItemController
 * @package App\Http\Controllers\API
 */

class PriorityTaskItemAPIController extends AppBaseController
{
    /** @var  PriorityTaskItemRepository */
    private $priorityTaskItemRepository;

    public function __construct(PriorityTaskItemRepository $priorityTaskItemRepo)
    {
        $this->priorityTaskItemRepository = $priorityTaskItemRepo;
    }

    /**
     * Display a listing of the PriorityTaskItem.
     * GET|HEAD /priorityTaskItems
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $priorityTaskItems = $this->priorityTaskItemRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($priorityTaskItems->toArray(), 'Priority Task Items retrieved successfully');
    }

    /**
     * Store a newly created PriorityTaskItem in storage.
     * POST /priorityTaskItems
     *
     * @param CreatePriorityTaskItemAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePriorityTaskItemAPIRequest $request)
    {
        $input = $request->all();

        $priorityTaskItem = $this->priorityTaskItemRepository->create($input);

        return $this->sendResponse($priorityTaskItem->toArray(), 'Priority Task Item saved successfully');
    }

    /**
     * Display the specified PriorityTaskItem.
     * GET|HEAD /priorityTaskItems/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PriorityTaskItem $priorityTaskItem */
        $priorityTaskItem = $this->priorityTaskItemRepository->find($id);

        if (empty($priorityTaskItem)) {
            return $this->sendError('Priority Task Item not found');
        }

        return $this->sendResponse($priorityTaskItem->toArray(), 'Priority Task Item retrieved successfully');
    }

    /**
     * Update the specified PriorityTaskItem in storage.
     * PUT/PATCH /priorityTaskItems/{id}
     *
     * @param int $id
     * @param UpdatePriorityTaskItemAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePriorityTaskItemAPIRequest $request)
    {
        $input = $request->all();

        /** @var PriorityTaskItem $priorityTaskItem */
        $priorityTaskItem = $this->priorityTaskItemRepository->find($id);

        if (empty($priorityTaskItem)) {
            return $this->sendError('Priority Task Item not found');
        }

        $priorityTaskItem = $this->priorityTaskItemRepository->update($input, $id);

        return $this->sendResponse($priorityTaskItem->toArray(), 'PriorityTaskItem updated successfully');
    }

    /**
     * Remove the specified PriorityTaskItem from storage.
     * DELETE /priorityTaskItems/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PriorityTaskItem $priorityTaskItem */
        $priorityTaskItem = $this->priorityTaskItemRepository->find($id);

        if (empty($priorityTaskItem)) {
            return $this->sendError('Priority Task Item not found');
        }

        $priorityTaskItem->delete();

        return $this->sendSuccess('Priority Task Item deleted successfully');
    }
}
