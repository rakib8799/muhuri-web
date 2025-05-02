<?php

namespace App\Models\Supplier;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'mobile_number',
        'previous_due',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'village',
        'note',
        'created_by',
        'updated_by',
        'is_active'
    ];

    public function supplierPayments(): HasMany
    {
        return $this->hasMany(SupplierPayment::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Supplier $supplier) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-suppliers")->flush();
        });

        static::deleted(function (Supplier $supplier) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-suppliers")->flush();
        });
    }

}
