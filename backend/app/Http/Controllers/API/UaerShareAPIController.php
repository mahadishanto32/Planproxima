<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUaerShareAPIRequest;
use App\Http\Requests\API\UpdateUaerShareAPIRequest;
use App\Models\UaerShare;
use App\Repositories\UaerShareRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Response;
use DB;

/**
 * Class UaerShareController
 * @package App\Http\Controllers\API
 */

class UaerShareAPIController extends AppBaseController
{
    /** @var  UaerShareRepository */
    private $uaerShareRepository;

    public function __construct(UaerShareRepository $uaerShareRepo)
    {
        $this->uaerShareRepository = $uaerShareRepo;
    }

    /**
     * Display a listing of the UaerShare.
     * GET|HEAD /uaerShares
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $uaerShares = $this->uaerShareRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($uaerShares->toArray(), 'Uaer Shares retrieved successfully');
    }
 
    public function share_kra_kpi($user_id){

        $dept_list  = UaerShare::select('departments.id','departments.name')
        ->where('uaer_shares.user_id',$user_id)
        ->join('departments','departments.id','uaer_shares.dept_id')
        ->get();
        return $this->sendResponse($dept_list->toArray(), 'Uaer Shares retrieved successfully');

    }
    public function share_kra_kpi_permission(Request $request){
        if($request['user_id']){
            $user =  User::find($request['user_id']); 
            if($user){ 
                $deletedRows = DB::table('uaer_shares')->where('user_id', $request['user_id'])->delete();
               
                $departments =  $request->dept_selects;
                foreach ($departments as $key => $value) { 
                    UaerShare::insert( 
                        ['dept_id' => $value['id'] , 'user_id' => $request['user_id']] 
                    ); 
                }
            } 
        }  
        
        return $this->sendResponse(1, 'Department Assign saved successfully ');
    }


    /**
     * Store a newly created UaerShare in storage.
     * POST /uaerShares
     *
     * @param CreateUaerShareAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUaerShareAPIRequest $request)
    {
        $input = $request->all();

        $uaerShare = $this->uaerShareRepository->create($input);

        return $this->sendResponse($uaerShare->toArray(), 'Uaer Share saved successfully');
    }

    /**
     * Display the specified UaerShare.
     * GET|HEAD /uaerShares/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UaerShare $uaerShare */
        $uaerShare = $this->uaerShareRepository->find($id);

        if (empty($uaerShare)) {
            return $this->sendError('Uaer Share not found');
        }

        return $this->sendResponse($uaerShare->toArray(), 'Uaer Share retrieved successfully');
    }

    /**
     * Update the specified UaerShare in storage.
     * PUT/PATCH /uaerShares/{id}
     *
     * @param int $id
     * @param UpdateUaerShareAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUaerShareAPIRequest $request)
    {
        $input = $request->all();

        /** @var UaerShare $uaerShare */
        $uaerShare = $this->uaerShareRepository->find($id);

        if (empty($uaerShare)) {
            return $this->sendError('Uaer Share not found');
        }

        $uaerShare = $this->uaerShareRepository->update($input, $id);

        return $this->sendResponse($uaerShare->toArray(), 'UaerShare updated successfully');
    }

    /**
     * Remove the specified UaerShare from storage.
     * DELETE /uaerShares/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UaerShare $uaerShare */
        $uaerShare = $this->uaerShareRepository->find($id);

        if (empty($uaerShare)) {
            return $this->sendError('Uaer Share not found');
        }

        $uaerShare->delete();

        return $this->sendSuccess('Uaer Share deleted successfully');
    }
}
