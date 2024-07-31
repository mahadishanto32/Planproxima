<?php

namespace App\Http\Controllers; 
use App\Models\Delivery;
use App\Models\Product; 
use Illuminate\Http\Request;
use App\Http\Resources\DeliveryResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
class DeliveryController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 

        $factory_id  =  $request->get('factory_id') ? $request->get('factory_id') : ''; 
        $summary_group_id =  $request->get('summary_group_id') ?  $request->get('summary_group_id') : '';
        $start_date   =  $request->get('start_date') ?  $request->get('start_date') : '';  
        $end_date     =  $request->get('end_date')  ? $request->get('end_date') : ''; 
        $request_data['start_date'] = $start_date ;
        $request_data['end_date'] = $end_date ;
        $request_data['summary_group_id'] = $summary_group_id ;
        $query = Delivery::orderBy('id', 'desc');
        if($summary_group_id){
            $query->where('summary_group_id',$summary_group_id);
        } 
        if($request_data['start_date']  && $request_data['end_date']){
            $query->whereBetween('date', [date('Y-m-d', strtotime($request_data['start_date'])), date('Y-m-d', strtotime($request_data['end_date']))]);  
        }   
        if($request->get('limit')){
            $query->limit($request->get('limit'));
        } 
        $items  =   $query->get();  
        $items = DeliveryResource::collection($items);  
        return $this->sendResponse($items, 'Manufacturer saved successfully');  
         
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new DeliveryImport, request()->file('csvFile'));
            return redirect('/delivery'); 
        }else{
            return redirect('/delivery');
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
     * @param  \App\models\Delivery  $Delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $Delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Delivery  $Delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $Delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Delivery  $Delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $Delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Delivery  $Delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $Delivery)
    {
        //
    }
}
