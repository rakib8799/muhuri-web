<?php

namespace App\Services\Sale;

use App\Models\Buyer\Buyer;
use App\Models\Sale\Sale;
use App\Services\Buyer\BuyerPaymentService;
use App\Services\Buyer\BuyerSummaryService;
use App\Services\SummaryService;
use Illuminate\Support\Facades\DB;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;
use App\Services\FiscalYear\FiscalYearService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SaleService extends BaseModelService
{
    protected FiscalYearService $fiscalYearService;
    protected SaleItemService $saleItemService;
    protected SaleSummaryService $saleSummeryService;
    protected BuyerSummaryService $buyerSummaryService;
    protected BuyerPaymentService $buyerPaymentService;
    protected SummaryService $summaryService;

    public function __construct(FiscalYearService $fiscalYearService, SaleItemService $saleItemService, SaleSummaryService $saleSummaryService, BuyerSummaryService $buyerSummaryService, BuyerPaymentService $buyerPaymentService, SummaryService $summaryService)
    {
        $this->fiscalYearService = $fiscalYearService;
        $this->saleItemService = $saleItemService;
        $this->saleSummeryService = $saleSummaryService;
        $this->buyerSummaryService = $buyerSummaryService;
        $this->buyerPaymentService = $buyerPaymentService;
        $this->summaryService = $summaryService;
    }

    public function model(): string
    {
        return Sale::class;
    }

    public function addSaleToSummaries(Sale $sale)
    {
        $this->buyerSummaryService->increaseTotalTransaction($sale->buyer, $sale);
        $this->summaryService->increaseTotalSale($sale);

        return $sale;
    }

    public function removeSaleFromSummaries(Sale $sale)
    {
        $this->buyerSummaryService->decreaseTotalTransaction($sale->buyer, $sale);
        $this->summaryService->decreaseTotalSale($sale);

        return $sale;
    }

    public function createSale(array $data)
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
            'buyer_id' => $data['buyer_id'],
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
            $this->addSaleToSummaries($sale);

            $saleItems = $this->saleItemService->createSaleItems($data['items'], $sale->id);
            foreach($saleItems as $saleItem){
                $this->saleSummeryService->addSaleItemToSummary($sale, $saleItem);
            }

            if($sale->paid_amount > 0) {
                $paymentData = [
                    'payment_date' => $sale->sale_date,
                    'amount' => $sale->paid_amount,
                ];
                $this->buyerPaymentService->createPayment($sale->buyer, $paymentData);
            }

            return $sale;
        });
    }

    public function getSales(Buyer $buyer = null, $year = null)
    {
        if(!$year){
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }

        $sales = $this->model()::where('fiscal_year', $year)
            ->when($buyer, function ($query) use ($buyer){
                return $query->where('buyer_id', $buyer->id);
            })->orderBy('id', 'desc')->get();

        return $sales;
    }

    public function updateSale($sale, array $data)
    {
        return DB::transaction(function () use ($sale, $data) {
            $this->removeSaleFromSummaries($sale);
            $this->decreaseSaleGrantTotal($sale);

            $saleNote = isset($data['sale_note']) ? $data['sale_note'] : '';

            $sale->sale_date = $data['sale_date'];
            $sale->discount = $data['discount'] ?? 0;
            $sale->paid_amount = $data['paid_amount'] ?? 0;
            $sale->note = $saleNote;
            $sale->save();

            $this->increaseSaleGrandTotal($sale);
            $this->addSaleToSummaries($sale);

            return $sale;
        });
    }

    public function increaseSaleGrandTotal($sale)
    {
        $sale->sub_total += $sale->saleItems->sum('total_price');
        $sale->grand_total = $sale->sub_total - $sale->discount;
        $sale->save();
    }

    public function decreaseSaleGrantTotal($sale)
    {
        $sale->sub_total -= $sale->saleItems->sum('total_price');
        $sale->grand_total = $sale->sub_total - $sale->discount;
        $sale->save();
    }

    public function deleteSale(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $this->removeSaleFromSummaries($sale);
            foreach ($sale->saleItems as $saleItem) {
                $this->saleSummeryService->removeSaleItemFromSummary($sale, $saleItem);
            }
            $this->saleItemService->getSaleItems($sale)->each(function ($saleItem) {
                $saleItem->delete();
            });
            $sale->delete();
        });
        return true;
    }

    public function getGrandTotalById($buyerId)
    {
        return $this->model()::where('buyer_id', $buyerId)->sum('grand_total');
    }

    public function getSaleById($saleId)
    {
        return $this->model()::with('saleItems')->where('id', $saleId)->first();
    }

    public function getSalesDateAndTypeWise($request)
    {
        $from = $request->from_date ?? Carbon::today()->toDateString();
        $to = $request->to_date ?? Carbon::today()->toDateString();
        $saleType = $request->sale_type;

        $query = $this->model()::whereBetween('sale_date', [$from, $to]);

        if ($saleType) {
            $query->where('sale_type', $saleType);
        }

        $sales = $query->with('buyer')->get();

        return [
            'sales' => $sales,
            'total_amount' => $sales->sum('grand_total'),
        ];
    }

    public function getSaleTypesWithName()
    {
        return $this->model()::select('sale_type')
            ->distinct()
            ->get()
            ->map(function ($saleType) {
                return [
                    'value' => $saleType->sale_type,
                    'text' => $saleType->sale_type === 'larvae' ? 'পোনা' : 'অন্যান্য',
                ];
            }
        );
    }
}
