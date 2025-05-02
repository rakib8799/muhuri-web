<?php

namespace App\Services\Subscription;

use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionHistory;
use App\Services\Core\BaseModelService;

class SubscriptionHistoryService extends BaseModelService
{
    public function model(): string
    {
        return SubscriptionHistory::class;
    }

    public function getSubscriptionHistories()
    {
        return $this->model()::with('subscriptionPlan')->orderBy('id', 'desc')->get();
    }

    public function createSubscriptionHistory($subscription)
    {
        $data = [
            'subscription_id' => $subscription->id,
            'subscription_plan_id' => $subscription->subscription_plan_id,
            'start_date' => $subscription->start_date,
            'end_date' => $subscription->end_date,
            'price' => $subscription->price,
            'discount_amount' => $subscription->discount_amount,
            'final_price' => $subscription->final_price,
            'is_trial_taken' => $subscription->is_trial_taken,
            'trial_start_date' => $subscription->trial_start_date,
            'trial_end_date' => $subscription->trial_end_date,
            'note' => $subscription->note,
        ];
        $this->model()::create($data);
    }
}
