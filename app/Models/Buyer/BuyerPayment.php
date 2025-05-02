<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BuyerPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'buyer_id',
        'amount',
        'payment_date',
        'payment_method',
        'invoice_number',
        'fiscal_year_id',
        'fiscal_year',
        'note',
        'is_editable',
        'created_by',
        'updated_by',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function buyerPayments(): HasMany
    {
        return $this->hasMany(BuyerPayment::class);
    }

    protected static function booted(): void
    {
        static::saved(function (BuyerPayment $buyerPayment) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-buyer-payment")->flush();
        });

        static::deleted(function (BuyerPayment $buyerPayment) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-buyer-payment")->flush();
        });
    }
}
