<?php

namespace App\Models\Payment;

use App\Models\Payment\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'slug',
        'base_url',
        'app_key',
        'app_secret',
        'username',
        'password',
        'central_id',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'payment_method_id');
    }
}
