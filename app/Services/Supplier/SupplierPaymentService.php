<?php

namespace App\Services\Supplier;

use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierPayment;
use App\Services\Core\BaseModelService;
use App\Services\Expense\ExpenseService;
use App\Services\Core\HelperService;
use App\Services\FiscalYear\FiscalYearService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SupplierPaymentService extends BaseModelService
{
    private SupplierSummaryService $supplierSummaryService;
    private ExpenseService $expenseService;

    private FiscalYearService $fiscalYearService;

    public function __construct(
        SupplierSummaryService $supplierSummaryService,
        ExpenseService         $expenseService,
        FiscalYearService      $fiscalYearService,
    )
    {
        $this->supplierSummaryService = $supplierSummaryService;
        $this->expenseService = $expenseService;
        $this->fiscalYearService = $fiscalYearService;
    }

    public function model(): string
    {
        return SupplierPayment::class;
    }

    public function createPayment(Supplier $supplier, array $data): SupplierPayment
    {
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($data['payment_date']);
        $data['supplier_id'] = $supplier->id;
        $data['fiscal_year_id'] = $fiscalYear->id;
        $data['fiscal_year'] = $fiscalYear->fiscal_year;

        return DB::transaction(function () use ($supplier, $data) {
            $payment = $this->create($data);
            $this->supplierSummaryService->addPaymentToSummary($supplier, $payment);
            $this->expenseService->addSupplierPaymentToExpense($payment, SupplierPayment::class);
            return $payment;
        });
    }

    public function updatePayment(SupplierPayment $payment, array $data)
    {
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($data['payment_date']);
        return DB::transaction(function () use ($payment, $data, $fiscalYear) {
            $this->removePaymentFromSummaries($payment);

            $payment->amount = $data['amount'];
            $payment->payment_date = $data['payment_date'];
            $payment->fiscal_year_id = $fiscalYear->id;
            $payment->fiscal_year = $fiscalYear->fiscal_year;
            $payment->payment_method = $data['payment_method'];
            $payment->note = $data['note'] ?? $payment->note;
            $payment->save();
            $note = HelperService::getPaymentNote($payment);
            $this->supplierSummaryService->addPaymentToSummary($payment->supplier, $payment);
            $this->expenseService->updatePaymentToExpense($payment, SupplierPayment::class, $note);
            return $payment;
        });
    }

    public function deletePayment(SupplierPayment $payment): SupplierPayment
    {
        return DB::transaction(function () use ($payment) {
            $this->removePaymentFromSummaries($payment);
            $this->expenseService->deletePaymentFromExpense($payment, SupplierPayment::class);
            $payment->delete();
            $payment->deleted_by = auth()->user()->id;
            $payment->save();
            return $payment;
        });
    }

    private function getSupplierPaymentQuery()
    {
        return SupplierPayment::with([
            'supplier' => function ($supplierQuery) {
                $supplierQuery->select('id', 'name');
            }
        ]);
    }

    public function getPayments(
        Supplier $supplier = null,
                 $year = null,
                 $fromDate = null,
                 $toDate = null,
                 $orderBy = 'payment_date',
                 $direction = 'desc',
                 $page = 1,
    )
    {
        if (!$year) {
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }
        $supplierId = $supplier ? $supplier->id : 'all';
        $workspaceName = app('workspaceName');
        $cacheKey = "{$workspaceName}-supplier-payments-{$supplierId}-{$year}-{$fromDate}-{$toDate}-{$orderBy}-{$direction}-{$page}";
        $cacheTag = "{$workspaceName}-supplier-payment";
        if (Cache::tags($cacheTag)->has($cacheKey)) {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $paymentQuery = $this->getSupplierPaymentQuery();

        $supplierPayments = $paymentQuery
            ->when($supplier, function ($query) use ($supplier) {
                return $query->where('supplier_id', $supplier->id);
            })
            ->when($year, function ($query) use ($year) {
                return $query->where('fiscal_year', $year);
            })
            ->when($fromDate, function ($query) use ($fromDate) {
                return $query->where('payment_date', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->where('payment_date', '<=', $toDate);
            })
            ->orderBy($orderBy, $direction)
            ->get();

        Cache::tags($cacheTag)->put($cacheKey, $supplierPayments);
        return $supplierPayments;
    }

    /**
     * @param SupplierPayment $payment
     * @return void
     */
    public function removePaymentFromSummaries(SupplierPayment $payment): void
    {
        $this->supplierSummaryService->removePaymentFromSummary($payment->supplier, $payment);
    }
}
