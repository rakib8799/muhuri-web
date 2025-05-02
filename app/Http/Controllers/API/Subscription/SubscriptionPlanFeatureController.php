<?php

namespace App\Http\Controllers\API\Subscription;

use App\Http\Controllers\Controller;
use App\Services\Subscription\SubscriptionPlanFeatureService;
use Illuminate\Http\Request;

class SubscriptionPlanFeatureController extends Controller
{
    protected SubscriptionPlanFeatureService $subscriptionPlanFeatureService;

    public function __construct(SubscriptionPlanFeatureService $subscriptionPlanFeatureService)
    {
        $this->subscriptionPlanFeatureService = $subscriptionPlanFeatureService;
    }

    public function syncSubscriptionPlanFeatures(Request $request)
    {
        $this->subscriptionPlanFeatureService->syncData($request);
        return response()->json(['message' => 'Subscription plan feature synced successfully']);
    }
}
