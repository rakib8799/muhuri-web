<?php

namespace App\Http\Controllers\HR;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\JobPosition\CreateJobPositionRequest;
use App\Http\Requests\HR\JobPosition\UpdateJobPositionRequest;
use App\Models\HR\JobPosition;
use App\Services\HR\JobPositionService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class JobPositionController extends Controller implements HasMiddleware
{
    protected JobPositionService $jobPositionService;

    public function __construct(JobPositionService $jobPositionService)
    {
        $this->jobPositionService = $jobPositionService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-job-position', only: ['store', 'storeJobPosition']),
            new Middleware('permission:can-edit-job-position', only: ['update', 'changeStatus']),
            new Middleware('permission:can-delete-job-position', only: ['destroy']),
            new Middleware('permission:can-view-job-position', only: ['index', 'getJobPositions']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('jobPositions');
        $jobPositions = $this->jobPositionService->getJobPositions();
        $responseData = [
            'jobPositions' => $jobPositions,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.jobPosition.index'),
        ];
        return Inertia::render('JobPosition/Index', $responseData);
    }

    public function store(CreateJobPositionRequest $request)
    {
        $validatedData = $request->validated();
        $jobPosition = $this->jobPositionService->createJobPosition($validatedData);
        $status = $jobPosition ? Constants::SUCCESS : Constants::ERROR;
        $message = $jobPosition ? __('message.custom.jobPosition.store.success') : __('message.custom.jobPosition.store.error');
        return Redirect::route('job-positions.index')->with($status, $message);
    }

    public function getJobPositions()
    {
        $jobPositions = $this->jobPositionService->getjobPositions();
        $response = [
            'success' => true,
            'message' => 'Get Job Position List',
            'data' => [
                'jobPositions' => $jobPositions
            ]
        ];
        $httpStatus = 200;
        return response()->json($response, $httpStatus);
    }

    public function storeJobPosition(CreateJobPositionRequest $request)
    {
        $validatedData = $request->validated();
        $this->jobPositionService->createJobPosition($validatedData);
        return Redirect::back();
    }

    public function update(UpdateJobPositionRequest $request, JobPosition $jobPosition)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->jobPositionService->update($jobPosition, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.jobPosition.update.success') : __('message.custom.jobPosition.update.error');
        return Redirect::route('job-positions.index')->with($status, $message);
    }

    public function destroy(JobPosition $jobPosition)
    {
        $isDeleted = $this->jobPositionService->deleteJobPosition($jobPosition);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? __('message.custom.jobPosition.destroy.success') : __('message.custom.jobPosition.destroy.error');
        return Redirect::route('job-positions.index')->with($status, $message);
    }

    public function changeStatus(Request $request, JobPosition $jobPosition)
    {
        $jobPosition = $this->jobPositionService->changeStatus($jobPosition, $request->is_active);
        $status = $jobPosition ? Constants::SUCCESS : Constants::ERROR;
        $message = $jobPosition ? ($jobPosition->is_active ? __('message.custom.jobPosition.changeStatus.activate') : __('message.custom.jobPosition.changeStatus.deactivate')) : __('message.custom.jobPosition.changeStatus.error');
        return Redirect::route('job-positions.index')->with($status, $message);
    }
}
