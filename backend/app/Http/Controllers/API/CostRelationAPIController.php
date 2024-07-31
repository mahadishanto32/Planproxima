<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCostRelationAPIRequest;
use App\Http\Requests\API\UpdateCostRelationAPIRequest;
use App\Models\CostRelation;
use App\Repositories\CostRelationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class CostRelationController
 * @package App\Http\Controllers\API
 */

class CostRelationAPIController extends AppBaseController
{
    /** @var  CostRelationRepository */
    private $costRelationRepository;

    public function __construct(CostRelationRepository $costRelationRepo)
    {
        $this->costRelationRepository = $costRelationRepo;
    }

    /**
     * Display a listing of the CostRelation.
     * GET|HEAD /costRelations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $costRelations = $this->costRelationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($costRelations->toArray(), 'Cost Relations retrieved successfully');
    }

    /**
     * Store a newly created CostRelation in storage.
     * POST /costRelations
     *
     * @param CreateCostRelationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCostRelationAPIRequest $request)
    {
        $input = $request->all();

        $costRelation = $this->costRelationRepository->create($input);

        return $this->sendResponse($costRelation->toArray(), 'Cost Relation saved successfully');
    }

    /**
     * Display the specified CostRelation.
     * GET|HEAD /costRelations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CostRelation $costRelation */
        $costRelation = $this->costRelationRepository->find($id);

        if (empty($costRelation)) {
            return $this->sendError('Cost Relation not found');
        }

        return $this->sendResponse($costRelation->toArray(), 'Cost Relation retrieved successfully');
    }

    /**
     * Update the specified CostRelation in storage.
     * PUT/PATCH /costRelations/{id}
     *
     * @param int $id
     * @param UpdateCostRelationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCostRelationAPIRequest $request)
    {
        $input = $request->all();

        /** @var CostRelation $costRelation */
        $costRelation = $this->costRelationRepository->find($id);

        if (empty($costRelation)) {
            return $this->sendError('Cost Relation not found');
        }

        $costRelation = $this->costRelationRepository->update($input, $id);

        return $this->sendResponse($costRelation->toArray(), 'CostRelation updated successfully');
    }

    /**
     * Remove the specified CostRelation from storage.
     * DELETE /costRelations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CostRelation $costRelation */
        $costRelation = $this->costRelationRepository->find($id);

        if (empty($costRelation)) {
            return $this->sendError('Cost Relation not found');
        }

        $costRelation->delete();

        return $this->sendSuccess('Cost Relation deleted successfully');
    }
}
