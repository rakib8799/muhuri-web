<?php

namespace App\Http\Controllers\Payment;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Services\ConfigurationService;
use App\Services\Payment\Bkash\BkashPaymentService;
use App\Services\Payment\InvoiceService;
use App\Services\Payment\TransactionService;
use App\Services\SMS\SMSPurchaseService;
use App\Services\Subscription\SubscriptionPlanService;
use App\Services\Subscription\SubscriptionService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BkashPaymentController extends Controller
{
    private BkashPaymentService $bkashPaymentService;
    private TransactionService $transactionService;
    private InvoiceService $invoiceService;
    private SubscriptionService $subscriptionService;
    private SubscriptionPlanService $subscriptionPlanService;
    private ConfigurationService $configurationService;
    private SMSPurchaseService $smsPurchaseService;

    public function __construct(
        BkashPaymentService     $bkashPaymentService,
        TransactionService      $transactionService,
        InvoiceService          $invoiceService,
        SubscriptionService     $subscriptionService,
        SubscriptionPlanService $subscriptionPlanService,
        ConfigurationService    $configurationService,
        SMSPurchaseService      $smsPurchaseService
    )
    {
        $this->bkashPaymentService = $bkashPaymentService;
        $this->transactionService = $transactionService;
        $this->invoiceService = $invoiceService;
        $this->subscriptionService = $subscriptionService;
        $this->subscriptionPlanService = $subscriptionPlanService;
        $this->configurationService = $configurationService;
        $this->smsPurchaseService = $smsPurchaseService;
    }

    public function createSubscriptionPayment(Request $request)
    {
        $user = auth()->user();
        $subscriptionId = $request->subscription_id;
        $subscriptionPlan = $this->subscriptionPlanService->findOrFail($request->subscription_plan_id);

        // Create invoice
        $invoiceData = [
            'user_id' => $user->id,
            'amount' => $subscriptionPlan->price,
            'details' => 'Subscription payment for ' . $subscriptionPlan->name,
            'invoice_type' => Constants::SUBSCRIPTION,
            'subscription_plan_id' => $subscriptionPlan->id,
        ];
        $invoice = $this->invoiceService->createInvoice($invoiceData);

        // Prepare payment data
        $paymentData = [
            'userId' => $user->id,
            'amount' => $invoice->amount,
            'currency' => 'BDT',
            'callback_url' => route('payment.bkash.callback.subscription', ['subscription_id' => $subscriptionId]),
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'invoice' => $invoice,
        ];

        return $this->bkashPaymentService->createPayment($paymentData);
    }

    public function subscriptionCallback(Request $request)
    {
        $paymentID = $request->query('paymentID');
        $status = $request->query('status');


        $subscriptionId = $request->query('subscription_id');
        $subscription = $this->subscriptionService->findOrFail($subscriptionId);

        $breadcrumbs = Breadcrumbs::generate('paymentStatus');
        $responseData = [
            'paymentID' => $paymentID,
            'status' => $status,
            'message' => 'Something went wrong.',
            'redirectRoute' => 'subscriptions.show',
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => 'Payment Status',
            'buttonText' => 'Return to Subscription'
        ];

        if (!$paymentID) {
            $responseData['message'] = 'Invalid payment ID.';
            return Inertia::render('Payments/PaymentStatus', $responseData);
        }

        $transaction = $this->transactionService->getTransactionByPaymentIdAndStatus($paymentID);
        $user = auth()->user();

        if (!$transaction) {
            $responseData['message'] = 'Transaction not found.';
            return Inertia::render('Payments/PaymentStatus', $responseData);
        }

        if ($transaction->user_id != $user->id) {
            $responseData['message'] = 'Unauthorized access.';
            return Inertia::render('Payments/PaymentStatus', $responseData);
        }

        if ($status === Constants::SUCCESS) {
            $response = $this->bkashPaymentService->executePayment($paymentID, $transaction);

            if ($response[Constants::SUCCESS]) {
                $this->subscriptionService->updateSubscriptionFromTransaction($transaction, $subscription);
                $responseData['message'] = 'Thank you for your payment. Your subscription is now active.';
                return Inertia::render('Payments/PaymentStatus', $responseData);
            }
        }

        $transaction->update([
            'status' => Constants::FAILED,
            'is_active' => false
        ]);
        $transaction->invoice()->update([
            'payment_status' => Constants::FAILED
        ]);

        $responseData['message'] = 'Payment failed. Please try again.';
        return Inertia::render('Payments/PaymentStatus', $responseData);
    }

    public function createSmsPayment(Request $request)
    {
        // validate request for
        $request->validate([
            'sms_purchase_id' => 'required|numeric',
            'sms_quantity' => 'required|numeric',
        ]);

        $user = auth()->user();
        $smsRate = $this->configurationService->getSMSRate();
        $smsPurchaseId = $request->sms_purchase_id;
        $smsQuantity = $request->sms_quantity;

        // Create invoice
        $invoiceData = [
            'user_id' => $user->id,
            'amount' => $smsQuantity * $smsRate,
            'details' => 'Payment for SMS purchase. Quantity: ' . $smsQuantity,
            'invoice_type' => Constants::SUBSCRIPTION,
            'sms_purchase_id' => $smsPurchaseId,
        ];
        $invoice = $this->invoiceService->createInvoice($invoiceData);

        // Prepare payment data
        $paymentData = [
            'userId' => $user->id,
            'amount' => $invoice->amount,
            'currency' => 'BDT',
            'callback_url' => route('payment.bkash.callback.sms', ['sms_purchase_id' => $smsPurchaseId]),
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'invoice' => $invoice,
        ];

        return $this->bkashPaymentService->createPayment($paymentData);

    }

    public function smsCallback(Request $request)
    {
        $paymentID = $request->query('paymentID');
        $status = $request->query('status');


        $smsPurchaseId = $request->query('sms_purchase_id');
        $smsPurchase = $this->smsPurchaseService->findOrFail($smsPurchaseId);

        $breadcrumbs = Breadcrumbs::generate('paymentStatus');
        $responseData = [
            'paymentID' => $paymentID,
            'status' => $status,
            'message' => 'Something went wrong.',
            'redirectRoute' => 'sms.purchaseReport',
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => 'Payment Status',
            'buttonText' => 'Go to SMS Purchase History'
        ];

        if (!$paymentID) {
            $responseData['message'] = 'Invalid payment ID.';
            return Inertia::render('Payments/PaymentStatus', $responseData);
        }

        $transaction = $this->transactionService->getTransactionByPaymentIdAndStatus($paymentID);
        $user = auth()->user();

        if (!$transaction) {
            $responseData['message'] = 'Transaction not found.';
            return Inertia::render('Payments/PaymentStatus', $responseData);
        }

        if ($transaction->user_id != $user->id) {
            $responseData['message'] = 'Unauthorized access.';
            return Inertia::render('Payments/PaymentStatus', $responseData);
        }

        if ($status === Constants::SUCCESS) {
            $response = $this->bkashPaymentService->executePayment($paymentID, $transaction);

            if ($response[Constants::SUCCESS]) {
                $this->smsPurchaseService->updateSMSPurchaseFromTransaction($transaction, $smsPurchase);
                $responseData['message'] = 'Thank you for your payment. SMS purchase is successful.';
                return Inertia::render('Payments/PaymentStatus', $responseData);
            }
        }

        $transaction->update([
            'status' => Constants::FAILED,
            'is_active' => false
        ]);
        $transaction->invoice()->update([
            'payment_status' => Constants::FAILED
        ]);

        $responseData['message'] = 'Payment failed. Please try again.';
        return Inertia::render('Payments/PaymentStatus', $responseData);
    }

    public function expirePendingInvoices()
    {
        $expiredInvoices = $this->invoiceService->expirePendingInvoices();
        return $expiredInvoices;
    }
}
