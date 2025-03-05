<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BalanceDetail extends Model
{
    protected $fillable = [
        'amount',
    ];

    /**
     * Get the wallet that owns the BalanceDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function operation(): MorphTo
    {
        return $this->morphTo();
    }
}
