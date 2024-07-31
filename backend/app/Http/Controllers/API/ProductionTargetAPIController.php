<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProductionTargetAPIRequest;
use App\Http\Requests\API\UpdateProductionTargetAPIRequest;
use App\Models\ProductionTarget;
use App\Repositories\ProductionTargetRepository;
use App\Http\Resources\ProductionTargetResource;
use Illuminate\Http\Request;
use App\Imports\ProductionTargetImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class ProductionTargetController
 * @package App\Http\Controllers\API
 */

class ProductionTargetAPIController extends AppBaseController
{
    /** @var  ProductionTargetRepository */
    private $productionTargetRepository;

    public function __construct(ProductionTargetRepository $productionTargetRepo)
    {
        $this->productionTargetRepository = $productionTargetRepo;
    }

    /**
     * Display a listing of the ProductionTarget.
     * GET|HEAD /productionTargets
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $items = $this->productionTargetRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );
        $items = ProductionTargetResource::collection($items);    
        return $this->sendResponse($items , 'Production Targets retrieved successfully');
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new ProductionTargetImport, request()->file('csvFile'));
            return $this->sendResponse($data, 'Production Targets retrieved successfully'); 
        }else{
            return $this->sendResponse( 0 , 'Error'); 
        } 
    }

    /**
     * Store a newly created ProductionTarget in storage.
     * POST /productionTargets
     *
     * @param CreateProductionTargetAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProductionTargetAPIRequest $request)
    {
        $input = $request->all();

        $productionTarget = $this->productionTargetRepository->create($input);

        return $this->sendResponse($productionTarget->toArray(), 'Production Target saved successfully');
    }

    /**
     * Display the specified ProductionTarget.
     * GET|HEAD /productionTargets/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ProductionTarget $productionTarget */
        $productionTarget = $this->productionTargetRepository->find($id);

        if (empty($productionTarget)) {
            return $this->sendError('Production Target not found');
        }

        return $this->sendResponse($productionTarget->toArray(), 'Production Target retrieved successfully');
    }

    /**
     * Update the specified ProductionTarget in storage.
     * PUT/PATCH /productionTargets/{id}
     *
     * @param int $id
     * @param UpdateProductionTargetAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProductionTargetAPIRequest $request)
    {
        $input = $request->all();

        /** @var ProductionTarget $productionTarget */
        $productionTarget = $this->productionTargetRepository->find($id);

        if (empty($productionTarget)) {
            return $this->sendError('Production Target not found');
        }

        $productionTarget = $this->productionTargetRepository->update($input, $id);

        return $this->sendResponse($productionTarget->toArray(), 'ProductionTarget updated successfully');
    }

    /**
     * Remove the specified ProductionTarget from storage.
     * DELETE /productionTargets/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ProductionTarget $productionTarget */
        $productionTarget = $this->productionTargetRepository->find($id);

        if (empty($productionTarget)) {
            return $this->sendError('Production Target not found');
        }

        $productionTarget->delete();

        return $this->sendSuccess('Production Target deleted successfully');
    }
}
