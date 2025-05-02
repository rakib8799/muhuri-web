<?php

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'duration',
        'plan_type',
        'duration_unit',
        'trial_duration',
        'trial_duration_unit',
        'discount_amount',
        'note',
        'max_active_users',
        'is_active',
        'central_id'
    ];

    public function subscriptionPlanFeatures()
    {
        return $this->belongsToMany(SubscriptionPlanFeature::class, 'subscription_plan_subscription_plan_feature');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}
