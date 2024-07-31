<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductionFeedbackAPIRequest;
use App\Http\Requests\API\UpdateProductionFeedbackAPIRequest;
use App\Models\ProductionFeedback;
use App\Repositories\ProductionFeedbackRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response , Auth;

/**
 * Class ProductionFeedbackController
 * @package App\Http\Controllers\API
 */

class ProductionFeedbackAPIController extends AppBaseController
{
    /** @var  ProductionFeedbackRepository */
    private $productionFeedbackRepository;

    public function __construct(ProductionFeedbackRepository $productionFeedbackRepo)
    {
        $this->productionFeedbackRepository = $productionFeedbackRepo;
    }

    /**
     * Display a listing of the ProductionFeedback.
     * GET|HEAD /productionFeedbacks
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user(); 
       // $request['user_id'] = $user_data->id ; 
        // $productionFeedbacks = $this->productionFeedbackRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );
        $query =  ProductionFeedback::limit(100);
       // $query->where('summary_group_id' ,$request->summary_group_id);

       //ADD COMMENT USER DATA
        $query->with('userInfo')->where('summary_group_id' ,$request->summary_group_id);


        if(!empty($request->startDate)){
            $query->where('start_date','>=', $request->startDate);
        }

        if(!empty($request->endDate)){
            $query->where('end_date','<=', $request->endDate);
        }

        $result =  $query->get();

        return $this->sendResponse($result, 'Production Feedbacks retrieved successfully');
    }

    /**
     * Store a newly created ProductionFeedback in storage.
     * POST /productionFeedbacks
     *
     * @param CreateProductionFeedbackAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProductionFeedbackAPIRequest $request)
    {
        $input = $request->all();

        //CONCAT USER ID
        $input['user_id'] = Auth::user()->id;

        $productionFeedback = $this->productionFeedbackRepository->create($input);

        return $this->sendResponse($productionFeedback->toArray(), 'Production Feedback saved successfully');
    }

    /**
     * Display the specified ProductionFeedback.
     * GET|HEAD /productionFeedbacks/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ProductionFeedback $productionFeedback */
        $productionFeedback = $this->productionFeedbackRepository->find($id);

        if (empty($productionFeedback)) {
            return $this->sendError('Production Feedback not found');
        }

        return $this->sendResponse($productionFeedback->toArray(), 'Production Feedback retrieved successfully');
    }

    /**
     * Update the specified ProductionFeedback in storage.
     * PUT/PATCH /productionFeedbacks/{id}
     *
     * @param int $id
     * @param UpdateProductionFeedbackAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductionFeedbackAPIRequest $request)
    {
        $input = $request->all();

        /** @var ProductionFeedback $productionFeedback */
        $productionFeedback = $this->productionFeedbackRepository->find($id);

        if (empty($productionFeedback)) {
            return $this->sendError('Production Feedback not found');
        }

        $productionFeedback = $this->productionFeedbackRepository->update($input, $id);

        return $this->sendResponse($productionFeedback->toArray(), 'ProductionFeedback updated successfully');
    }

    /**
     * Remove the specified ProductionFeedback from storage.
     * DELETE /productionFeedbacks/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ProductionFeedback $productionFeedback */
        $productionFeedback = $this->productionFeedbackRepository->find($id);

        if (empty($productionFeedback)) {
            return $this->sendError('Production Feedback not found');
        }

        $productionFeedback->delete();

        return $this->sendSuccess('Production Feedback deleted successfully');
    }
}
