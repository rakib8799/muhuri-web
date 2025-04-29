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
        'purchase_date',
        'item_id',
        'total_box',
        'total_poly',
        'total_quantity',
        'total_amount',
        'unit_display_name',
        'item_unit_id',
        'month',
        'fiscal_year',
        'fiscal_year_id',
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
