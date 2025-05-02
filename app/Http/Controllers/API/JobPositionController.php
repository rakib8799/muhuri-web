<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\HR\JobPositionService;
use Illuminate\Http\Request;

class JobPositionController extends Controller
{
    protected JobPositionService $jobPositionService;

    public function __construct(JobPositionService $jobPositionService)
    {
        $this->jobPositionService = $jobPositionService;
    }

    public function syncJobPositions(Request $request)
    {
        $this->jobPositionService->syncData($request);
        return response()->json(['message' => 'Job Positions synced successfully']);
    }

    public function jobPositionDelete(Request $request)
    {
        $jobPosition = $request->json()->all();
        $this->jobPositionService->syncJobPositionDelete($jobPosition);
        return response()->json(['message' => 'Job Position deleted successfully']);
    }
}
