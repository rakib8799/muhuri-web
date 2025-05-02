<?php

namespace App\Models\Purchase;

use App\Models\FiscalYear;
use App\Models\Item\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_unit_id',
        'brand_id',
        'unit_display_name',
        'fiscal_year_id',
        'total_box',
        'total_poly',
        'total_quantity',
        'total_amount',
        'purchase_date',
        'month',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }
}
