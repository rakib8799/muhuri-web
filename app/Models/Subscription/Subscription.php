<?php

namespace App\Models\Subscription;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'subscription_plan_id',
        'start_date',
        'end_date',
        'price',
        'discount_amount',
        'final_price',
        'is_trial_taken',
        'trial_start_date',
        'trial_end_date',
        'note',
        'is_active',
        'deleted_by'
    ];

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function subscriptionHistories()
    {
        return $this->hasMany(SubscriptionHistory::class, 'subscription_id');
    }

    /**
     * Add Activity to activityLogs table
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    /**
     * Changed the activity description of events
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        $subscriptionPlanName = $this->subscriptionPlan->name;
        $subscriptionPlanType = $this->subscriptionPlan->plan_type;
        if ($eventName === 'updated') {
            return "Updated the subscription to - $subscriptionPlanName ($subscriptionPlanType)";
        }

        // Default description if event is not handled
        return "Subscription {$eventName}.";
    }
}
