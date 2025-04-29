<?php

namespace App\Models\Payment;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'payment_transactions';
    protected $fillable = [
        'payment_method_id',
        'user_id',
        'invoice_id',
        'invoice_number',
        'amount',
        'currency_code',
        'payment_id',
        'bkash_transaction_id',
        'create_request_url',
        'create_request',
        'create_response',
        'execute_request_url',
        'execute_request',
        'execute_response',
        'status', // initiated, success, failed
        'is_active'
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
