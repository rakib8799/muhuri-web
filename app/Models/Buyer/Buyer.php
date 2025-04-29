<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends Model
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
        'is_active',
        'note',
        'created_by',
        'updated_by',
    ];

    public function buyerPayments(): HasMany
    {
        return $this->hasMany(BuyerPayment::class);
    }

    protected static function booted(): void
    {
        static::saved(function (Buyer $buyer) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-buyers")->flush();
        });

        static::deleted(function (Buyer $buyer) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-buyers")->flush();
        });
    }
}
