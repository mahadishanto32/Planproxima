<?php

namespace App\Http\Controllers; 
use App\Models\CostCenter; 
use App\Models\Ordergroup; 
use Illuminate\Http\Request;
use App\Http\Resources\CostCenterResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
class CostCenterController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = CostCenter::orderBy('id', 'desc');
        if( $request->summary_group_id){
            $query->where('summary_group_id', $request->summary_group_id);
        } 
        $items = $query->get();   
        $items = CostCenterResource::collection($items);    
        return $this->sendResponse($items, 'Cost center retrieved successfully');
        
        // return view('cost_center/index', [
        //     'items' => $items,
        // ]);
    }

    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\CostCenter  $Cost
     * @return \Illuminate\Http\Response
     */
    public function show(CostCenter $costCenter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\CostCenter  $Cost
     * @return \Illuminate\Http\Response
     */
    public function edit(CostCenter $costCenter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cost $cost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cost $cost)
    {
        //
    }
}
