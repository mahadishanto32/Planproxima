<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatebuyerEnquiryColumnAPIRequest;
use App\Http\Requests\API\UpdatebuyerEnquiryColumnAPIRequest;
use App\Models\buyerEnquiryColumn;
use App\Repositories\buyerEnquiryColumnRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class buyerEnquiryColumnController
 * @package App\Http\Controllers\API
 */

class buyerEnquiryColumnAPIController extends AppBaseController
{
    /** @var  buyerEnquiryColumnRepository */
    private $buyerEnquiryColumnRepository;

    public function __construct(buyerEnquiryColumnRepository $buyerEnquiryColumnRepo)
    {
        $this->buyerEnquiryColumnRepository = $buyerEnquiryColumnRepo;
    }

    /**
     * Display a listing of the buyerEnquiryColumn.
     * GET|HEAD /buyerEnquiryColumns
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $buyerEnquiryColumns = $this->buyerEnquiryColumnRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($buyerEnquiryColumns->toArray(), 'Buyer Enquiry Columns retrieved successfully');
    }

    /**
     * Store a newly created buyerEnquiryColumn in storage.
     * POST /buyerEnquiryColumns
     *
     * @param CreatebuyerEnquiryColumnAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatebuyerEnquiryColumnAPIRequest $request)
    {
        $input = $request->all();

        $buyerEnquiryColumn = $this->buyerEnquiryColumnRepository->create($input);

        return $this->sendResponse($buyerEnquiryColumn->toArray(), 'Buyer Enquiry Column saved successfully');
    }

    /**
     * Display the specified buyerEnquiryColumn.
     * GET|HEAD /buyerEnquiryColumns/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var buyerEnquiryColumn $buyerEnquiryColumn */
        $buyerEnquiryColumn = $this->buyerEnquiryColumnRepository->find($id);

        if (empty($buyerEnquiryColumn)) {
            return $this->sendError('Buyer Enquiry Column not found');
        }

        return $this->sendResponse($buyerEnquiryColumn->toArray(), 'Buyer Enquiry Column retrieved successfully');
    }

    /**
     * Update the specified buyerEnquiryColumn in storage.
     * PUT/PATCH /buyerEnquiryColumns/{id}
     *
     * @param int $id
     * @param UpdatebuyerEnquiryColumnAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatebuyerEnquiryColumnAPIRequest $request)
    {
        $input = $request->all();

        /** @var buyerEnquiryColumn $buyerEnquiryColumn */
        $buyerEnquiryColumn = $this->buyerEnquiryColumnRepository->find($id);

        if (empty($buyerEnquiryColumn)) {
            return $this->sendError('Buyer Enquiry Column not found');
        }

        $buyerEnquiryColumn = $this->buyerEnquiryColumnRepository->update($input, $id);

        return $this->sendResponse($buyerEnquiryColumn->toArray(), 'buyerEnquiryColumn updated successfully');
    }

    /**
     * Remove the specified buyerEnquiryColumn from storage.
     * DELETE /buyerEnquiryColumns/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var buyerEnquiryColumn $buyerEnquiryColumn */
        $buyerEnquiryColumn = $this->buyerEnquiryColumnRepository->find($id);

        if (empty($buyerEnquiryColumn)) {
            return $this->sendError('Buyer Enquiry Column not found');
        }

        $buyerEnquiryColumn->delete();

        return $this->sendSuccess('Buyer Enquiry Column deleted successfully');
    }
}
