<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductionPlansAPIRequest;
use App\Http\Requests\API\UpdateProductionPlansAPIRequest;
use App\Models\ProductionPlans;
use App\Repositories\ProductionPlansRepository;
use Illuminate\Http\Request;
use App\Imports\ProductionPlanImport; 
use App\Http\Resources\ProductionPlansResource;
use App\Http\Controllers\AppBaseController;
use Maatwebsite\Excel\Facades\Excel;
use Auth ;
use Response;

/**
 * Class ProductionPlansController
 * @package App\Http\Controllers\API
 */

class ProductionPlansAPIController extends AppBaseController
{
    /** @var  ProductionPlansRepository */
    private $productionPlansRepository;

    public function __construct(ProductionPlansRepository $productionPlansRepo)
    {
        $this->productionPlansRepository = $productionPlansRepo;
    }

    /**
     * Display a listing of the ProductionPlans.
     * GET|HEAD /productionPlans
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $productionPlans = $this->productionPlansRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        ); 
        $data_return  =   ProductionPlansResource::collection($productionPlans);

        return $this->sendResponse($data_return , 'Production Plans retrieved successfully');
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new ProductionPlanImport, request()->file('csvFile'));
 
            return $this->sendResponse($data, 'Production Plans retrieved successfully'); 
        }else{
            return $this->sendResponse( 0 , 'Error'); 
        } 
    }

    /**
     * Store a newly created ProductionPlans in storage.
     * POST /productionPlans
     *
     * @param CreateProductionPlansAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProductionPlansAPIRequest $request)
    {
        $input = $request->all();

        $productionPlans = $this->productionPlansRepository->create($input);

        return $this->sendResponse($productionPlans->toArray(), 'Production Plans saved successfully');
    }

    /**
     * Display the specified ProductionPlans.
     * GET|HEAD /productionPlans/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ProductionPlans $productionPlans */
        $productionPlans = $this->productionPlansRepository->find($id);

        if (empty($productionPlans)) {
            return $this->sendError('Production Plans not found');
        }

        return $this->sendResponse($productionPlans->toArray(), 'Production Plans retrieved successfully');
    }

    /**
     * Update the specified ProductionPlans in storage.
     * PUT/PATCH /productionPlans/{id}
     *
     * @param int $id
     * @param UpdateProductionPlansAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductionPlansAPIRequest $request)
    {
        $input = $request->all();

        /** @var ProductionPlans $productionPlans */
        $productionPlans = $this->productionPlansRepository->find($id);

        if (empty($productionPlans)) {
            return $this->sendError('Production Plans not found');
        }

        $productionPlans = $this->productionPlansRepository->update($input, $id);

        return $this->sendResponse($productionPlans->toArray(), 'ProductionPlans updated successfully');
    }

    /**
     * Remove the specified ProductionPlans from storage.
     * DELETE /productionPlans/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ProductionPlans $productionPlans */
        $productionPlans = $this->productionPlansRepository->find($id);

        if (empty($productionPlans)) {
            return $this->sendError('Production Plans not found');
        }

        $productionPlans->delete();

        return $this->sendSuccess('Production Plans deleted successfully');
    }
}
