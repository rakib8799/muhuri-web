<?php

namespace App\Models\Sale;

use App\Models\FiscalYear;
use App\Models\Buyer\Buyer;
use App\Models\Sale\SaleItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'buyer_id',
        'invoice_number',
        'sale_date',
        'sub_total',
        'discount',
        'grand_total',
        'paid_amount',
        'fiscal_year',
        'fiscal_year_id',
        'status',
        'note',
        'sale_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
