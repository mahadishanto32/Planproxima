<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateProduction_empAPIRequest;
use App\Http\Requests\API\UpdateProduction_empAPIRequest;
use App\Models\Production_emp;
use App\Models\Department;
use App\Models\Production_product_name;
use App\Http\Resources\Production_empResource;
use App\Repositories\Production_empRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Auth, DB;
use Response;

/**
 * Class Production_empController
 * @package App\Http\Controllers\API
 */

class Production_empAPIController extends AppBaseController
{
    /** @var  Production_empRepository */
    private $productionEmpRepository;

    public function __construct(Production_empRepository $productionEmpRepo)
    {
        $this->productionEmpRepository = $productionEmpRepo;
    }

    /**
     * Display a listing of the Production_emp.
     * GET|HEAD /productionEmps
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user(); 
        $request['factory_id'] = $user_data->dept_id ;
        // $productionEmps = $this->productionEmpRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );
        $q = Production_emp::orderby('id','DESC');
        $q->limit(30);
        if($user_data->role_id == 5 || $user_data->role_id == 6 ||$user_data->role_id == 7){
            $q->where('factory_id',$user_data->dept_id);
        }
        if( $request['week']){
            $q->where('week', $request['week']);
        }
        if( $request['month']){
            $q->where('month', $request['month']);
        }
        if( $request['year']){
            $q->where('year', $request['year']);
        }
        $result = $q->get();
        $data_return  =   Production_empResource::collection($result);
        return $this->sendResponse($data_return, 'Production Emps retrieved successfully');
    }
    function production_emps_factory(){
        $user_data = Auth::user(); 
        $q = Department::limit(30);
        if($user_data->role_id ==  5 || $user_data->role_id ==  6 ||  $user_data->role_id ==  7){
            $q->where('id',$user_data->dept_id);
        }
        $q->where('is_factory',1);
        $result = $q->get();
        return $this->sendResponse($result->toArray(), 'Factory');
    }
    function get_products_list(Request $request ){
        $user_data = Auth::user(); 
       $q =  Production_product_name::limit(100);
       /*if($user_data->role_id ==  5 || $user_data->role_id ==  6 ||  $user_data->role_id ==  7){
            $q->where('factory_id',$user_data->dept_id);
        }
        if($request->get('factory_id')){
            $q->where('factory_id',$user_data->dept_id);
        }*/
        $q->where('factory_id', $request->factory_id);
        $q->where('active', 0);
        $result = $q->get();

        return $this->sendResponse($result->toArray(), 'Product');
  
    }
    function get_iending_emp(Request $request ){
        $product_id = $request->get('product_id');


        $q = Production_emp::orderby('id','DESC');
        $q->where('active',0);
        $q->where('product_id',$product_id);
        $result = $q->first();

        return $this->sendResponse($result , 'Product');
  
    }

    /**
     * Store a newly created Production_emp in storage.
     * POST /productionEmps
     *
     * @param CreateProduction_empAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateProduction_empAPIRequest $request)
    {
        $input = $request->all();

        $productionEmp = $this->productionEmpRepository->create($input);

        return $this->sendResponse($productionEmp->toArray(), 'Production Emp saved successfully');
    }

    /**
     * Display the specified Production_emp.
     * GET|HEAD /productionEmps/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Production_emp $productionEmp */
        $productionEmp = $this->productionEmpRepository->find($id);

        if (empty($productionEmp)) {
            return $this->sendError('Production Emp not found');
        }

        return $this->sendResponse($productionEmp->toArray(), 'Production Emp retrieved successfully');
    }

    /**
     * Update the specified Production_emp in storage.
     * PUT/PATCH /productionEmps/{id}
     *
     * @param int $id
     * @param UpdateProduction_empAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProduction_empAPIRequest $request)
    {
        $input = $request->all();

        /** @var Production_emp $productionEmp */
        $productionEmp = $this->productionEmpRepository->find($id);

        if (empty($productionEmp)) {
            return $this->sendError('Production Emp not found');
        }

        $productionEmp = $this->productionEmpRepository->update($input, $id);

        return $this->sendResponse($productionEmp->toArray(), 'Production_emp updated successfully');
    }

    /**
     * Remove the specified Production_emp from storage.
     * DELETE /productionEmps/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Production_emp $productionEmp */
        $productionEmp = $this->productionEmpRepository->find($id);

        if (empty($productionEmp)) {
            return $this->sendError('Production Emp not found');
        }

        $productionEmp->delete();

        return $this->sendSuccess('Production Emp deleted successfully');
    }
}
