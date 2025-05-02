<?php

namespace App\Services\Subscription;

use App\Constants\Constants;
use App\Models\Payment\Transaction;
use App\Models\Subscription\Subscription;
use App\Services\CentralAdmin\CentralAdminService;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;
use App\Services\Payment\TransactionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class SubscriptionService extends BaseModelService
{
    protected SubscriptionHistoryService $subscriptionHistoryService;
    protected SubscriptionPlanService $subscriptionPlanService;
    protected TransactionService $transactionService;
    protected CentralAdminService $centralAdminService;

    public function __construct(SubscriptionHistoryService $subscriptionHistoryService, SubscriptionPlanService $subscriptionPlanService, TransactionService $transactionService, CentralAdminService $centralAdminService)
    {
        $this->subscriptionHistoryService = $subscriptionHistoryService;
        $this->subscriptionPlanService = $subscriptionPlanService;
        $this->transactionService = $transactionService;
        $this->centralAdminService = $centralAdminService;
    }

    public function model(): string
    {
        return Subscription::class;
    }

    public function getSubscription()
    {
        $result = DB::transaction(function () {
            $subscription = $this->model()::with('subscriptionPlan')->first();

            if ($subscription) {
                $this->updateSubscriptionStatus($subscription);

                $subscription->status = $this->getSubscriptionStatus($subscription);
            }

            return $subscription;
        });
        return $result;
    }

    private function getSubscriptionStatus(Subscription $subscription): string
    {
        $now = now();

        $trialStart = $subscription->trial_start_date ? Carbon::parse($subscription->trial_start_date) : null;
        $trialEnd = $subscription->trial_end_date ? Carbon::parse($subscription->trial_end_date) : null;
        $startDate = $subscription->start_date ? Carbon::parse($subscription->start_date) : null;
        $endDate = $subscription->end_date ? Carbon::parse($subscription->end_date) : null;

        if ($trialStart && $trialEnd && $now->between($trialStart, $trialEnd)) {
            return Constants::TRIAL;
        }

        if ($startDate && $endDate && $now->lte($endDate)) {
            return Constants::RUNNING;
        }

        return Constants::EXPIRED;
    }

    public function updateSubscriptionFromTransaction(Transaction $transaction, Subscription $subscription)
    {
        $subscriptionPlan = $transaction->invoice->subscriptionPlan;
        $validatedData = [
            'subscription_plan_id' => $subscriptionPlan['id']
        ];
        $updatedSubscription = $this->updateSubscription($subscription, $validatedData);
        if($updatedSubscription){
            try{
                $subscriptionData = $this->formatSubscriptionData($updatedSubscription);
                $this->centralAdminService->syncSubscription($subscriptionData);
            }catch (Exception $e) {
                HelperService::captureException($e);
            }
        }
    }

    public function updateSubscriptionStatus(Subscription $subscription)
    {
        $endDate = Carbon::parse($subscription->end_date);
        $currentDate = Carbon::now();

        if ($currentDate->greaterThan($endDate)) {
            $subscription->is_active = 0;
            $subscription->save();
        } else {
            $subscription->is_active = 1;
            $subscription->save();
        }
    }

    /**
     * Subscription plan change and renewal in one method
     */
    public function updateSubscription(Subscription $subscription, $validatedData)
    {
        $result = DB::transaction(function () use ($subscription, $validatedData) {
            $subscriptionPlan = $this->subscriptionPlanService->find($validatedData['subscription_plan_id']);

            // Check if this is a renewal or plan change
            $isRenewal = $subscription->subscription_plan_id === $validatedData['subscription_plan_id'];

            $startDate = $isRenewal ? $subscription->end_date : now();
            $endDate = $this->calculateEndDate($startDate, $subscriptionPlan);

            $validatedData = array_merge($validatedData, [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'trial_start_date' => null,
                'trial_end_date' => null,
                'price' => $subscriptionPlan->price,
                'discount_amount' => $subscriptionPlan->discount_amount,
                'final_price' => $subscriptionPlan->price - $subscriptionPlan->discount_amount,
                'note' => $isRenewal ? 'Subscription is renewed by user.' : 'Subscription plan changed by user.',
            ]);

            $isUpdated = $this->update($subscription, $validatedData);
            if ($isUpdated) {
                $this->subscriptionHistoryService->createSubscriptionHistory($subscription);
            }

            return $subscription;
        });

        return $result;
    }

    private function calculateEndDate($startDate, $plan)
    {
        $parsedStartDate = Carbon::parse($startDate);
        return $parsedStartDate->addMonths($plan->duration);
    }

    public function syncData($request)
    {
        $data = $request->all();
        $plan = $this->subscriptionPlanService->getSubscriptionPlanByCentralId($data);
        if ($plan) {
            $data['subscription_plan_id'] = $plan->id;
        }
        return $this->model()::create($data);
    }

    public static function formatSubscriptionData(Subscription $subscription)
    {
        $subscriptionArray = $subscription->toArray();
        $subscriptionArray['start_date'] = Carbon::parse($subscription->start_date)->toDateString();
        $subscriptionArray['end_date'] = Carbon::parse($subscription->end_date)->toDateString();
        $subscriptionArray['trial_start_date'] = Carbon::parse($subscription->trial_start_date)->toDateString();
        $subscriptionArray['trial_end_date'] = Carbon::parse($subscription->trial_end_date)->toDateString();

        // we have mapped the supscription_plan_id to central_id because here central_id = central-admin subscription_plans table id
        $subscriptionArray['subscription_plan_id'] = $subscription->subscriptionPlan->central_id;
        return $subscriptionArray;
    }
}
