<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliverySlot extends Model
{
    protected $fillable = [
        'region_id', 'date', 'time_from', 'time_to', 'capacity', 'booked_count', 'is_active',
    ];

    protected $casts = [
        'date'        => 'date',
        'is_active'   => 'boolean',
        'booked_count' => 'integer',
        'capacity'    => 'integer',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(DeliveryDetail::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->booked_count < $this->capacity;
    }

    public function getRemainingCapacityAttribute(): int
    {
        return max(0, $this->capacity - $this->booked_count);
    }
}
