<?php

namespace App\Http\Controllers\API\Subscription;

use App\Http\Controllers\Controller;
use App\Services\Subscription\SubscriptionHistoryService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;
    protected SubscriptionHistoryService $subscriptionHistoryService;

    public function __construct(SubscriptionService $subscriptionService, SubscriptionHistoryService $subscriptionHistoryService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->subscriptionHistoryService = $subscriptionHistoryService;
    }

    /**
     * Sync selected subscription from central-admin at the time of company creation
     * Now create subscription history
     */
    public function syncSubscription(Request $request)
    {
        $subscription = $this->subscriptionService->syncData($request);
        $this->subscriptionHistoryService->createSubscriptionHistory($subscription);
        return response()->json(['message' => 'Subscription synced successfully']);
    }
}
