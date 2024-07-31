<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTeamMemberAPIRequest;
use App\Http\Requests\API\UpdateTeamMemberAPIRequest;
use App\Models\TeamMember;
use App\Repositories\TeamMemberRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\TeamMemberResource;
use Response;

/**
 * Class TeamMemberController
 * @package App\Http\Controllers\API
 */

class TeamMemberAPIController extends AppBaseController
{
    /** @var  TeamMemberRepository */
    private $teamMemberRepository;

    public function __construct(TeamMemberRepository $teamMemberRepo)
    {
        $this->teamMemberRepository = $teamMemberRepo;
    }

    /**
     * Display a listing of the TeamMember.
     * GET|HEAD /teamMembers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $teamMembers = $this->teamMemberRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );
        $teamMembers = TeamMemberResource::collection($teamMembers);
        return $this->sendResponse($teamMembers, 'Team Members retrieved successfully');
    }

    /**
     * Store a newly created TeamMember in storage.
     * POST /teamMembers
     *
     * @param CreateTeamMemberAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTeamMemberAPIRequest $request)
    {
        $input = $request->all();
        $teamMember = $this->teamMemberRepository->create($input);

        return $this->sendResponse($teamMember->toArray(), 'Team Member saved successfully');
    }

    /**
     * Display the specified TeamMember.
     * GET|HEAD /teamMembers/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TeamMember $teamMember */
        $teamMember = $this->teamMemberRepository->find($id);

        if (empty($teamMember)) {
            return $this->sendError('Team Member not found');
        }

        return $this->sendResponse($teamMember->toArray(), 'Team Member retrieved successfully');
    }

    /**
     * Update the specified TeamMember in storage.
     * PUT/PATCH /teamMembers/{id}
     *
     * @param int $id
     * @param UpdateTeamMemberAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTeamMemberAPIRequest $request)
    {
        $input = $request->all();

        /** @var TeamMember $teamMember */
        $teamMember = $this->teamMemberRepository->find($id);

        if (empty($teamMember)) {
            return $this->sendError('Team Member not found');
        }

        $teamMember = $this->teamMemberRepository->update($input, $id);

        return $this->sendResponse($teamMember->toArray(), 'TeamMember updated successfully');
    }

    /**
     * Remove the specified TeamMember from storage.
     * DELETE /teamMembers/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TeamMember $teamMember */
        $teamMember = $this->teamMemberRepository->find($id);

        if (empty($teamMember)) {
            return $this->sendError('Team Member not found');
        }

        $teamMember->delete();

        return $this->sendSuccess('Team Member deleted successfully');
    }
}
