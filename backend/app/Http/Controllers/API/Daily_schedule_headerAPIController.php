<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDaily_schedule_headerAPIRequest;
use App\Http\Requests\API\UpdateDaily_schedule_headerAPIRequest;
use App\Models\Daily_schedule_header;
use App\Repositories\Daily_schedule_headerRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth, DB;
/**
 * Class Daily_schedule_headerController
 * @package App\Http\Controllers\API
 */

class Daily_schedule_headerAPIController extends AppBaseController
{
    /** @var  Daily_schedule_headerRepository */
    private $dailyScheduleHeaderRepository;

    public function __construct(Daily_schedule_headerRepository $dailyScheduleHeaderRepo)
    {
        $this->dailyScheduleHeaderRepository = $dailyScheduleHeaderRepo;
    }

    /**
     * Display a listing of the Daily_schedule_header.
     * GET|HEAD /dailyScheduleHeaders
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $user_data = Auth::user();  
        $request['dept_id'] = $user_data->dept_id ;
        $header  = Daily_schedule_header::limit('200'); 
        $header->where('dept_id', $user_data->dept_id );
        $header->orderBy('serialno','ASC');
        $result = $header->get();
        // $dailyScheduleHeaders = $this->dailyScheduleHeaderRepository->all(
        //     $request->except(['skip', 'limit']),
        //     $request->get('skip'),
        //     $request->get('limit')
        // );

        return $this->sendResponse($result, 'Daily Schedule Headers retrieved successfully');
    }

    /**
     * Store a newly created Daily_schedule_header in storage.
     * POST /dailyScheduleHeaders
     *
     * @param CreateDaily_schedule_headerAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDaily_schedule_headerAPIRequest $request)
    {
        $input = $request->all();

        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->create($input);

        return $this->sendResponse($dailyScheduleHeader->toArray(), 'Daily Schedule Header saved successfully');
    }

    /**
     * Display the specified Daily_schedule_header.
     * GET|HEAD /dailyScheduleHeaders/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Daily_schedule_header $dailyScheduleHeader */
        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->find($id);

        if (empty($dailyScheduleHeader)) {
            return $this->sendError('Daily Schedule Header not found');
        }

        return $this->sendResponse($dailyScheduleHeader->toArray(), 'Daily Schedule Header retrieved successfully');
    }

    /**
     * Update the specified Daily_schedule_header in storage.
     * PUT/PATCH /dailyScheduleHeaders/{id}
     *
     * @param int $id
     * @param UpdateDaily_schedule_headerAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDaily_schedule_headerAPIRequest $request)
    {
        $input = $request->all();

        /** @var Daily_schedule_header $dailyScheduleHeader */
        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->find($id);

        if (empty($dailyScheduleHeader)) {
            return $this->sendError('Daily Schedule Header not found');
        }

        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->update($input, $id);

        return $this->sendResponse($dailyScheduleHeader->toArray(), 'Daily_schedule_header updated successfully');
    }

    /**
     * Remove the specified Daily_schedule_header from storage.
     * DELETE /dailyScheduleHeaders/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Daily_schedule_header $dailyScheduleHeader */
        $dailyScheduleHeader = $this->dailyScheduleHeaderRepository->find($id);

        if (empty($dailyScheduleHeader)) {
            return $this->sendError('Daily Schedule Header not found');
        }

        $dailyScheduleHeader->delete();

        return $this->sendSuccess('Daily Schedule Header deleted successfully');
    }
}
