<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBuyerContactShareAPIRequest;
use App\Http\Requests\API\UpdateBuyerContactShareAPIRequest;
use App\Models\BuyerContactShare;
use App\Repositories\BuyerContactShareRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class BuyerContactShareController
 * @package App\Http\Controllers\API
 */

class BuyerContactShareAPIController extends AppBaseController
{
    /** @var  BuyerContactShareRepository */
    private $buyerContactShareRepository;

    public function __construct(BuyerContactShareRepository $buyerContactShareRepo)
    {
        $this->buyerContactShareRepository = $buyerContactShareRepo;
    }

    /**
     * Display a listing of the BuyerContactShare.
     * GET|HEAD /buyerContactShares
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $buyerContactShares = $this->buyerContactShareRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($buyerContactShares->toArray(), 'Buyer Contact Shares retrieved successfully');
    }

    /**
     * Store a newly created BuyerContactShare in storage.
     * POST /buyerContactShares
     *
     * @param CreateBuyerContactShareAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBuyerContactShareAPIRequest $request)
    {
        $input = $request->all();

        $buyerContactShare = $this->buyerContactShareRepository->create($input);

        return $this->sendResponse($buyerContactShare->toArray(), 'Buyer Contact Share saved successfully');
    }

    /**
     * Display the specified BuyerContactShare.
     * GET|HEAD /buyerContactShares/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var BuyerContactShare $buyerContactShare */
        $buyerContactShare = $this->buyerContactShareRepository->find($id);

        if (empty($buyerContactShare)) {
            return $this->sendError('Buyer Contact Share not found');
        }

        return $this->sendResponse($buyerContactShare->toArray(), 'Buyer Contact Share retrieved successfully');
    }

    /**
     * Update the specified BuyerContactShare in storage.
     * PUT/PATCH /buyerContactShares/{id}
     *
     * @param int $id
     * @param UpdateBuyerContactShareAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBuyerContactShareAPIRequest $request)
    {
        $input = $request->all();

        /** @var BuyerContactShare $buyerContactShare */
        $buyerContactShare = $this->buyerContactShareRepository->find($id);

        if (empty($buyerContactShare)) {
            return $this->sendError('Buyer Contact Share not found');
        }

        $buyerContactShare = $this->buyerContactShareRepository->update($input, $id);

        return $this->sendResponse($buyerContactShare->toArray(), 'BuyerContactShare updated successfully');
    }

    /**
     * Remove the specified BuyerContactShare from storage.
     * DELETE /buyerContactShares/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var BuyerContactShare $buyerContactShare */
        $buyerContactShare = $this->buyerContactShareRepository->find($id);

        if (empty($buyerContactShare)) {
            return $this->sendError('Buyer Contact Share not found');
        }

        $buyerContactShare->delete();

        return $this->sendSuccess('Buyer Contact Share deleted successfully');
    }

    public function contact_assign(Request $request){
        if($request->status=='true'){
            BuyerContactShare::updateOrCreate(
                ['b_id' => $request->b_id , 'user_id' => $request->user_id], 
            );            
        }else{
            $data = BuyerContactShare::where('b_id' , $request->b_id)->where('user_id' , $request->user_id)->delete();
        }
        return $this->sendSuccess('Buyer Contact Modified successfully');
    }
}
