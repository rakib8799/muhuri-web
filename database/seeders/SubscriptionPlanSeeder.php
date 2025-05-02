<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        SubscriptionPlan::create([
            'name' => 'Basic Plan',
            'slug' => 'basic-plan',
            'description' => 'Basic subscription plan with limited features.',
            'price' => 1000,
            'duration' => 1,
            'plan_type' => 'monthly',
            'duration_unit' => 'month',
            'trial_duration' => 30,
            'trial_duration_unit' => 'day',
            'discount_amount' => 0,
            'note' => 'No additional notes.',
            'is_active' => true,
            'central_id' => null,
        ]);
    }
}
