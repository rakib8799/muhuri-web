<?php

namespace App\Services\Payment\Bkash;

use App\Constants\Constants;
use App\Models\Payment\Transaction;
use App\Services\Payment\PaymentMethodService;
use App\Services\Payment\TransactionService;
use Inertia\Inertia;

class BkashPaymentService
{
    private BkashService $bkashService;
    private PaymentMethodService $paymentMethodService;
    protected $paymentMethod;
    private TransactionService $transactionService;

    public function __construct(
        BkashService         $bkashService,
        PaymentMethodService $paymentMethodService,
        TransactionService   $transactionService
    )
    {
        $this->bkashService = $bkashService;
        $this->paymentMethodService = $paymentMethodService;
        $this->paymentMethod = $this->paymentMethodService->getPaymentMethodBySlug('bkash');
        $this->transactionService = $transactionService;
    }

    public function createPayment(array $paymentData)
    {
        try {
            $invoice = $paymentData['invoice'] ?? null;

            $bkashRequestUrl = "{$this->paymentMethod->base_url}/create";
            $bkashRequestData = [
                "mode" => "0011",
                "payerReference" => uniqid(),
                "callbackURL" => $paymentData["callback_url"],
                "amount" => $paymentData["amount"],
                "currency" => $paymentData["currency"],
                "intent" => "sale",
                "merchantInvoiceNumber" => $paymentData["invoice_number"],
            ];

            $transaction = $this->transactionService->createTransaction(
                $this->paymentMethod->id,
                $invoice->amount,
                $invoice,
                $bkashRequestUrl,
                $bkashRequestData
            );

            $response = $this->bkashService->createPayment($bkashRequestUrl, $bkashRequestData);
            $responseData = $response['data'];

            $transaction->update([
                'create_response' => json_encode($responseData)
            ]);

            if ($response['status'] === 200 && isset($responseData['paymentID'], $responseData['bkashURL'])) {
                $transactionResponseData = [
                    'payment_id' => $responseData['paymentID']
                ];
                $transaction->update($transactionResponseData);

                return Inertia::location($responseData['bkashURL']);
            }

            throw new \Exception("Payment creation failed.");
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage() ?? 'An error occurred while creating the payment. Please try again later.'
            ];
        }
    }

    public function executePayment(string $paymentID, Transaction $transaction)
    {
        try {
            if ($transaction->status === 'paid') {
                return [
                    'success' => false,
                    'message' => 'Transaction already completed'
                ];
            }

            $bkashRequestUrl = "{$this->paymentMethod->base_url}/execute";
            $bkashRequestData = [
                'paymentID' => $paymentID
            ];

            $transaction->update([
                'execute_request_url' => $bkashRequestUrl,
                'execute_request' => json_encode($bkashRequestData)
            ]);

            $response = $this->bkashService->executePayment($bkashRequestUrl, $bkashRequestData);
            $responseData = $response['data'];

            $transaction->update([
                'execute_response' => json_encode($responseData)
            ]);

            if ($response['status'] === 200 && isset($responseData['trxID'])) {
                $transaction->update([
                    'bkash_transaction_id' => $responseData['trxID'],
                    'status' => Constants::SUCCESS,
                    'is_active' => false
                ]);

                $transaction->invoice()->update([
                    'payment_status' => Constants::COMPLETED,
                    'paid_at' => now(),
                    'is_active' => false
                ]);

                return [
                    'success' => true,
                    'message' => 'Payment successful'
                ];
            }

            throw new \Exception('Payment execution failed.');
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage() ?? 'An error occurred while executing the payment. Please try again later.'
            ];
        }
    }
}
