<?php

namespace App\Http\Controllers\SMS;

use App\Http\Controllers\Controller;
use App\Services\ConfigurationService;
use App\Services\Payment\PaymentMethodService;
use App\Services\SMS\SMSCreditService;
use App\Services\SMS\SMSLogService;
use App\Services\SMS\SMSPurchaseService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class SMSController extends Controller implements HasMiddleware
{
    protected SMSCreditService $smsCreditService;
    protected SMSPurchaseService $smsPurchaseService;
    protected PaymentMethodService $paymentMethodService;
    protected ConfigurationService $configurationService;
    protected SMSLogService $smsLogService;


    public function __construct(
        SMSCreditService $smsCreditService,
        SMSPurchaseService $smsPurchaseService,
        PaymentMethodService $paymentMethodService,
        ConfigurationService $configurationService,
        SMSLogService $smsLogService
    )
    {
        $this->smsCreditService = $smsCreditService;
        $this->smsPurchaseService = $smsPurchaseService;
        $this->paymentMethodService = $paymentMethodService;
        $this->configurationService = $configurationService;
        $this->smsLogService = $smsLogService;
    }

    public static function middleware(): array
    {
        return [
            new middleware('permission:can-view-sms-dashboard', only: ['index']),
            new middleware('permission:can-purchase-sms', only: ['purchaseSMS']),
            new middleware('permission:can-view-purchase-sms-history', only: ['purchaseSMSHistory']),
            new middleware('permission:can-view-sms-template', only: ['getSMSTemplate']),
            new middleware('permission:can-view-sms-log', only: ['getSMSLog']),
            new middleware('permission:can-send-sms', only: ['sendSMS']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('smsDashboard');
        $smsCredit = $this->smsCreditService->getOrCreateSMSCredit();
        $responseData = [
            'smsCredit' => $smsCredit,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sms.index')
        ];

        return Inertia::render('SMS/Index', $responseData);
    }

    public function smsPurchase()
    {
        $breadcrumbs = Breadcrumbs::generate('smsPurchase');
        $smsCredit = $this->smsCreditService->getOrCreateSMSCredit();
        $paymentMethods = $this->paymentMethodService->getActivePaymentMethods();
        $smsRate = $this->configurationService->getConfiguration('sms_rate');
        $responseData = [
            'smsCredit' => $smsCredit,
            'smsRate' => $smsRate,
            'breadcrumbs' => $breadcrumbs,
            'paymentMethods' => $paymentMethods,
            'pageTitle' => __('pageTitle.custom.sms.purchaseSMS')
        ];

        return Inertia::render('SMS/SMSPurchase', $responseData);
    }

    // store method for sms purchase and return json data
    public function storeSMSPurchase(Request $request)
    {
        $request->validate([
            'sms_quantity' => 'required|numeric',
        ]);
        $smsRate = 2; // TODO: get from settings

        $smsPurchaseData = [
            'sms_quantity' => $request->sms_quantity,
            'sms_rate' => $smsRate,
            'total_price' => $request->sms_quantity * $smsRate,
            'payment_status' => 'pending',
            'transaction_id' => null
        ];

        $smsPurchase = $this->smsPurchaseService->create($smsPurchaseData);
        if ($smsPurchase) {
            return response()->json([
                'message' => 'SMS purchase successful',
                'data' => $smsPurchase,
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'message' => 'SMS purchase failed',
                'status' => 'error'
            ], 500);
        }
    }

    public function smsPurchaseReport()
    {
        $breadcrumbs = Breadcrumbs::generate('smsPurchaseReport');
        $smsPurchases = $this->smsPurchaseService->getSMSPurchases('paid');
        $responseData = [
            'smsPurchases' => $smsPurchases,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sms.purchaseSMSHistory')
        ];

        return Inertia::render('SMS/SMSPurchaseReport', $responseData);
    }

    public function smsLog()
    {
        $breadcrumbs = Breadcrumbs::generate('smsLog');
        $smsLogs = $this->smsLogService->all();
        $responseData = [
            'smsLogs' => $smsLogs,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.sms.smsLog')
        ];

        return Inertia::render('SMS/SMSLog', $responseData);
    }

}
