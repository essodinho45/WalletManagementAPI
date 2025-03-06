<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransaction;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transfer(StoreTransaction $request)
    {
        $transaction_id = Transaction::makeTransaction($request->validated());
        return new TransactionResource(Transaction::with('user', 'fromWallet', 'toWallet')->findOrFail($transaction_id));
    }
}
