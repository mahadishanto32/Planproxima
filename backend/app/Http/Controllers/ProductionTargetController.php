<?php

namespace App\Http\Controllers; 
use App\Models\ProductionTarget; 
use App\Http\Resources\ProductionTargetResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
class ProductionTargetController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $items = ProductionTarget::orderBy('id', 'desc')->get();   
        $items = ProductionTargetResource::collection($items);    
        return view('production_target/index', [
            'items' => $items,
        ]);
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new CostImport, request()->file('csvFile'));
            return redirect('/cost'); 
        }else{
            return redirect('/cost');
        } 
    }
 
    public function production_target_add()
    {
        //
        return view('production_target/add');
    }
    public function production_target_add_submit(Request $request)
    {
        //
        return view('production_target/add', [ 
            'request' => $request 
        ]);
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
