<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::get('/users/{id}', 'show');
    Route::post('/users', 'create');
});
Route::controller(WalletController::class)->group(function () {
    Route::post('/wallet/deposit', 'deposit');
    Route::post('/wallet/withdraw', 'withdraw');
});
