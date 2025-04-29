<?php

namespace App\Http\Controllers;

use App\Services\SMS\SMSCreditService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected SMSCreditService $smsCreditService;

    public function __construct(SMSCreditService $smsCreditService)
    {
        $this->smsCreditService = $smsCreditService;
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('dashboard');
        $permissions = session('permissions');
        $smsCredit = $this->smsCreditService->getOrCreateSMSCredit();

        $responseData = [
            'smsCredit' => $smsCredit,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.home'),
            'permissions' => $permissions
        ];

        return Inertia::render('Dashboard', $responseData);
    }
}
