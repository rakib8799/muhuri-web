<?php

namespace App\Services\SMS;

use App\Models\SMS\SMSPurchase;
use App\Services\Core\BaseModelService;
use Illuminate\Support\Facades\DB;

class SMSPurchaseService extends BaseModelService
{
    public function model(): string
    {
        return SMSPurchase::class;
    }
    /**
     * Create a new SMS purchase entry.
     */
    public function createSMSPurchase(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = auth()->id();
            $data['updated_by'] = auth()->id();
            return $this->create($data);
        });
    }

    /**
     * Update SMS purchase payment status.
     */
    public function updatePaymentStatus($purchaseId, $status)
    {
        $purchase = SMSPurchase::findOrFail($purchaseId);
        $purchase->update([
            'payment_status' => $status,
            'updated_by' => auth()->id(),
        ]);

        return $purchase;
    }

    // updateSMSPurchaseFromTransaction
    public function updateSMSPurchaseFromTransaction($transaction, $smsPurchase)
    {
        $smsPurchase = $transaction->invoice->smsPurchase;
        // DB transaction return result
        $result = DB::transaction(function () use ($transaction, $smsPurchase) {
            $smsPurchase->update([
                'payment_status' => 'paid',
                'transaction_id' => $transaction->id,
                'updated_by' => auth()->id(),
            ]);

            $smsCreditService = new SMSCreditService();
            $smsCredit = $smsCreditService->getOrCreateSMSCredit();
            $smsCredit->update([
                'total_credits' => $smsCredit->total_credits + $smsPurchase->sms_quantity,
                'remaining_credits' => $smsCredit->remaining_credits + $smsPurchase->sms_quantity,
                'updated_by' => auth()->id(),
            ]);

            return true;
        });

        return $result;
    }

    public function getSMSPurchases($status = null)
    {
        $query = SMSPurchase::query();
        $query->with(['transaction', 'createdBy']);
        if ($status) {
            $query->where('payment_status', $status);
        }
        return $query->get();
    }

}
