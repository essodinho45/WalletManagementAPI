<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletOperation;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use App\Models\WalletOperation;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function deposit(StoreWalletOperation $request)
    {
        $attributes = $request->validated();
        $wallet = WalletOperation::makeOperation($attributes, 'deposit');
        return new WalletResource($wallet);
    }
    public function withdraw(StoreWalletOperation $request)
    {
        $attributes = $request->validated();
        $wallet = WalletOperation::makeOperation($attributes, 'withdrawal');
        return new WalletResource($wallet);
    }
}
