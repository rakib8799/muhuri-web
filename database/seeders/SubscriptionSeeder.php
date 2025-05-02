<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionPlan;
use Carbon\Carbon;

class SubscriptionSeeder extends Seeder
{
    public function run()
    {
        $subscriptionPlan = SubscriptionPlan::first();

        if ($subscriptionPlan) {
            Subscription::create([
                'subscription_plan_id' => $subscriptionPlan->id,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonth(),
                'price' => $subscriptionPlan->price,
                'discount_amount' => 0,
                'final_price' => $subscriptionPlan->price,
                'is_trial_taken' => true,
                'trial_start_date' => Carbon::now(),
                'trial_end_date' => Carbon::now()->addDays(7),
                'note' => 'First user subscription.',
                'is_active' => true,
                'deleted_by' => null,
            ]);
        }
    }
}
