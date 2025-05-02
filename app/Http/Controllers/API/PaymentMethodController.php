<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentMethodService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    protected PaymentMethodService $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function syncPaymentMethods(Request $request)
    {
        $this->paymentMethodService->syncData($request);
        return response()->json(['message' => 'Payment methods synced successfully']);
    }

    public function paymentMethodDelete(Request $request)
    {
        $paymentMethod = $request->json()->all();
        $this->paymentMethodService->syncPaymentMethodDelete($paymentMethod);
        return response()->json(['message' => 'Payment Method deleted successfully']);
    }
}
