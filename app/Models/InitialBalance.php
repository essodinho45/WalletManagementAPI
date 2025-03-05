<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class InitialBalance extends Model
{
    protected $fillable = [
        'amount',
    ];

    /**
     * Get the wallet that owns the InitialBalance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
    public function balanceDetail(): MorphOne
    {
        return $this->morphOne(BalanceDetail::class, 'operation');
    }
}
