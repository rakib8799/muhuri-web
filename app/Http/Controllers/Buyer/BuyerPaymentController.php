<?php

namespace App\Http\Controllers\Buyer;

use App\Services\ConfigurationService;
use Inertia\Inertia;
use App\Models\Buyer\Buyer;
use App\Services\SMS\SMSService;
use App\Services\LocationService;
use App\Models\Buyer\BuyerPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Core\HelperService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Services\Buyer\BuyerPaymentService;
use App\Services\Buyer\BuyerSummaryService;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Requests\Buyer\CreateBuyerPaymentRequest;
use App\Http\Requests\Buyer\UpdateBuyerPaymentRequest;

class BuyerPaymentController extends Controller implements HasMiddleware
{
    protected BuyerPaymentService $buyerPaymentService;
    protected LocationService $locationService;
    protected BuyerSummaryService $buyerSummaryService;
    protected SMSService $smsService;
    protected ConfigurationService $configurationService;

    public function __construct(BuyerPaymentService $buyerPaymentService, LocationService $locationService, BuyerSummaryService $buyerSummaryService, SMSService $smsService, ConfigurationService $configurationService)
    {
        $this->buyerPaymentService = $buyerPaymentService;
        $this->locationService = $locationService;
        $this->buyerSummaryService = $buyerSummaryService;
        $this->smsService = $smsService;
        $this->configurationService = $configurationService;
    }

    public static function middleware(): array
    {
        return [
            new middleware('permission:can-view-buyer-payment', only: ['show']),
            new middleware('permission:can-create-buyer-payment', only: ['store']),
            new middleware('permission:can-edit-buyer-payment', only: ['update']),
            new middleware('permission:can-delete-buyer-payment', only: ['destroy'])
        ];
    }

    public function store(CreateBuyerPaymentRequest $request, Buyer $buyer)
    {
        $validatedData = $request->validated();
        $buyerPayment = $this->buyerPaymentService->createPayment($buyer, $validatedData);
        $status = $buyerPayment ? 'success' : 'error';
        $message = $buyerPayment ? __('message.custom.payment.store.success') : __('message.custom.payment.store.error');
        try{
            $this->smsService->sendBuyerPaymentConfirmation($buyerPayment);
            //Here call the getSmsCount for update the credit
        }catch(\Exception $e){
            Log::error('SMS Error: '. $e->getMessage());
        }

        return Inertia::location(route('buyers.payments.invoice', [
            'buyer' => $buyer->id,
            'payment' => $buyerPayment->id,
        ]));
    }

    public function update(UpdateBuyerPaymentRequest $request, Buyer $buyer, BuyerPayment $payment)
    {
        $validatedData = $request->validated();
        $buyerPaymentAccess = $this->buyerPaymentService->checkBuyerPaymentAccess($buyer, $payment);
        if($buyerPaymentAccess){
            $isUpdated = $this->buyerPaymentService->updatePayment($payment, $validatedData);
            $status = $isUpdated ? 'success' : 'error';
            $message = $isUpdated ? __('message.custom.payment.update.success') : __('message.custom.payment.update.error');
            return Inertia::location(route('buyers.payments.invoice', [
                'buyer' => $buyer->id,
                'payment' => $payment->id,
            ]));
        }

        return redirect()->route('buyers.show', $payment->buyer->id)->with('error','Buyer have no access to update payment');
    }

    public function show(Buyer $buyer, BuyerPayment $payment)
    {
        $breadcrumbs = Breadcrumbs::generate('buyerPaymentInvoice', $buyer, $payment);
        $address = HelperService::getAddress($buyer);
        $buyerSummary = $this->buyerSummaryService->getBuyerSummary($buyer);
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $responseData = [
            'buyer' => $buyer,
            'payment' => $payment,
            'address' => $address,
            'buyerSummary' => $buyerSummary,
            'configuration' => $configuration,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.buyer.paymentDetails')
        ];

        return Inertia::render('Buyer/Payment/Show', $responseData);
    }

    public function destroy(Buyer $buyer, BuyerPayment $payment)
    {
        $buyerPaymentAccess = $this->buyerPaymentService->checkBuyerPaymentAccess($buyer, $payment);
        if($buyerPaymentAccess){
            $isDeleted = $this->buyerPaymentService->deletePayment($payment);
            $status = $isDeleted ? 'success' : 'error';
            $message = $isDeleted ? __('message.custom.payment.destroy.success') : __('message.custom.payment.destroy.error');
            return redirect()->route('buyers.show',$payment->buyer->id)->with($status, $message);
        }
        return redirect()->route('buyers.show', $payment->buyer->id)->with('error','Buyer have no access to update payment');
    }
}
