<?php

namespace App\Services\Subscription;

use App\Models\Subscription\SubscriptionPlanFeature;
use App\Services\Core\BaseModelService;

class SubscriptionPlanFeatureService extends BaseModelService
{
    public function model(): string
    {
        return SubscriptionPlanFeature::class;
    }

    public function getSubscriptionPlanFeatures()
    {
        return $this->model()::all();
    }

    public function getSubscriptionPlanFeatureIds($centralFeatureIds)
    {
        return $this->model()::whereIn('central_id', $centralFeatureIds)->pluck('id')->toArray();
    }

    public function syncData($request)
    {
        $subscriptionPlanFeatures = $request->all();
        // Check if the data is a single object (associative array)
        if (isset($subscriptionPlanFeatures['id'])) {
            $subscriptionPlanFeatures = [$subscriptionPlanFeatures];
        }
        
        foreach ($subscriptionPlanFeatures as $subscriptionPlanFeature) {
            $subscriptionPlanFeature['central_id'] = $subscriptionPlanFeature['id'];
            $newSubscriptionPlanFeature = $this->model()::updateOrCreate(["central_id" => $subscriptionPlanFeature["central_id"]], $subscriptionPlanFeature);

            if ($newSubscriptionPlanFeature->is_active == false) {
                $newSubscriptionPlanFeature->subscriptionPlans()->detach();
            }
        }
    }
}
