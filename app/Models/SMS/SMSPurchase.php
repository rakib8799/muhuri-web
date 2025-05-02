<?php

namespace App\Models\SMS;

use App\Models\Payment\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSPurchase extends Model
{
    use HasFactory;

    protected $table = 'sms_purchases';
    protected $fillable = [
        'sms_quantity',
        'sms_rate',
        'total_price',
        'payment_status', // ['pending', 'paid', 'failed']
        'transaction_id',
        'note',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
