<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use DB;

class Transaction extends Model
{
    protected $fillable = [
        'amount',
        'from_wallet',
        'to_wallet',
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
    public static function makeTransaction($attributes)
    {
        $id = DB::transaction(function () use ($attributes) {
            $from_wallet = Wallet::findByCode($attributes['from_wallet']);
            $from_wallet->checkBalance($attributes['amount']);
            $to_wallet = Wallet::findByCode($attributes['to_wallet']);
            $attributes['from_wallet'] = $from_wallet->id;
            $attributes['to_wallet'] = $to_wallet->id;
            $transaction = self::create($attributes);
            $from_balance_detail = $from_wallet->details()->create(['amount' => -1 * $transaction->amount]);
            $to_balance_detail = $to_wallet->details()->create(['amount' => $transaction->amount]);
            $transaction->balanceDetail()->save($from_balance_detail);
            $transaction->balanceDetail()->save($to_balance_detail);
            $from_wallet->recalculateBalance();
            $to_wallet->recalculateBalance();
            return $transaction->id;
        });
        return $id;
    }
    public static function listUserTransactions($user_id)
    {
        $user = User::findOrFail($user_id);
        $wallets = $user->wallets->pluck('id');
        $transactions = Transaction::with(['user', 'fromWallet', 'toWallet'])
            ->whereIn('from_wallet', $wallets)
            ->orWhereIn('to_wallet', $wallets)
            ->orderByDesc('created_at')
            ->paginate(5);
        return $transactions;
    }
    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction) {
            $transaction->user_id = $transaction->fromWallet->user->id;
        });
    }
}
