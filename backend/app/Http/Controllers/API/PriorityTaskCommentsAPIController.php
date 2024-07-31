<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePriorityTaskCommentsAPIRequest;
use App\Http\Requests\API\UpdatePriorityTaskCommentsAPIRequest;
use App\Models\PriorityTaskComments;
use App\Repositories\PriorityTaskCommentsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth ;

/**
 * Class PriorityTaskCommentsController
 * @package App\Http\Controllers\API
 */

class PriorityTaskCommentsAPIController extends AppBaseController
{
    /** @var  PriorityTaskCommentsRepository */
    private $priorityTaskCommentsRepository;

    public function __construct(PriorityTaskCommentsRepository $priorityTaskCommentsRepo)
    {
        $this->priorityTaskCommentsRepository = $priorityTaskCommentsRepo;
    }

    /**
     * Display a listing of the PriorityTaskComments.
     * GET|HEAD /priorityTaskComments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user();
        // $priorityTaskComments = $this->priorityTaskCommentsRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

        PriorityTaskComments::where('priority_item_task_id', $request->priority_item_task_id)
    ->where('created_by', '!=', $user_data->id)
    ->update(['is_read' => 1]);


        $priorityTaskComments = PriorityTaskComments::select('priority_task_comments.*','priority_task_items.created_by as tast_created','users.name')
        ->join('priority_task_items' ,'priority_task_comments.priority_item_task_id' ,'priority_task_items.id')
        ->join('users' ,'users.id' ,'priority_task_comments.created_by')
        ->where('priority_item_task_id', $request->priority_item_task_id)
        ->get(); 

        return $this->sendResponse($priorityTaskComments->toArray(), 'Priority Task Comments retrieved successfully');
    }

    /**
     * Store a newly created PriorityTaskComments in storage.
     * POST /priorityTaskComments
     *
     * @param CreatePriorityTaskCommentsAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePriorityTaskCommentsAPIRequest $request)
    {
        $user_data = Auth::user();
        $input = $request->all();
        $input['created_by'] = $user_data->id ; 
        $priorityTaskComments = $this->priorityTaskCommentsRepository->create($input);

        return $this->sendResponse($priorityTaskComments->toArray(), 'Priority Task Comments saved successfully');
    }

    /**
     * Display the specified PriorityTaskComments.
     * GET|HEAD /priorityTaskComments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PriorityTaskComments $priorityTaskComments */
        $priorityTaskComments = $this->priorityTaskCommentsRepository->find($id);

        if (empty($priorityTaskComments)) {
            return $this->sendError('Priority Task Comments not found');
        }

        return $this->sendResponse($priorityTaskComments->toArray(), 'Priority Task Comments retrieved successfully');
    }

    /**
     * Update the specified PriorityTaskComments in storage.
     * PUT/PATCH /priorityTaskComments/{id}
     *
     * @param int $id
     * @param UpdatePriorityTaskCommentsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePriorityTaskCommentsAPIRequest $request)
    {
        $input = $request->all();

        /** @var PriorityTaskComments $priorityTaskComments */
        $priorityTaskComments = $this->priorityTaskCommentsRepository->find($id);

        if (empty($priorityTaskComments)) {
            return $this->sendError('Priority Task Comments not found');
        }

        $priorityTaskComments = $this->priorityTaskCommentsRepository->update($input, $id);

        return $this->sendResponse($priorityTaskComments->toArray(), 'PriorityTaskComments updated successfully');
    }

    /**
     * Remove the specified PriorityTaskComments from storage.
     * DELETE /priorityTaskComments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PriorityTaskComments $priorityTaskComments */
        $priorityTaskComments = $this->priorityTaskCommentsRepository->find($id);

        if (empty($priorityTaskComments)) {
            return $this->sendError('Priority Task Comments not found');
        }

        $priorityTaskComments->delete();

        return $this->sendSuccess('Priority Task Comments deleted successfully');
    }
}
