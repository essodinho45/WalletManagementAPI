<?php

namespace App\Models;

use App\Exceptions\InsufficientFundsException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wallet extends Model
{
    protected $fillable = [
        'balance',
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
     * Get all of the details for the Wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(BalanceDetail::class);
    }
    /**
     * Get all of the operations for the Wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function operations(): HasMany
    {
        return $this->hasMany(WalletOperation::class);
    }

    /**
     * Get the initialBalance associated with the Wallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function initialBalance(): HasOne
    {
        return $this->hasOne(InitialBalance::class);
    }
    public function recalculateBalance()
    {
        $balance = $this->details()->sum('amount');
        $this->balance = $balance;
        $this->save();
    }
    public static function findByCode($code)
    {
        return self::where('code', $code)->firstOrFail();
    }
    public function checkBalance($amount)
    {
        if ($amount > $this->balance)
            throw new InsufficientFundsException();
    }
    protected static function booted(): void
    {
        static::creating(function (Wallet $wallet) {
            $wallet->code = str_pad($wallet->user_id, 5, '0', STR_PAD_LEFT) . '-' . time();
        });
    }
}
