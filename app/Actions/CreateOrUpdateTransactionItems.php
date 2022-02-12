<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class CreateOrUpdateTransactionItems
{
    public function execute(Model $model, Collection $items)
    {
        $items->each(function ($item) use ($model) {
            data_get($item, 'id') !== null
                ? $this->update($item)
                : $this->create($model, $item);
        });
    }

    private function create(Model $model, $item)
    {
        $model->transactions()->save(
            new Transaction([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ])
        );
    }

    private function update($item)
    {
        $transaction = Transaction::where('id', $item['id'])->first();

        $transaction->product_id = $item['product_id'];
        $transaction->quantity = $item['quantity'];
        $transaction->price = $item['price'];
        $transaction->save();
    }
}