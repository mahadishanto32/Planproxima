<?php

namespace App\Http\Controllers; 
use App\Models\Wastage;
use App\Models\Product;
use App\Models\WastageSummaryGroup;
use App\Imports\WastageImport;
use Illuminate\Http\Request;
//WastegeConsumptionRelationResource
use App\Http\Resources\WastegeConsumptionRelationResource;
use App\Http\Resources\WastageResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
class WastageController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $items = Wastage::orderBy('id', 'desc')->skip(0)->take(300)->get();   
        $items = WastageResource::collection($items);    
        return $this->sendResponse($items, 'Wastage saved successfully');  
        
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new WastageImport, request()->file('csvFile'));
            return redirect('/wastage'); 
        }else{
            return redirect('/wastage');
        } 
    }

    public function  wastage_summary(Request $request){
        $q = WastageSummaryGroup::orderby('id','ASC');
        if($request->type){
            $q->where('type',$request->type);
        }else{
            $q->where('type',0);
        }
        if($request->grouping_id){
            $q->where('grouping_id',$request->grouping_id);
        }
        if($request->plant_id){
            $q->where('plant_id',$request->plant_id);
        }
        
        $items = $q->get();
        return $this->sendResponse($items, 'Summary group successfully');  
    }
    public function  wastage_summary_details($id , Request $request){
        $items = WastageSummaryGroup::where('id',$id)->orWhere('grouping_id',$id)->get();
        $items = WastegeConsumptionRelationResource::collection($items);
        return $this->sendResponse($items, 'Summary group successfully');  
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
     * @param  \App\models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function show(Wastage $wastage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function edit(Wastage $wastage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wastage $wastage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wastage $wastage)
    {
        //
    }
}
