<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use DB;

class WalletOperation extends Model
{
    protected $fillable = [
        'amount',
        'type',
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
     * Get the wallet that owns the BalanceDetail
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
    public static function makeOperation($attributes, $type)
    {
        $attributes['type'] = $type;
        $wallet = DB::transaction(function () use ($attributes, $type) {
            $wallet = Wallet::findByCode($attributes['wallet_code']);
            $factor = 1;
            if ($type == 'withdrawal') {
                $wallet->checkBalance($attributes['amount']);
                $factor = -1;
            }
            $operation = $wallet->operations()->create($attributes);
            $balance_detail = $wallet->details()->create(['amount' => $factor * $operation->amount]);
            $operation->balanceDetail()->save($balance_detail);
            $wallet->recalculateBalance();
            return $wallet;
        });
        return $wallet;
    }
    protected static function booted(): void
    {
        static::creating(function (WalletOperation $walletOperation) {
            $walletOperation->user_id = $walletOperation->wallet->user->id;
        });
    }
}
