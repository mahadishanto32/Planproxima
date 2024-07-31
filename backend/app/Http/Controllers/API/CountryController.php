<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
 
class CountryController extends Controller
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

        $response = Http::withHeaders([
            'x-rapidapi-host' => 'ajayakv-rest-countries-v1.p.rapidapi.com',
            'x-rapidapi-key' => 'cJvLRNK0GfdM9WSMbQe3inU7REn8JVy5'
        ])->get('https://ajayakv-rest-countries-v1.p.rapidapi.com/rest/v1/all');
        
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
    public function edit($id)
    {
        //
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
