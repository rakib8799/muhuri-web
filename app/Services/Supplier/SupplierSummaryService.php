<?php

namespace App\Services\Supplier;

use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierPayment;
use App\Models\Supplier\SupplierSummary;
use App\Services\Core\BaseModelService;
use App\Services\FiscalYear\FiscalYearService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class SupplierSummaryService extends BaseModelService
{
    private FiscalYearService $fiscalYearService;

    public function __construct(FiscalYearService $fiscalYearService)
    {
        $this->fiscalYearService = $fiscalYearService;
    }

    public function model(): string
    {
        return SupplierSummary::class;
    }

    public function firstOrCreateSupplierSummary($supplier, $date)
    {
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($date);
        $data['fiscal_year_id'] = $fiscalYear->id;
        $data['fiscal_year'] = $fiscalYear->fiscal_year;
        $data['month'] = Carbon::parse($date)->format('m');

        return $this->model()::firstOrCreate([
            'supplier_id' => $supplier->id,
            'fiscal_year_id' => $fiscalYear->id,
            'month' => $data['month']
        ], $data);
    }

    public function getSupplierSummaries(Supplier $supplier, $month = null, $year = null)
    {
        if (!$year) {
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }
        $workspaceName = app('workspaceName');

        $cacheKey = "{$workspaceName}-supplier-summary-{$supplier->id}-{$year}";
        $cacheTag = "{$workspaceName}-supplier-summary-{$supplier->id}";
        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $query = $this->model()::where('supplier_id', $supplier->id)->where('fiscal_year', $year);
        if ($month !== null) {
            $query->whereMonth('month', $month);
        }
        $supplierSummaries = $query->get();

        $totalTransaction = 0;
        $totalPayment = 0;
        foreach ($supplierSummaries as $supplierSummary) {
            $totalTransaction += $supplierSummary->total_transaction;
            $totalPayment += $supplierSummary->total_payment;
            $supplierSummary->total_due = $supplierSummary->total_transaction > 0 ? $supplierSummary->total_transaction - $supplierSummary->total_payment : 0;
        }

        $totalDue = $totalTransaction - $totalPayment + $supplier->previous_due;
        $supplierSummariesData = [
            'total_transaction' => $totalTransaction,
            'total_payment' => $totalPayment,
            'total_due' => $totalDue,
            'supplier_summaries' => $supplierSummaries,
        ];

        Cache::tags($cacheTag)->put($cacheKey, $supplierSummariesData);
        return $supplierSummariesData;
    }

    public function getSupplierSummary(Supplier $supplier, $month = null, $year = null)
    {
        if (!$year) {
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }

        $workspaceName = app('workspaceName');

        $cacheKey = "{$workspaceName}-supplier-summary-{$supplier->id}-{$month}-{$year}";
        $cacheTag = "{$workspaceName}-supplier-summary-{$supplier->id}";
        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $query = $this->model()::where('supplier_id', $supplier->id)
            ->where('fiscal_year', $year);

        if ($month !== null) {
            $query->whereMonth('month', $month);
        }

        $supplierSummaries = $query->get();
        $totalTransaction = $supplierSummaries->sum('total_transaction');
        $totalPayment = $supplierSummaries->sum('total_payment');
        $totalDue = $totalTransaction - $totalPayment + $supplier->previous_due;

        $supplierSummary = [
            'total_transaction' => $totalTransaction,
            'total_payment' => $totalPayment,
            'total_due' => $totalDue,
        ];

        Cache::tags($cacheTag)->put($cacheKey, $supplierSummary);
        return $supplierSummary;
    }

    public function addPurchaseToSummary(Supplier $supplier, $purchase)
    {
        $supplierSummary = $this->firstOrCreateSupplierSummary($supplier, $purchase->purchase_date);
        $supplierSummary->total_transaction += $purchase->amount;
        return $supplierSummary->save();
    }

    public function addPaymentToSummary(Supplier $supplier, SupplierPayment $payment)
    {
        $supplierSummary = $this->firstOrCreateSupplierSummary($supplier, $payment->payment_date);
        $supplierSummary->total_payment += $payment->amount;
        return $supplierSummary->save();
    }

    public function removeExpenseFromSummary(Supplier $supplier, $expense)
    {
        $supplierSummary = $this->firstOrCreateSupplierSummary($supplier, $expense->expense_date);
        $supplierSummary->total_transaction -= $expense->amount;
        return $supplierSummary->save();
    }

    public function removePaymentFromSummary(Supplier $supplier, SupplierPayment $payment)
    {
        $supplierSummary = $this->firstOrCreateSupplierSummary($supplier, $payment->payment_date);
        $supplierSummary->total_payment -= $payment->amount;
        return $supplierSummary->save();
    }

    public function removePurchaseFromSummary(Supplier $supplier, $purchase)
    {
        $supplierSummary = $this->firstOrCreateSupplierSummary($supplier, $purchase->purchase_date);
        $supplierSummary->total_transaction -= $purchase->amount;
        return $supplierSummary->save();
    }

    public function increaseTotalTransaction(Supplier $supplier, $purchase)
    {
        $supplierSummary = $this->firstOrCreateSupplierSummary($supplier, $purchase->purchase_date);
        $supplierSummary->total_transaction += $purchase->grand_total;
        $supplierSummary->save();
    }

    public function decreaseTotalTransaction(Supplier $supplier, $purchase)
    {
        $supplierSummary = $this->firstOrCreateSupplierSummary($supplier, $purchase->purchase_date);
        $supplierSummary->total_transaction -= $purchase->grand_total;
        $supplierSummary->save();
    }
}
