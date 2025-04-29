<?php

namespace App\Models\Buyer;

use App\Models\FiscalYear;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BuyerSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'total_transaction',
        'total_payment',
        'month',
        'fiscal_year',
        'fiscal_year_id',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    protected static function booted(): void
    {

        static::saved(function (BuyerSummary $buyerSummary) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-buyer-summary-{$buyerSummary->buyer_id}")->flush();
        });

        static::deleted(function (BuyerSummary $buyerSummary) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-buyer-summary-{$buyerSummary->buyer_id}")->flush();
        });
    }
}
