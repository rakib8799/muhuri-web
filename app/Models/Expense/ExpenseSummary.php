<?php

namespace App\Models\Expense;

use App\Models\FiscalYear;
use App\Models\Item\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_date',
        'item_id',
        'total_amount',
        'month',
        'fiscal_year_id',
        'fiscal_year',
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
