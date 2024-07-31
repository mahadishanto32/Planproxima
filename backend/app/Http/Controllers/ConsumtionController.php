<?php

namespace App\Http\Controllers; 
use App\Models\Consumtion; 
//use App\Http\Resources\ConsumtionResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
class ConsumtionController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $items = Wastage::orderBy('id', 'desc')->skip(0)->take(100)->get();   
        $items = WastageResource::collection($items);    
        $data['items'] = $items;
        return $this->sendResponse($data, 'Wastage retrieved successfully');
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new WastageImport, request()->file('csvFile'));
            return redirect('/wastage'); 
        }else{
            return redirect('/wastage');
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
