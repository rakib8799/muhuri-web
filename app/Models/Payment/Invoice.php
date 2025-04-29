<?php

namespace App\Models\Payment;

use App\Models\SMS\SMSPurchase;
use App\Models\Subscription\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'payment_invoices';
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'sms_purchase_id',
        'invoice_type',
        'invoice_number',
        'amount',
        'currency_code',
        'invoice_date',
        'expire_date',
        'status', // pending, expired
        'payment_status', // pending, paid, failed
        'paid_at',
        'details',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function smsPurchase()
    {
        return $this->belongsTo(SMSPurchase::class, 'sms_purchase_id');
    }
}
