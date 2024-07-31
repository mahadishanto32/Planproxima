<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateConsumption_relationAPIRequest;
use App\Http\Requests\API\UpdateConsumption_relationAPIRequest;
use App\Models\Consumption_relation;
use App\Repositories\Consumption_relationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class Consumption_relationController
 * @package App\Http\Controllers\API
 */

class Consumption_relationAPIController extends AppBaseController
{
    /** @var  Consumption_relationRepository */
    private $consumptionRelationRepository;

    public function __construct(Consumption_relationRepository $consumptionRelationRepo)
    {
        $this->consumptionRelationRepository = $consumptionRelationRepo;
    }

    /**
     * Display a listing of the Consumption_relation.
     * GET|HEAD /consumptionRelations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $consumptionRelations = $this->consumptionRelationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($consumptionRelations->toArray(), 'Consumption Relations retrieved successfully');
    }

    /**
     * Store a newly created Consumption_relation in storage.
     * POST /consumptionRelations
     *
     * @param CreateConsumption_relationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateConsumption_relationAPIRequest $request)
    {
        $input = $request->all();

        $consumptionRelation = $this->consumptionRelationRepository->create($input);

        return $this->sendResponse($consumptionRelation->toArray(), 'Consumption Relation saved successfully');
    }

    /**
     * Display the specified Consumption_relation.
     * GET|HEAD /consumptionRelations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Consumption_relation $consumptionRelation */
        $consumptionRelation = $this->consumptionRelationRepository->find($id);

        if (empty($consumptionRelation)) {
            return $this->sendError('Consumption Relation not found');
        }

        return $this->sendResponse($consumptionRelation->toArray(), 'Consumption Relation retrieved successfully');
    }

    /**
     * Update the specified Consumption_relation in storage.
     * PUT/PATCH /consumptionRelations/{id}
     *
     * @param int $id
     * @param UpdateConsumption_relationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConsumption_relationAPIRequest $request)
    {
        $input = $request->all();

        /** @var Consumption_relation $consumptionRelation */
        $consumptionRelation = $this->consumptionRelationRepository->find($id);

        if (empty($consumptionRelation)) {
            return $this->sendError('Consumption Relation not found');
        }

        $consumptionRelation = $this->consumptionRelationRepository->update($input, $id);

        return $this->sendResponse($consumptionRelation->toArray(), 'Consumption_relation updated successfully');
    }

    /**
     * Remove the specified Consumption_relation from storage.
     * DELETE /consumptionRelations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Consumption_relation $consumptionRelation */
        $consumptionRelation = $this->consumptionRelationRepository->find($id);

        if (empty($consumptionRelation)) {
            return $this->sendError('Consumption Relation not found');
        }

        $consumptionRelation->delete();

        return $this->sendSuccess('Consumption Relation deleted successfully');
    }
}
