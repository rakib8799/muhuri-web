<?php

namespace App\Models\Purchase;

use App\Models\Item\Item;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'invoice_number',
        'sub_total',
        'discount',
        'grand_total',
        'paid_amount',
        'fiscal_year',
        'fiscal_year_id',
        'status',
        'note',
        'purchase_type',
        'created_by',
        'updated_by'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function supplierPayment(): BelongsTo
    {
        return $this->belongsTo(SupplierPayment::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
