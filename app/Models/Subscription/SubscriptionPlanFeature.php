<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_active',
        'central_id'
    ];

    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_subscription_plan_feature');
    }
}
