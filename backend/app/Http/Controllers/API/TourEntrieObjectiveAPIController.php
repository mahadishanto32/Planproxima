<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTourEntrieObjectiveAPIRequest;
use App\Http\Requests\API\UpdateTourEntrieObjectiveAPIRequest;
use App\Models\TourEntrieObjective;
use App\Repositories\TourEntrieObjectiveRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth ;
use Illuminate\Support\Facades\Http;
/**
 * Class TourEntrieObjectiveController
 * @package App\Http\Controllers\API
 */

class TourEntrieObjectiveAPIController extends AppBaseController
{
    /** @var  TourEntrieObjectiveRepository */
    private $tourEntrieObjectiveRepository;

    public function __construct(TourEntrieObjectiveRepository $tourEntrieObjectiveRepo)
    {
        $this->tourEntrieObjectiveRepository = $tourEntrieObjectiveRepo;
    }

    /**
     * Display a listing of the TourEntrieObjective.
     * GET|HEAD /tourEntrieObjectives
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // $tourEntrieObjectives = $this->tourEntrieObjectiveRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );
        $user_data = Auth::user();
        $tourEntrieObjectives = TourEntrieObjective::select('tour_entrie_objectives.*', 'users.name')
        ->join('users', 'users.id', 'tour_entrie_objectives.user_id')
        ->where('users.id', $user_data->id)
        ->orderBy('tour_entrie_objectives.created_at', 'desc') // Change 'created_at' to your desired column
        ->limit(50)
        ->get();
        
        return $this->sendResponse($tourEntrieObjectives->toArray(), 'Tour Entrie Objectives retrieved successfully');
    }

    public function month_objectives(  Request $request){
 
        $month =  date("M", strtotime($request->start_date? $request->start_date : date('Y-m-d') ));
        $tourEntrieObjectives = TourEntrieObjective::select('tour_entrie_objectives.*' ,'users.name')
        ->join('users','users.id' ,  'tour_entrie_objectives.user_id')
        ->where('users.id',  $request->user_id) 
        ->where('tour_entrie_objectives.month', strtolower($month))
        ->get();

        return $this->sendResponse($tourEntrieObjectives , 'Tour Entrie Objective saved successfully');
    }



    public function tour_territory(  Request $request){
        $empid = $request->empid;
        $trritoryList = [];
        $responce = Http::get(automationUrl() . 'supervisor-territory-list', [
                'empid' => Auth::user()->employee_id,
            ])->json();

        $trritoryList = isset($responce['territory'])?$responce['territory']:[];

        return $this->sendResponse($trritoryList , 'Territory List Retrive successfully');
    }   
    
    public function territory_details( Request $request){
        $territory = $request->territory_details;
        // $trritoryList = [];
        // $responce = Http::get(automationUrl() . 'supervisor-territory-list', [
        //         'empid' => Auth::user()->employee_id,
        //     ])->json();

        // $trritoryList = isset($responce['territory'])?$responce['territory']:[];

        return $this->sendResponse($territory , 'Territory List Retrive successfully');
    }    
    
    public function tour_point(  Request $request){
        $terri_id = $request->terri_id;
        $pointList = [];
        $responce = Http::get(automationUrl() . 'apps-point-list', [
                'terri_id' => $terri_id,
                'is_dealer' => 'yes',
            ])->json();

        $pointList = isset($responce['point_list'])?$responce['point_list']:[];

        return $this->sendResponse($pointList , 'Point List Retrive  successfully');
    }        


    public function point_fo(Request $request){
        $point_id = $request->point_id;
        $responce = Http::get(automationUrl() . 'apps-route-list', [
            'point_id' => $point_id,
        ])->json();
        $dataList = [
            'route_list' => isset($responce['route_list'])?$responce['route_list']:[],      
            'fo_list' => isset($responce['fo_list'])?$responce['fo_list']:[],      
        ];
        return $this->sendResponse($dataList , 'Point List Retrive  successfully');
    }   
    /**
     * Store a newly created TourEntrieObjective in storage.
     * POST /tourEntrieObjectives
     *
     * @param CreateTourEntrieObjectiveAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTourEntrieObjectiveAPIRequest $request)
    {
        $input = $request->all();
        $user_data = Auth::user();
        if($request['objectiveslist']){
            $objectiveslist = $request['objectiveslist'] ; 
            foreach ($objectiveslist as $key => $value) {
                # code...
                if($value['objective']){
                    $input['objective'] = $value['objective'] ;
                    $input['month'] =  $request->month ;
                    $input['user_id'] =  $user_data->id ;
                      $this->tourEntrieObjectiveRepository->create($input);
                }
                
            }

        } 
        return $this->sendResponse(1, 'Tour Entrie Objective saved successfully');
    }

    /**
     * Display the specified TourEntrieObjective.
     * GET|HEAD /tourEntrieObjectives/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TourEntrieObjective $tourEntrieObjective */
        $tourEntrieObjective = $this->tourEntrieObjectiveRepository->find($id);

        if (empty($tourEntrieObjective)) {
            return $this->sendError('Tour Entrie Objective not found');
        }

        return $this->sendResponse($tourEntrieObjective->toArray(), 'Tour Entrie Objective retrieved successfully');
    }

    /**
     * Update the specified TourEntrieObjective in storage.
     * PUT/PATCH /tourEntrieObjectives/{id}
     *
     * @param int $id
     * @param UpdateTourEntrieObjectiveAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTourEntrieObjectiveAPIRequest $request)
    {
        $input = $request->all();

        /** @var TourEntrieObjective $tourEntrieObjective */
        $tourEntrieObjective = $this->tourEntrieObjectiveRepository->find($id);

        if (empty($tourEntrieObjective)) {
            return $this->sendError('Tour Entrie Objective not found');
        }

        $tourEntrieObjective = $this->tourEntrieObjectiveRepository->update($input, $id);

        return $this->sendResponse($tourEntrieObjective->toArray(), 'TourEntrieObjective updated successfully');
    }

    /**
     * Remove the specified TourEntrieObjective from storage.
     * DELETE /tourEntrieObjectives/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TourEntrieObjective $tourEntrieObjective */
        $tourEntrieObjective = $this->tourEntrieObjectiveRepository->find($id);

        if (empty($tourEntrieObjective)) {
            return $this->sendError('Tour Entrie Objective not found');
        }

        $tourEntrieObjective->delete();

        return $this->sendSuccess('Tour Entrie Objective deleted successfully');
    }
}
