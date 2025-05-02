<?php

namespace App\Models\Sale;

use App\Models\Item\Item;
use App\Models\Sale\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'item_id',
        'item_name',
        'brand_id',
        'brand_name',
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
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
