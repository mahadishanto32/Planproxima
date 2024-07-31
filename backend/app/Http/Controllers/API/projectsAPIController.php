<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateprojectsAPIRequest;
use App\Http\Requests\API\UpdateprojectsAPIRequest;
use App\Models\projects;
use App\Models\DailyScheduleItem;
use App\Repositories\projectsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Http\Resources\ProjectAPIResource;
/**
 * Class projectsController
 * @package App\Http\Controllers\API
 */

class projectsAPIController extends AppBaseController
{
    /** @var  projectsRepository */
    private $projectsRepository;

    public function __construct(projectsRepository $projectsRepo)
    {
        $this->projectsRepository = $projectsRepo;
    }

    /**
     * Display a listing of the projects.
     * GET|HEAD /projects
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $projects = $this->projectsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            $request->get('desc' , 'name'),
        );
        $projects = projects::all();
        // return $projects;
        $result = ProjectAPIResource::collection($projects);
        return $this->sendResponse($result, 'Projects retrieved successfully');
    }

    /**
     * Store a newly created projects in storage.
     * POST /projects
     *
     * @param CreateprojectsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateprojectsAPIRequest $request)
    {
        $input = $request->all();

        $projects = $this->projectsRepository->create($input);

        return $this->sendResponse($projects->toArray(), 'Projects saved successfully');
    }

    /**
     * Display the specified projects.
     * GET|HEAD /projects/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var projects $projects */
        $projects = $this->projectsRepository->find($id);

        if (empty($projects)) {
            return $this->sendError('Projects not found');
        }

        return $this->sendResponse($projects->toArray(), 'Projects retrieved successfully');
    }

    /**
     * Update the specified projects in storage.
     * PUT/PATCH /projects/{id}
     *
     * @param int $id
     * @param UpdateprojectsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateprojectsAPIRequest $request)
    {
        $input = $request->all();

        /** @var projects $projects */
        $projects = $this->projectsRepository->find($id);

        if (empty($projects)) {
            return $this->sendError('Projects not found');
        }

        $projects = $this->projectsRepository->update($input, $id);

        return $this->sendResponse($projects->toArray(), 'projects updated successfully');
    }

    /**
     * Remove the specified projects from storage.
     * DELETE /projects/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var projects $projects */

        $itemCheck = DailyScheduleItem::where('project_id' , $id)->first();
        
        if(empty($itemCheck)){
            $projects = $this->projectsRepository->find($id);

            if (empty($projects)) {
                return $this->sendError('Projects not found');
            }
    
            $projects->delete();
    
            return $this->sendSuccess('Projects deleted successfully');
        }else{   
            return $this->sendSuccess("Can't Delete this Project");
        }

    }
}
