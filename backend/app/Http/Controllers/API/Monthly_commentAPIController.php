<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMonthly_commentAPIRequest;
use App\Http\Requests\API\UpdateMonthly_commentAPIRequest;
use App\Models\Monthly_comment;
use App\Repositories\Monthly_commentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class Monthly_commentController
 * @package App\Http\Controllers\API
 */

class Monthly_commentAPIController extends AppBaseController
{
    /** @var  Monthly_commentRepository */
    private $monthlyCommentRepository;

    public function __construct(Monthly_commentRepository $monthlyCommentRepo)
    {
        $this->monthlyCommentRepository = $monthlyCommentRepo;
    }

    /**
     * Display a listing of the Monthly_comment.
     * GET|HEAD /monthlyComments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $monthlyComments = $this->monthlyCommentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($monthlyComments->toArray(), 'Monthly Comments retrieved successfully');
    }

    /**
     * Store a newly created Monthly_comment in storage.
     * POST /monthlyComments
     *
     * @param CreateMonthly_commentAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMonthly_commentAPIRequest $request)
    {
        $input = $request->all();

        $monthlyComment = $this->monthlyCommentRepository->create($input);

        return $this->sendResponse($monthlyComment->toArray(), 'Monthly Comment saved successfully');
    }

    /**
     * Display the specified Monthly_comment.
     * GET|HEAD /monthlyComments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Monthly_comment $monthlyComment */
        $monthlyComment = $this->monthlyCommentRepository->find($id);

        if (empty($monthlyComment)) {
            return $this->sendError('Monthly Comment not found');
        }

        return $this->sendResponse($monthlyComment->toArray(), 'Monthly Comment retrieved successfully');
    }

    /**
     * Update the specified Monthly_comment in storage.
     * PUT/PATCH /monthlyComments/{id}
     *
     * @param int $id
     * @param UpdateMonthly_commentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonthly_commentAPIRequest $request)
    {
        $input = $request->all();

        /** @var Monthly_comment $monthlyComment */
        $monthlyComment = $this->monthlyCommentRepository->find($id);

        if (empty($monthlyComment)) {
            return $this->sendError('Monthly Comment not found');
        }

        $monthlyComment = $this->monthlyCommentRepository->update($input, $id);

        return $this->sendResponse($monthlyComment->toArray(), 'Monthly_comment updated successfully');
    }

    /**
     * Remove the specified Monthly_comment from storage.
     * DELETE /monthlyComments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Monthly_comment $monthlyComment */
        $monthlyComment = $this->monthlyCommentRepository->find($id);

        if (empty($monthlyComment)) {
            return $this->sendError('Monthly Comment not found');
        }

        $monthlyComment->delete();

        return $this->sendSuccess('Monthly Comment deleted successfully');
    }
}
