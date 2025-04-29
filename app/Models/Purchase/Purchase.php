<?php

namespace App\Models\Purchase;

use App\Models\Item\Item;
use App\Models\Supplier\Supplier;
use App\Models\Supplier\SupplierPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'brand_id',
        'brand_name',
        'item_id',
        'item_name',
        'quantity',
        'total_box',
        'total_poly',
        'mir',
        'unit_price',
        'total_price',
        'unit_name',
        'unit_value',
        'unit_display_name',
        'item_unit_id',
        'fiscal_year_id',
        'fiscal_year',
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

    public function supplierPayment(): BelongsTo
    {
        return $this->belongsTo(SupplierPayment::class);
    }
}
