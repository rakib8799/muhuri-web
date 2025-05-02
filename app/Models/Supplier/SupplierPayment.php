<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class SupplierPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'fiscal_year_id',
        'fiscal_year',
        'supplier_id',
        'amount',
        'payment_date',
        'payment_method',
        'invoice_number',
        'note',
        'is_editable',
        'created_by',
        'updated_by',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getPayee()
    {
        return $this->supplier()->first();
    }

    protected static function booted(): void
    {
        static::saved(function (SupplierPayment $supplierPayment) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-supplier-payment")->flush();
        });

        static::deleted(function (SupplierPayment $supplierPayment) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-supplier-payment")->flush();
        });
    }
}
