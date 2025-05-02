<?php

namespace App\Services\Buyer;

use App\Services\FiscalYear\FiscalYearService;
use Carbon\Carbon;
use App\Models\Buyer\Buyer;
use App\Models\Buyer\BuyerPayment;
use App\Models\Buyer\BuyerSummary;
use Illuminate\Support\Facades\Cache;
use App\Services\Core\BaseModelService;

class BuyerSummaryService extends BaseModelService
{
    private FiscalYearService $fiscalYearService;

    public function __construct(FiscalYearService $fiscalYearService)
    {
        $this->fiscalYearService = $fiscalYearService;
    }

    public function model(): string
    {
        return BuyerSummary::class;
    }

    public function firstOrCreateBuyerSummary($buyer, $date)
    {
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($date);
        $data['fiscal_year_id'] = $fiscalYear->id;
        $data['fiscal_year'] = $fiscalYear->fiscal_year;
        $data['month'] = Carbon::parse($date)->format('m');

        return $this->model()::firstOrCreate([
            'buyer_id' => $buyer->id,
            'fiscal_year_id' => $fiscalYear->id,
            'month' => $data['month']
        ], $data);
    }

    public function addPaymentToSummery(Buyer $buyer, BuyerPayment $payment)
    {
        $buyerSummery = $this->firstOrCreateBuyerSummary($buyer, $payment->payment_date);
        $buyerSummery->total_payment += $payment->amount;
        return $buyerSummery->save();
    }

    public function removePaymentFromSummary(Buyer $buyer, BuyerPayment $payment)
    {
        $buyerSummary = $this->getBuyerSummeryByDate($buyer, $payment->payment_date);
        $buyerSummary->total_payment -= $payment->amount;
        return $buyerSummary->save();
    }

    public function getBuyerSummary(Buyer $buyer, $month = null, $year = null)
    {
        if(!$year){
            $fiscalYear = $this->fiscalYearService->getCurrentFiscalYear();
            $year = $fiscalYear->fiscal_year;
        }
        $workspaceName = app('workspaceName');

        $cacheKey = "{$workspaceName}-buyer-summary-{$buyer->id}-{$month}-{$year}";
        $cacheTag = "{$workspaceName}-buyer-summary-{$buyer->id}";

        if(Cache::tags($cacheTag)->has($cacheKey))
        {
            return Cache::tags($cacheTag)->get($cacheKey);
        }

        $query = $this->model()::where('buyer_id', $buyer->id)->where('fiscal_year', $year);

        if($month !== null){
            $query->whereMonth('month', $month);
        }

        $buyerSummaries = $query->get();

        $totalTransaction = $buyerSummaries->sum('total_transaction');
        $totalPayment = $buyerSummaries->sum('total_payment');
        $totalDue = $totalTransaction - $totalPayment + $buyer->previous_due;

        $buyerSummary = [
            'total_transaction' => $totalTransaction,
            'total_payment' => $totalPayment,
            'total_due' => $totalDue,
        ];

        Cache::tags($cacheTag)->put($cacheKey, $buyerSummary);
        return $buyerSummary;
    }

    public function getBuyerSummeryByDate($buyer, $date)
    {
        $month = Carbon::parse($date)->format('m');
        $fiscalYear = $this->fiscalYearService->getFiscalYearByDate($date);
        $buyerSummery = $this->model()::where('fiscal_year', $fiscalYear->fiscal_year)
            ->when($buyer, function ($query) use ($buyer) {
                $query->where('buyer_id', $buyer->id);
            })
            ->when($month, function ($query) use ($month){
                return $query->where('month', $month);
            })
            ->first();
        return $buyerSummery;
    }

    public function increaseTotalTransaction(Buyer $buyer, $sale)
    {
        $buyerSummary = $this->firstOrCreateBuyerSummary($buyer, $sale->sale_date);
        $buyerSummary->total_transaction += $sale->grand_total;
        $buyerSummary->save();
    }

    public function decreaseTotalTransaction(Buyer $buyer, $sale)
    {
        $buyerSummary = $this->firstOrCreateBuyerSummary($buyer, $sale->sale_date);
        $buyerSummary->total_transaction -= $sale->grand_total;
        $buyerSummary->save();
    }
}
