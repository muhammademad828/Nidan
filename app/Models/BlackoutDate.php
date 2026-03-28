<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlackoutDate extends Model
{
    protected $fillable = ['date', 'reason', 'region_id'];

    protected $casts = ['date' => 'date'];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
