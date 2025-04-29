<?php

namespace App\Services\Purchase;

use App\Models\Supplier\Supplier;
use App\Models\Purchase\Purchase;
use App\Services\Supplier\SupplierPaymentService;
use App\Services\Supplier\SupplierSummaryService;
use App\Services\SummaryService;
use Illuminate\Support\Facades\DB;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;
use App\Services\FiscalYear\FiscalYearService;

class PurchaseService extends BaseModelService
{
    protected FiscalYearService $fiscalYearService;
    protected PurchaseItemService $saleItemService;
    protected PurchaseSummaryService $saleSummeryService;
    protected SupplierSummaryService $supplierSummaryService;
    protected SupplierPaymentService $supplierPaymentService;
    protected SummaryService $summaryService;

    public function __construct(FiscalYearService $fiscalYearService, PurchaseItemService $saleItemService, PurchaseSummaryService $saleSummaryService, SupplierSummaryService $supplierSummaryService, SupplierPaymentService $supplierPaymentService, SummaryService $summaryService)
    {
        $this->fiscalYearService = $fiscalYearService;
        $this->saleItemService = $saleItemService;
        $this->saleSummeryService = $saleSummaryService;
        $this->supplierSummaryService = $supplierSummaryService;
        $this->supplierPaymentService = $supplierPaymentService;
        $this->summaryService = $summaryService;
    }

    public function model(): string
    {
        return Purchase::class;
    }

    public function addPurchaseToSummaries(Purchase $sale)
    {
        $this->supplierSummaryService->increaseTotalTransaction($sale->supplier, $sale);
        $this->summaryService->increaseTotalPurchase($sale);

        return $sale;
    }

    public function removePurchaseFromSummaries(Purchase $sale)
    {
        $this->supplierSummaryService->decreaseTotalTransaction($sale->supplier, $sale);
        $this->summaryService->decreaseTotalPurchase($sale);

        return $sale;
    }

    public function createPurchase(array $data)
    {
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($data['sale_date']);
        $invoiceNumber = HelperService::generateInvoiceNumber('S-', $this->model());
        $subTotal = round(array_sum(array_map(fn($item) => floatval($item['total_price']), $data['items'])));
        $discount = isset($data['discount']) ? floatval($data['discount']) : 0;
        $paidAmount = isset($data['paid_amount']) ? floatval($data['paid_amount']) : 0;
        $grandTotal = max(0, $subTotal - $discount);
        $saleType = isset($data['sale_type']) ? $data['sale_type'] : 'other';
        $saleNote = isset($data['sale_note']) ? $data['sale_note'] : '';

        $saleData = [
            'supplier_id' => $data['supplier_id'],
            'invoice_number' => $invoiceNumber,
            'sale_date' => $data['sale_date'],
            'sub_total' => $subTotal,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'paid_amount' => $paidAmount,
            'fiscal_year' => $fiscalYear->fiscal_year,
            'fiscal_year_id' => $fiscalYear->id,
            'sale_type' => $saleType,
            'note' => $saleNote
        ];

        return DB::transaction(function () use ($saleData, $data) {
            $sale = $this->create($saleData);
            $this->addPurchaseToSummaries($sale);

            $saleItems = $this->saleItemService->createPurchaseItems($data['items'], $sale->id);
            foreach($saleItems as $saleItem){
                $this->saleSummeryService->addPurchaseItemToSummary($sale, $saleItem);
            }

            if($sale->paid_amount > 0) {
                $paymentData = [
                    'payment_date' => $sale->sale_date,
                    'amount' => $sale->paid_amount,
                ];
                $this->supplierPaymentService->createPayment($sale->supplier, $paymentData);
            }

            return $sale;
        });
    }

    public function getPurchases(Supplier $supplier = null, $year = null)
    {
        if(!$year){
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }

        $sales = $this->model()::where('fiscal_year', $year)
            ->when($supplier, function ($query) use ($supplier){
                return $query->where('supplier_id', $supplier->id);
            })->orderBy('id', 'desc')->get();

        return $sales;
    }

    public function updatePurchase($sale, array $data)
    {
        return DB::transaction(function () use ($sale, $data) {
            $this->removePurchaseFromSummaries($sale);
            $this->decreasePurchaseGrantTotal($sale);

            $saleNote = isset($data['sale_note']) ? $data['sale_note'] : '';

            $sale->sale_date = $data['sale_date'];
            $sale->discount = $data['discount'] ?? 0;
            $sale->paid_amount = $data['paid_amount'] ?? 0;
            $sale->note = $saleNote;
            $sale->save();

            $this->increasePurchaseGrandTotal($sale);
            $this->addPurchaseToSummaries($sale);

            return $sale;
        });
    }

    public function increasePurchaseGrandTotal($sale)
    {
        $sale->sub_total += $sale->saleItems->sum('total_price');
        $sale->grand_total = $sale->sub_total - $sale->discount;
        $sale->save();
    }

    public function decreasePurchaseGrantTotal($sale)
    {
        $sale->sub_total -= $sale->saleItems->sum('total_price');
        $sale->grand_total = $sale->sub_total - $sale->discount;
        $sale->save();
    }

    public function deletePurchase(Purchase $sale)
    {
        DB::transaction(function () use ($sale) {
            $this->removePurchaseFromSummaries($sale);
            foreach ($sale->saleItems as $saleItem) {
                $this->saleSummeryService->removePurchaseItemFromSummary($sale, $saleItem);
            }
            $this->saleItemService->getPurchaseItems($sale)->each(function ($saleItem) {
                $saleItem->delete();
            });
            $sale->delete();
        });
        return true;
    }

    public function getGrandTotalById($supplierId)
    {
        return $this->model()::where('supplier_id', $supplierId)->sum('grand_total');
    }

    public function getPurchaseById($saleId)
    {
        return $this->model()::with('saleItems')->where('id', $saleId)->first();
    }
}
