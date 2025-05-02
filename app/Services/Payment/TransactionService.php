<?php

namespace App\Services\Payment;

use App\Constants\Constants;
use App\Models\Payment\Invoice;
use App\Models\Payment\Transaction;
use App\Services\Core\BaseModelService;

class TransactionService extends BaseModelService
{
    public function model(): string
    {
        return Transaction::class;
    }

    public function getTransactionByPaymentIdAndStatus(string $paymentID, string $status = Constants::INITIATED)
    {
        return $this->model()::where(['payment_id' => $paymentID, 'status' => $status])->first();
    }

    public function createTransaction(string $paymentMethodId, float $amount, Invoice $invoice, string $bkashRequestUrl, array $bkashRequestData)
    {
        $transactionData = [
            'payment_method_id' => $paymentMethodId,
            'user_id' => $invoice->user_id,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'amount' => $amount,
            'create_request_url' => $bkashRequestUrl,
            'create_request' => json_encode($bkashRequestData)
        ];
        $transaction = $this->create($transactionData);
        return $transaction;
    }
}
