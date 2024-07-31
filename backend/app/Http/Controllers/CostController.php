<?php

namespace App\Http\Controllers; 
use App\Models\Cost;
use App\Models\Product;
use App\Imports\CostImport;
use Illuminate\Http\Request;
use App\Http\Resources\CostResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
class CostController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $query = Cost::orderBy('id', 'desc');
        if($request->factory_id){
            $query->where('factory_id',$request->factory_id);  
        }
        if($request->summary_group_id){
            $query->where('summary_group_id',$request->summary_group_id);  
        }
        $items  =  $query->limit(500)->get();   
        $items = CostResource::collection($items);    
        
        return $this->sendResponse($items, 'Cost center retrieved successfully');
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new CostImport, request()->file('csvFile'));
            return redirect('/cost'); 
        }else{
            return redirect('/cost');
        } 
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
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function show(Cost $cost)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Cost  $Cost
     * @return \Illuminate\Http\Response
     */
    public function edit(Cost $cost)
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
