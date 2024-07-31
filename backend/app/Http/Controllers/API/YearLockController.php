<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\YearLock;
use App\Http\Controllers\AppBaseController;
class YearLockController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $client = new http\Client;
        // $request = new http\Client\Request;
        // $request->setRequestUrl('');
        // $request->setRequestMethod('GET');
        // $request->setHeaders([
        //   'x-rapidapi-host' => 'ajayakv-rest-countries-v1.p.rapidapi.com',
        //   'x-rapidapi-key' => 'cJvLRNK0GfdM9WSMbQe3inU7REn8JVy5'
        // ]);
        // $client->enqueue($request)->send();
        // $response = $client->getResponse();
        // echo $response->getBody();
        
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $year =  $request->year;   
        // $data = YearLock::updateOrCreate(
        //     ['year' => $year], // Condition to find the record
        //     ['year' => $year + 1, 'appraisal_year' => $year]   // Data to update or create
        // );
        // return $data;
        
        try {
            $data = YearLock::updateOrCreate(
                ['year' => $year], // Condition to find the record
                ['year' => $year + 1, 'appraisal_year' => $year]   // Data to update or create
            );
            // If the operation is successful, return a success response
            return $this->sendResponse($data, 'YearLock updated or created successfully');
        } catch (\Exception $e) {
            // If an exception is thrown, return an error response
            return $this->sendError('Error updating or creating YearLock');
        }     
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
