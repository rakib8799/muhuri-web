<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Summary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'summary_date',
        'total_purchase',
        'total_sale',
        'total_expense',
        'total_buyer_payment',
        'total_supplier_payment',
        'fiscal_year_id',
        'fiscal_year',
    ];
}
