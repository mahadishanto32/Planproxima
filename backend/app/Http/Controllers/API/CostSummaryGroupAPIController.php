<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCostSummaryGroupAPIRequest;
use App\Http\Requests\API\UpdateCostSummaryGroupAPIRequest;
use App\Models\CostSummaryGroup;
use App\Repositories\CostSummaryGroupRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class CostSummaryGroupController
 * @package App\Http\Controllers\API
 */

class CostSummaryGroupAPIController extends AppBaseController
{
    /** @var  CostSummaryGroupRepository */
    private $costSummaryGroupRepository;

    public function __construct(CostSummaryGroupRepository $costSummaryGroupRepo)
    {
        $this->costSummaryGroupRepository = $costSummaryGroupRepo;
    }

    /**
     * Display a listing of the CostSummaryGroup.
     * GET|HEAD /costSummaryGroups
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $costSummaryGroups = $this->costSummaryGroupRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($costSummaryGroups->toArray(), 'Cost Summary Groups retrieved successfully');
    }

    /**
     * Store a newly created CostSummaryGroup in storage.
     * POST /costSummaryGroups
     *
     * @param CreateCostSummaryGroupAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCostSummaryGroupAPIRequest $request)
    {
        $input = $request->all();

        $costSummaryGroup = $this->costSummaryGroupRepository->create($input);

        return $this->sendResponse($costSummaryGroup->toArray(), 'Cost Summary Group saved successfully');
    }

    /**
     * Display the specified CostSummaryGroup.
     * GET|HEAD /costSummaryGroups/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CostSummaryGroup $costSummaryGroup */
        $costSummaryGroup = $this->costSummaryGroupRepository->find($id);

        if (empty($costSummaryGroup)) {
            return $this->sendError('Cost Summary Group not found');
        }

        return $this->sendResponse($costSummaryGroup->toArray(), 'Cost Summary Group retrieved successfully');
    }

    /**
     * Update the specified CostSummaryGroup in storage.
     * PUT/PATCH /costSummaryGroups/{id}
     *
     * @param int $id
     * @param UpdateCostSummaryGroupAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCostSummaryGroupAPIRequest $request)
    {
        $input = $request->all();

        /** @var CostSummaryGroup $costSummaryGroup */
        $costSummaryGroup = $this->costSummaryGroupRepository->find($id);

        if (empty($costSummaryGroup)) {
            return $this->sendError('Cost Summary Group not found');
        }

        $costSummaryGroup = $this->costSummaryGroupRepository->update($input, $id);

        return $this->sendResponse($costSummaryGroup->toArray(), 'CostSummaryGroup updated successfully');
    }

    /**
     * Remove the specified CostSummaryGroup from storage.
     * DELETE /costSummaryGroups/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CostSummaryGroup $costSummaryGroup */
        $costSummaryGroup = $this->costSummaryGroupRepository->find($id);

        if (empty($costSummaryGroup)) {
            return $this->sendError('Cost Summary Group not found');
        }

        $costSummaryGroup->delete();

        return $this->sendSuccess('Cost Summary Group deleted successfully');
    }
}
