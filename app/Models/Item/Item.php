<?php

namespace App\Models\Item;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Item extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'type',
        'parent_id',
        'item_type',
        'description',
        'display_order',
        'note',
        'central_id',
        'created_by',
        'updated_by',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Item::class, 'parent_id', 'id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isParent(): bool
    {
        return $this->parent_id === null;
    }

    public function isChild(): bool
    {
        return $this->parent_id !== null && $this->parent_id !== "";
    }

    protected static function booted(): void
    {
        static::saved(function (Item $item) {
            try {
                $workspaceName = app('workspaceName');
                Cache::tags("{$workspaceName}-item")->flush();
            } catch (\Exception $e) {
                // do nothing
            }
        });


        static::deleted(function (Item $item) {
            $workspaceName = app('workspaceName');
            Cache::tags("{$workspaceName}-item")->flush();
        });
    }
}
