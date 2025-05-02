<?php

namespace App\Services\Buyer;

use App\Models\Buyer\Buyer;
use App\Models\Buyer\BuyerPayment;
use App\Services\FiscalYear\FiscalYearService;
use App\Services\SummaryService;
use Illuminate\Support\Facades\DB;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;

class BuyerPaymentService extends BaseModelService
{
    private BuyerSummaryService $buyerSummaryService;
    private FiscalYearService $fiscalYearService;
    private SummaryService  $summaryService;

    public function __construct(BuyerSummaryService $buyerSummaryService, FiscalYearService $fiscalYearService, SummaryService  $summaryService)
    {
        $this->buyerSummaryService = $buyerSummaryService;
        $this->fiscalYearService = $fiscalYearService;
        $this->summaryService = $summaryService;
    }

    public function model(): string
    {
        return BuyerPayment::class;
    }

    public function createPayment(Buyer $buyer, array $data)
    {
        $fiscalYear = $this->fiscalYearService-> getFiscalYearByDate($data['payment_date']);
        $invoiceNumber = HelperService::generateInvoiceNumber('P-', $this->model());
        $data['buyer_id'] = $buyer->id;
        $data['invoice_number'] = $invoiceNumber;
        $data['fiscal_year_id'] = $fiscalYear->id;
        $data['fiscal_year'] = $fiscalYear->fiscal_year;
        return DB::transaction(function () use ($buyer, $data) {
            $payment = $this->create($data);
            $this->buyerSummaryService->addPaymentToSummery($buyer, $payment);
            $this->summaryService->addBuyerPaymentToSummary($payment);
            return $payment;
        });
    }

    public function getPayments(
        Buyer $buyer = null,
        $year = null,
        $fromDate = null,
        $toDate = null,
        $orderBy = 'payment_date',
        $direction = 'desc',
        ){
        if(!$year){
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }

        $payments = $this->model()::where('fiscal_year', $year)
            ->when($buyer, function ($query) use ($buyer) {
                return $query->where('buyer_id', $buyer->id);
            })
            ->when($year, function ($query) use ($year){
                return $query->where('fiscal_year', $year);
            })
            ->when($fromDate, function ($query) use ($fromDate){
                return $query->where('payment_date','>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate){
                return $query->where('payment_date', '<=', $toDate);
            })
            ->orderBy($orderBy, $direction)
            ->get();

        return $payments;
    }

    public function updatePayment(BuyerPayment $payment, array $data)
    {
        $fiscalYear = $this->fiscalYearService-> getFiscalYearByDate($data['payment_date']);

        return DB::transaction(function () use ($payment, $data, $fiscalYear) {
            $this->removePaymentFromSummaries($payment);
            $payment->amount = $data['amount'];
            $payment->payment_date = $data['payment_date'];
            $payment->fiscal_year_id = $fiscalYear->id;
            $payment->fiscal_year = $fiscalYear->fiscal_year;
            $payment->payment_method = $data['payment_method'];
            $payment->note = $data['note'] ?? $payment->note;
            $payment->save();

            $this->buyerSummaryService->addPaymentToSummery($payment->buyer, $payment);
            $this->summaryService->addBuyerPaymentToSummary($payment);

            return $payment;
        });
    }

    public function checkBuyerPaymentAccess(Buyer $buyer, BuyerPayment $payment)
    {
        if($buyer->id != $payment->buyer_id){
            return false;
        }
        return true;
    }

    public function deletePayment(BuyerPayment $payment): BuyerPayment
    {
        return DB::transaction(function () use ($payment) {
            $this->removePaymentFromSummaries($payment);
            $payment->delete();
            $payment->deleted_by = auth()->user()->id;
            $payment->save();
            return $payment;
        });
    }

    public function removePaymentFromSummaries(BuyerPayment $payment)
    {
        $this->buyerSummaryService->removePaymentFromSummary($payment->buyer, $payment);
        $this->summaryService->removeBuyerPaymentFromSummary($payment);
    }

}
