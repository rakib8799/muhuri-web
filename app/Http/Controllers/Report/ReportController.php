<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Buyer\BuyerService;
use App\Services\SummaryService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class ReportController extends Controller implements HasMiddleware
{
    private SummaryService $summaryService;
    private BuyerService $buyerService;

    public function __construct(SummaryService $summaryService, BuyerService $buyerService)
    {
        $this->summaryService = $summaryService;
        $this->buyerService = $buyerService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-atAGlance', only: ['getSummariesReport']),
            new Middleware('permission:can-view-buyer-due', only: ['getBuyerDue']),
        ];
    }

    public function getSummariesReport()
    {
        $breadcrumbs = Breadcrumbs::generate('atAGlance');
        $summaries = $this->summaryService->getSummaries();
        $responseData = [
            'summaries'=> $summaries,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.report.atAGlance')
        ];

        return Inertia::render('Report/AtAGlance', $responseData);
    }

    public function getBuyerDue()
    {
        $breadcrumbs = Breadcrumbs::generate('buyerDue');
        $buyerDues = $this->buyerService->getBuyerDue();
        $responseData = [
            'buyerDues' => $buyerDues,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.report.buyerDue')
        ];

        return Inertia::render('Report/BuyerDue', $responseData);
    }

    public function getSupplierDue()
    {
        $suppliers = $this->supplierService->getSuppliers();
        $supplierDue = [];
        foreach ($suppliers as $supplier) {
            $supplierSummary = $this->supplierService->getSupplierDetails($supplier);
            if ($supplierSummary->total_due == 0) {
                continue;
            }
            $supplierDue[] = [
                'id' => $supplier->id,
                'supplier' => $supplier->name,
                'due' => $supplierSummary->total_due,
                'mobile' => $supplier->mobile_number ?? '',
            ];
        }
        return $supplierDue;
    }
}
