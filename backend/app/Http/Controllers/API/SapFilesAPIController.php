<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSapFilesAPIRequest;
use App\Http\Requests\API\UpdateSapFilesAPIRequest;
use App\Models\SapFiles;
use App\Repositories\SapFilesRepository;
use App\Http\Resources\SapFilesResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;
use Response , DB , Auth;

/**
 * Class SapFilesController
 * @package App\Http\Controllers\API
 */

class SapFilesAPIController extends AppBaseController
{
    /** @var  SapFilesRepository */
    private $sapFilesRepository;

    public function __construct(SapFilesRepository $sapFilesRepo)
    {
        $this->sapFilesRepository = $sapFilesRepo;
    }

    /**
     * Display a listing of the SapFiles.
     * GET|HEAD /sapFiles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // $sapFiles = $this->sapFilesRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

        $sapFiles = SapFiles::limit(2000)->orderby('id','desc')->get();
        $items = SapFilesResource::collection($sapFiles);    
        return $this->sendResponse($items, 'Sap Files retrieved successfully');
    }

    /**
     * Store a newly created SapFiles in storage.
     * POST /sapFiles
     *
     * @param CreateSapFilesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSapFilesAPIRequest $request)
    {
        $input = $request->all();

        $sapFiles = $this->sapFilesRepository->create($input);

        return $this->sendResponse($sapFiles->toArray(), 'Sap Files saved successfully');
    }

    /**
     * Display the specified SapFiles.
     * GET|HEAD /sapFiles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var SapFiles $sapFiles */
        $sapFiles = $this->sapFilesRepository->find($id);

        if (empty($sapFiles)) {
            return $this->sendError('Sap Files not found');
        }

        return $this->sendResponse($sapFiles->toArray(), 'Sap Files retrieved successfully');
    }


    public function sap_files_download($id){ 
        $file = SapFiles::find($id);
        $file_name = $file['file_name_url'];  
        return response()->download(storage_path('app/public/'.$file_name));
  
       
    } 
 
    /**
     * Update the specified SapFiles in storage.
     * PUT/PATCH /sapFiles/{id}
     *
     * @param int $id
     * @param UpdateSapFilesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSapFilesAPIRequest $request)
    {
        $input = $request->all();

        /** @var SapFiles $sapFiles */
        $sapFiles = $this->sapFilesRepository->find($id);

        if (empty($sapFiles)) {
            return $this->sendError('Sap Files not found');
        }

        $sapFiles = $this->sapFilesRepository->update($input, $id);

        return $this->sendResponse($sapFiles->toArray(), 'SapFiles updated successfully');
    }

    /**
     * Remove the specified SapFiles from storage.
     * DELETE /sapFiles/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var SapFiles $sapFiles */
        $sapFiles = $this->sapFilesRepository->find($id);

        if (empty($sapFiles)) {
            return $this->sendError('Sap Files not found');
        }

        $sapFiles->delete();

        return $this->sendSuccess('Sap Files deleted successfully');
    }
    public function sap_files_delete($id , Request $request)
    {
        //
      
        $user  = Auth::user()->email ;
    
        //if($user == 'cost'){  
            DB::table('manufacturers')->where('sap_file_id', $id)->delete();
            DB::table('delivery')->where('sap_file_id', $id)->delete();
            DB::table('consumtions')->where('sap_file_id', $id)->delete();
            DB::table('wastages')->where('sap_file_id', $id)->delete();
            DB::table('productionwastagedafts')->where('sap_file_id', $id)->delete();
            DB::table('sap_files')->where('id', $id)->delete();
            return $this->sendResponse( 0, 'SapFiles updated successfully');
        // }else{
        //     return $this->sendError('Sap Files not found');  
        // }
    }
}
