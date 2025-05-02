<?php

namespace App\Services\Buyer;

use App\Models\Buyer\Buyer;
use Illuminate\Support\Facades\DB;
use App\Services\Core\HelperService;
use App\Services\Core\BaseModelService;

class BuyerService extends BaseModelService
{
    private BuyerSummaryService $buyerSummaryService;

    public function __construct(BuyerSummaryService $buyerSummaryService)
    {
        $this->buyerSummaryService = $buyerSummaryService;
    }
    public function model(): string
    {
        return Buyer::class;
    }

    public function getBuyers()
    {
        return $this->model()::orderBy('id','desc')->get();
    }

    public function getBuyerById($buyerId)
    {
        return $this->model()::where('id', $buyerId)->first();
    }

    public function getBuyerDetails(Buyer $buyer,  $month = null, $year = null): Buyer
    {
        $buyerSummary = $this->buyerSummaryService->getBuyerSummary($buyer, $month, $year);;
        $address = HelperService::getAddress($buyer);
        $buyer->address = $address;
        $buyer->total_transaction = $buyerSummary['total_transaction'] ?? 0;
        $buyer->total_payment = $buyerSummary['total_payment'] ?? 0;
        $buyer->total_due = $buyerSummary['total_due'] ?? 0;
        $buyer->total_due += $buyer->previous_due;

        return $buyer;
    }

    public function getBuyerDue()
    {
        $buyers = $this->getBuyers();
        $buyerDue = [];
        foreach ($buyers as $buyer) {
            $buyerSummary = $this->getBuyerDetails($buyer);
            if ($buyerSummary->total_due == 0) {
                continue;
            }
            $buyerDue[] = [
                'id' => $buyer->id,
                'name' => $buyer->name,
                'total_transaction' => $buyerSummary->total_transaction,
                'total_payment' => $buyerSummary->total_payment,
                'total_due' => $buyerSummary->total_due,
                'mobile_number' => $buyer->mobile_number ?? '',
            ];
        }
        return $buyerDue;
    }


    public function changeStatus(Buyer $buyer, $isActive)
    {
        $isActive = ($buyer->is_active) ? false : true;
        $buyer->is_active = $isActive;
        $buyer->save();
        return $buyer;
    }

    public function deleteBuyer(Buyer $buyer)
    {
        return DB::transaction(function () use ($buyer) {
            $buyer->delete();
            $buyer->mobile_number = $buyer->mobile_number . '-deleted' .'-' . $buyer->id;
            $buyer->deleted_by = auth()->user()->id;
            $buyer->save();
            return true;
        });
    }
}
