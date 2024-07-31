<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProduction_product_nameAPIRequest;
use App\Http\Requests\API\UpdateProduction_product_nameAPIRequest;
use App\Models\Production_product_name;
use App\Repositories\Production_product_nameRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class Production_product_nameController
 * @package App\Http\Controllers\API
 */

class Production_product_nameAPIController extends AppBaseController
{
    /** @var  Production_product_nameRepository */
    private $productionProductNameRepository;

    public function __construct(Production_product_nameRepository $productionProductNameRepo)
    {
        $this->productionProductNameRepository = $productionProductNameRepo;
    }

    /**
     * Display a listing of the Production_product_name.
     * GET|HEAD /productionProductNames
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $productionProductNames = $this->productionProductNameRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($productionProductNames->toArray(), 'Production Product Names retrieved successfully');
    }

    /**
     * Store a newly created Production_product_name in storage.
     * POST /productionProductNames
     *
     * @param CreateProduction_product_nameAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProduction_product_nameAPIRequest $request)
    {
        $input = $request->all();

        $productionProductName = $this->productionProductNameRepository->create($input);

        return $this->sendResponse($productionProductName->toArray(), 'Production Product Name saved successfully');
    }

    /**
     * Display the specified Production_product_name.
     * GET|HEAD /productionProductNames/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Production_product_name $productionProductName */
        $productionProductName = $this->productionProductNameRepository->find($id);

        if (empty($productionProductName)) {
            return $this->sendError('Production Product Name not found');
        }

        return $this->sendResponse($productionProductName->toArray(), 'Production Product Name retrieved successfully');
    }

    /**
     * Update the specified Production_product_name in storage.
     * PUT/PATCH /productionProductNames/{id}
     *
     * @param int $id
     * @param UpdateProduction_product_nameAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProduction_product_nameAPIRequest $request)
    {
        $input = $request->all();

        /** @var Production_product_name $productionProductName */
        $productionProductName = $this->productionProductNameRepository->find($id);

        if (empty($productionProductName)) {
            return $this->sendError('Production Product Name not found');
        }

        $productionProductName = $this->productionProductNameRepository->update($input, $id);

        return $this->sendResponse($productionProductName->toArray(), 'Production_product_name updated successfully');
    }

    /**
     * Remove the specified Production_product_name from storage.
     * DELETE /productionProductNames/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Production_product_name $productionProductName */
        $productionProductName = $this->productionProductNameRepository->find($id);

        if (empty($productionProductName)) {
            return $this->sendError('Production Product Name not found');
        }

        $productionProductName->delete();

        return $this->sendSuccess('Production Product Name deleted successfully');
    }
}
