<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateFollow_upAPIRequest;
use App\Http\Requests\API\UpdateFollow_upAPIRequest;
use App\Models\Follow_up;
use App\Models\FollowUpDept;
use App\Repositories\Follow_upRepository;
use Illuminate\Http\Request;
use App\Http\Resources\Follow_upResource;
use App\Http\Controllers\AppBaseController;
use Response ,DB;

/**
 * Class Follow_upController
 * @package App\Http\Controllers\API
 */

class Follow_upAPIController extends AppBaseController
{
    /** @var  Follow_upRepository */
    private $followUpRepository;

    public function __construct(Follow_upRepository $followUpRepo)
    {
        $this->followUpRepository = $followUpRepo;
    }

    /**
     * Display a listing of the Follow_up.
     * GET|HEAD /followUps
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
      if($request->dept_id){
        $q =  Follow_up::select('follow_ups.*');
        $q->limit($request->limit ? $request->limit :  100);
        $q->where('status', $request->status);
        $q->join('follow_up_depts','follow_up_depts.activity_id','follow_ups.id');
        if($request['start_date']  && $request['end_date']){
            $q->whereBetween('follow_ups.complete', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        } 
        $q->where('follow_up_depts.dept_id',$request->dept_id);
        $result =$q->get();
        $result = Follow_upResource::collection($result);
        return $this->sendResponse($result, 'Follow Ups retrieved successfully');
      }else{
        $q =  Follow_up::limit(100);
        $q->limit($request->limit ? $request->limit :  100);
        $q->orderby('id','desc');
        $q->where('status', $request->status);
        if($request['start_date']  && $request['end_date']){
            $q->whereBetween('complete', [date('Y-m-d', strtotime($request['start_date'])), date('Y-m-d', strtotime($request['end_date']))]);  
        } 
        $result =$q->get(); 
        $result = Follow_upResource::collection($result); 
        return $this->sendResponse($result, 'Follow Ups retrieved successfully');
      }
       
    }

    /**
     * Store a newly created Follow_up in storage.
     * POST /followUps
     *
     * @param CreateFollow_upAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateFollow_upAPIRequest $request)
    {
        $input = $request->all();

        $followUp = $this->followUpRepository->create($input);
        
        if($request->dept_selects){
            foreach ($request->dept_selects as $key => $value) {
                $inputs = ['dept_id' =>  $value['id'] ,'activity_id' => $followUp->id];
                FollowUpDept::create($inputs);
            }
           
        } 
        return $this->sendResponse($followUp->toArray(), 'Follow Up saved successfully');
    }

    /**
     * Display the specified Follow_up.
     * GET|HEAD /followUps/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Follow_up $followUp */
        $followUp = $this->followUpRepository->find($id);

        if (empty($followUp)) {
            return $this->sendError('Follow Up not found');
        }

        return $this->sendResponse($followUp->toArray(), 'Follow Up retrieved successfully');
    }

    /**
     * Update the specified Follow_up in storage.
     * PUT/PATCH /followUps/{id}
     *
     * @param int $id
     * @param UpdateFollow_upAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFollow_upAPIRequest $request)
    {
        $input = $request->all();

        /** @var Follow_up $followUp */
        $followUp = $this->followUpRepository->find($id);
        $deletedRows = DB::table('follow_up_depts')->where('activity_id', $id)->delete();
        //$deletedRows = FollowUpDept::where('activity_id', $id)->delete();
        if($request->dept_selects){
            foreach ($request->dept_selects as $key => $value) {
                $inputs = ['dept_id' =>  $value['id'] ,'activity_id' => $id];
                FollowUpDept::create($inputs);
            }
           
        } 


        if (empty($followUp)) {
            return $this->sendError('Follow Up not found');
        }

        $followUp = $this->followUpRepository->update($input, $id);

        return $this->sendResponse($followUp->toArray(), 'Follow_up updated successfully');
    }
    public function follow_ups_status($id, UpdateFollow_upAPIRequest $request)
    {
        $input = $request->all();

        /** @var Follow_up $followUp */
        $followUp = $this->followUpRepository->find($id); 
        if (empty($followUp)) {
            return $this->sendError('Follow Up not found');
        }

        $followUp = $this->followUpRepository->update($input, $id);

        return $this->sendResponse($followUp->toArray(), 'Follow_up updated successfully');
    }

    /**
     * Remove the specified Follow_up from storage.
     * DELETE /followUps/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Follow_up $followUp */
        $followUp = $this->followUpRepository->find($id);

        if (empty($followUp)) {
            return $this->sendError('Follow Up not found');
        }

        $followUp->delete();

        return $this->sendSuccess('Follow Up deleted successfully');
    }
}
