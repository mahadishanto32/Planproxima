<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateMonthlyReportFileAPIRequest;
use App\Http\Requests\API\UpdateMonthlyReportFileAPIRequest;
use App\Models\MonthlyReportFile;
use App\Repositories\MonthlyReportFileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class MonthlyReportFileController
 * @package App\Http\Controllers\API
 */

class MonthlyReportFileAPIController extends AppBaseController
{
    /** @var  MonthlyReportFileRepository */
    private $monthlyReportFileRepository;

    public function __construct(MonthlyReportFileRepository $monthlyReportFileRepo)
    {
        $this->monthlyReportFileRepository = $monthlyReportFileRepo;
    }

    /**
     * Display a listing of the MonthlyReportFile.
     * GET|HEAD /monthlyReportFiles
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $monthlyReportFiles = $this->monthlyReportFileRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($monthlyReportFiles->toArray(), 'Monthly Report Files retrieved successfully');
    }

    /**
     * Store a newly created MonthlyReportFile in storage.
     * POST /monthlyReportFiles
     *
     * @param CreateMonthlyReportFileAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateMonthlyReportFileAPIRequest $request)
    {
        $input = $request->all();

        $monthlyReportFile = $this->monthlyReportFileRepository->create($input);

        return $this->sendResponse($monthlyReportFile->toArray(), 'Monthly Report File saved successfully');
    }

    /**
     * Display the specified MonthlyReportFile.
     * GET|HEAD /monthlyReportFiles/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var MonthlyReportFile $monthlyReportFile */
        $monthlyReportFile = $this->monthlyReportFileRepository->find($id);

        if (empty($monthlyReportFile)) {
            return $this->sendError('Monthly Report File not found');
        }

        return $this->sendResponse($monthlyReportFile->toArray(), 'Monthly Report File retrieved successfully');
    }

    /**
     * Update the specified MonthlyReportFile in storage.
     * PUT/PATCH /monthlyReportFiles/{id}
     *
     * @param int $id
     * @param UpdateMonthlyReportFileAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMonthlyReportFileAPIRequest $request)
    {
        $input = $request->all();

        /** @var MonthlyReportFile $monthlyReportFile */
        $monthlyReportFile = $this->monthlyReportFileRepository->find($id);

        if (empty($monthlyReportFile)) {
            return $this->sendError('Monthly Report File not found');
        }

        $monthlyReportFile = $this->monthlyReportFileRepository->update($input, $id);

        return $this->sendResponse($monthlyReportFile->toArray(), 'MonthlyReportFile updated successfully');
    }

    /**
     * Remove the specified MonthlyReportFile from storage.
     * DELETE /monthlyReportFiles/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var MonthlyReportFile $monthlyReportFile */
        $monthlyReportFile = $this->monthlyReportFileRepository->find($id);

        if (empty($monthlyReportFile)) {
            return $this->sendError('Monthly Report File not found');
        }

        $monthlyReportFile->delete();

        return $this->sendSuccess('Monthly Report File deleted successfully');
    }
}
