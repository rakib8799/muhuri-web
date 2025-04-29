<?php

namespace App\Models\Expense;

use App\Models\Item\Item;
use App\Models\Supplier\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expense_date',
        'invoice_number',
        'item_id',
        'item_name',
        'amount',
        'fiscal_year_id',
        'fiscal_year',
        'is_editable',
        'expenseable_type',
        'expenseable_id',
        'note',
        'created_by',
        'updated_by',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    protected static function booted(): void
    {
        static::saved(function (Expense $expense) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-expense")->flush();
        });

        static::deleted(function (Expense $expense) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-expense")->flush();
        });
    }

}
