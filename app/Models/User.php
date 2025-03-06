<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Get all of the wallets for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
    public static function makeUser($attributes)
    {
        $id = DB::transaction(function () use ($attributes) {
            $user = self::create($attributes);
            $wallet = $user->wallets()->create();
            $initial = $wallet->initialBalance()->create(['amount' => $attributes['initial_balance']]);
            $balance_detail = $wallet->details()->create(['amount' => $initial->amount]);
            $initial->balanceDetail()->save($balance_detail);
            $wallet->recalculateBalance();
            return $user->id;
        });
        return $id;
    }
}
