<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Transaction extends Model
{
    protected $fillable = [
        'amount',
    ];
    /**
     * Get the user that owns the Wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wallet that transaction comes from
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'from_wallet');
    }
    /**
     * Get the wallet that transaction goes to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'to_wallet');
    }
    public function balanceDetail(): MorphOne
    {
        return $this->morphOne(BalanceDetail::class, 'operation');
    }
}
