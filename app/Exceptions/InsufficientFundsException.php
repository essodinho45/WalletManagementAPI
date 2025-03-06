<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Response;

class InsufficientFundsException extends Exception
{
    public function render()
    {
        return Response::json([
            'message' => 'Wallet Does Not Have Sufficient Funds.'
        ], 400);
    }
}
