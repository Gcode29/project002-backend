<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Response;

class OrderTransactionController extends Controller
{
    public function __invoke(Sale $sale, Transaction $transaction): Response
    {
        $transaction->delete();

        return response()->noContent();
    }
}
