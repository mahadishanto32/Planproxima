<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWastege_relationAPIRequest;
use App\Http\Requests\API\UpdateWastege_relationAPIRequest;
use App\Models\Wastege_relation;
use App\Models\WastageSummaryGroup;
use App\Repositories\Wastege_relationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class Wastege_relationController
 * @package App\Http\Controllers\API
 */

class Wastege_relationAPIController extends AppBaseController
{
    /** @var  Wastege_relationRepository */
    private $wastegeRelationRepository;

    public function __construct(Wastege_relationRepository $wastegeRelationRepo)
    {
        $this->wastegeRelationRepository = $wastegeRelationRepo;
    }

    /**
     * Display a listing of the Wastege_relation.
     * GET|HEAD /wastegeRelations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $wastegeRelations = $this->wastegeRelationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($wastegeRelations->toArray(), 'Wastege Relations retrieved successfully');
    }

    public function WastageSummaryGroup($id){
        $wastege = WastageSummaryGroup::where('summary_group_id', $id)->get();

        return $this->sendResponse($wastege , 'Wastege Relations retrieved successfully');
    }

    /**
     * Store a newly created Wastege_relation in storage.
     * POST /wastegeRelations
     *
     * @param CreateWastege_relationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateWastege_relationAPIRequest $request)
    {
        $input = $request->all();

        $wastegeRelation = $this->wastegeRelationRepository->create($input);

        return $this->sendResponse($wastegeRelation->toArray(), 'Wastege Relation saved successfully');
    }

    /**
     * Display the specified Wastege_relation.
     * GET|HEAD /wastegeRelations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Wastege_relation $wastegeRelation */
        $wastegeRelation = $this->wastegeRelationRepository->find($id);

        if (empty($wastegeRelation)) {
            return $this->sendError('Wastege Relation not found');
        }

        return $this->sendResponse($wastegeRelation->toArray(), 'Wastege Relation retrieved successfully');
    }

    /**
     * Update the specified Wastege_relation in storage.
     * PUT/PATCH /wastegeRelations/{id}
     *
     * @param int $id
     * @param UpdateWastege_relationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWastege_relationAPIRequest $request)
    {
        $input = $request->all();

        /** @var Wastege_relation $wastegeRelation */
        $wastegeRelation = $this->wastegeRelationRepository->find($id);

        if (empty($wastegeRelation)) {
            return $this->sendError('Wastege Relation not found');
        }

        $wastegeRelation = $this->wastegeRelationRepository->update($input, $id);

        return $this->sendResponse($wastegeRelation->toArray(), 'Wastege_relation updated successfully');
    }

    /**
     * Remove the specified Wastege_relation from storage.
     * DELETE /wastegeRelations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Wastege_relation $wastegeRelation */
        $wastegeRelation = $this->wastegeRelationRepository->find($id);

        if (empty($wastegeRelation)) {
            return $this->sendError('Wastege Relation not found');
        }

        $wastegeRelation->delete();

        return $this->sendSuccess('Wastege Relation deleted successfully');
    }
}
