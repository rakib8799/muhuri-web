<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FiscalYear\FiscalYearService;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    protected FiscalYearService $fiscalYearService;

    public function __construct(FiscalYearService $fiscalYearService)
    {
        $this->fiscalYearService = $fiscalYearService;
    }

    public function syncFiscalYears(Request $request)
    {
        $this->fiscalYearService->syncData($request);
        return response()->json(['message' => 'Fiscal years synced successfully']);
    }
}
