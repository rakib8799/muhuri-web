<?php

namespace App\Services\Payment;

use App\Models\Payment\PaymentMethod;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;

class PaymentMethodService extends BaseModelService
{
    public function model(): string
    {
        return PaymentMethod::class;
    }

    public function getPaymentMethodBySlug($slug)
    {
        $paymentMethod = $this->model()::where(['slug' => $slug, 'is_active' => true])->first();
        return $paymentMethod;
    }

    public function getActivePaymentMethods($isActive = true)
    {
        $paymentMethods = $this->model()::where('is_active', $isActive)->get();
        return $paymentMethods;
    }

    public function syncData($request)
    {
        try{
            $paymentMethods = $request->all();
            if(isset($paymentMethods['id'])){
                $paymentMethods = [$paymentMethods];
            }

            foreach($paymentMethods as $paymentMethod){
                $paymentMethod['central_id'] = $paymentMethod['id'];
                $this->model()::updateOrCreate([
                    'central_id' => $paymentMethod['id']
                ], $paymentMethod);
            }
        }catch(\Exception $e){
            HelperService::captureException($e);
        }
    }

    public function syncPaymentMethodDelete($data)
    {
        try{
            $paymentMethod = $this->model()::where('central_id', $data['id'])->first();
            $paymentMethod->delete();
        }catch(\Exception $e){
            HelperService::captureException($e);
        }
    }
}
