<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Transaction;
use Illuminate\Http\Response;

class DeliveryTransactionController extends Controller
{
    public function __invoke(Delivery $delivery, Transaction $transaction): Response
    {
        $transaction->delete();

        return response()->noContent();
    }
}
