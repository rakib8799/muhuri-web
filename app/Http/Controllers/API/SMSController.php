<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SMS\SMSService;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    private SMSService $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function addFreeSMS(Request $request)
    {
        $this->smsService->addFreeSMS($request);
        return response()->json(['message' => 'SMS synced successfully.']);
    }
}
