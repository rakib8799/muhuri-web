<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleSummary extends Model
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
        'sale_date',
        'month',
    ];
}
