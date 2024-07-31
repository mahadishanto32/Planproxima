<?php

namespace App\Http\Controllers;

use App\Models\Factory;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Auth;
use DB;

class FactoryController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_data = Auth::user();
        $dept_id = $user_data->dept_id;

        if($dept_id > 0){
            $factorys = Factory::whereIn("id", function ($query) use ($dept_id) {
                $query->select('factory_id')
                    ->from('department_factories')
                    ->where('dept_id', $dept_id);
            })->get();

        }else{
            $factorys = Factory::where('status',1)->get();
        }

        
        return $this->sendResponse($factorys, 'Return retrieved successfully');
        // dd($Factory);
        // $data['title'] =  "Factory" ;
        // $data['factorys'] =  $factorys ;
       // return view('factory/index', $data);
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
     * @param  \App\Factory  $Factory
     * @return \Illuminate\Http\Response
     */
    public function show(Factory $Factory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Factory  $Factory
     * @return \Illuminate\Http\Response
     */
    public function edit(Factory $Factory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Factory  $Factory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Factory $Factory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Factory  $Factory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Factory $Factory)
    {
        //
    }
}
