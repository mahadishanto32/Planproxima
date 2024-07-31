<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateWingAPIRequest;
use App\Http\Requests\API\UpdateWingAPIRequest;
use App\Models\Wing;
use App\Http\Resources\WingResource;
use App\Repositories\WingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth ;

/**
 * Class WingController
 * @package App\Http\Controllers\API
 */

class WingAPIController extends AppBaseController
{
    /** @var  WingRepository */
    private $wingRepository;

    public function __construct(WingRepository $wingRepo)
    {
        $this->wingRepository = $wingRepo;
    }

    /**
     * Display a listing of the Wing.
     * GET|HEAD /wings
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user();

        if($request->dept_id){
            $request['dept_id'] = $request->dept_id ;
            if($user_data->role_id == 6 ){
                $request['user_id'] = $user_data->id;
            }
        }
        
        if($user_data->role_id == 5 &&  $user_data->dept_id != 6){
            $request['dept_id'] = $request->dept_id;
            // $request['dept_id'] = $user_data->dept_id;
        }else if($user_data->role_id == 6 ){
            $request['user_id'] = $user_data->id ;
        }

        $request['status'] = 1 ;
         
        $wings = $this->wingRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );
        
        $data_return  =   WingResource::collection($wings);
        return $this->sendResponse($data_return, 'Wings retrieved successfully');
       // return $this->sendResponse($wings->toArray(), 'Wings retrieved successfully');
    }
    public function get_wings( Request $request)
    {
         
        $request['status'] = 1 ; 
         
        $wings = $this->wingRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );
        
        $data_return  =   WingResource::collection($wings);
        return $this->sendResponse($data_return, 'Wings retrieved successfully');
       // return $this->sendResponse($wings->toArray(), 'Wings retrieved successfully');
    }

    /**
     * Store a newly created Wing in storage.
     * POST /wings
     *
     * @param CreateWingAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateWingAPIRequest $request)
    {
        $input = $request->all();

        $wing = $this->wingRepository->create($input);

        return $this->sendResponse($wing->toArray(), 'Wing saved successfully');
    }

    /**
     * Display the specified Wing.
     * GET|HEAD /wings/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Wing $wing */
        $wing = $this->wingRepository->find($id);

        if (empty($wing)) {
            return $this->sendError('Wing not found');
        }

        return $this->sendResponse($wing->toArray(), 'Wing retrieved successfully');
    }

    /**
     * Update the specified Wing in storage.
     * PUT/PATCH /wings/{id}
     *
     * @param int $id
     * @param UpdateWingAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWingAPIRequest $request)
    {
        $input = $request->all();

        /** @var Wing $wing */
        $wing = $this->wingRepository->find($id);

        if (empty($wing)) {
            return $this->sendError('Wing not found');
        }

        $wing = $this->wingRepository->update($input, $id);

        return $this->sendResponse($wing->toArray(), 'Wing updated successfully');
    }

    /**
     * Remove the specified Wing from storage.
     * DELETE /wings/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Wing $wing */
        $wing = $this->wingRepository->find($id);

        if (empty($wing)) {
            return $this->sendError('Wing not found');
        }

        $wing->delete();

        return $this->sendSuccess('Wing deleted successfully');
    }
}
