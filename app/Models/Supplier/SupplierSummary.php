<?php

namespace App\Models\Supplier;

use App\Models\FiscalYear;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class SupplierSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_id',
        'total_transaction',
        'total_payment',
        'month',
        'fiscal_year',
        'fiscal_year_id',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }
    protected static function booted(): void
    {

        static::saved(function (SupplierSummary $supplierSummary) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-supplier-summary-{$supplierSummary->supplier_id}")->flush();
        });

        static::deleted(function (SupplierSummary $supplierSummary) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-supplier-summary-{$supplierSummary->supplier_id}")->flush();
        });
    }
}
