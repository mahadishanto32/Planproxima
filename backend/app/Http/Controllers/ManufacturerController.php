<?php

namespace App\Http\Controllers; 
use App\Models\Manufacturer;
use App\Models\Product;
use App\Imports\ManufacturerImport;
use Illuminate\Http\Request;
use App\Http\Resources\ManufacturerResource;
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
class ManufacturerController extends AppBaseController
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
        $query = Manufacturer::orderBy('id', 'desc');
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
        $items = ManufacturerResource::collection($items);  
        return $this->sendResponse($items, 'Manufacturer saved successfully');  
        // return view('manufacturer/index', [
        //     'items' => $items,
        //     'request_data' => $request_data 
        // ]);
    }

    public function fileUpload(Request $request){ 
        if ($request->hasFile('csvFile')) { 
            $data = Excel::import(new ManufacturerImport, request()->file('csvFile'));
            return redirect('/manufacturer'); 
        }else{
            return redirect('/manufacturer');
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
     * @param  \App\models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function edit(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Manufacturer  $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        //
    }
}
