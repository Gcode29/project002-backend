<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Delivery;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use App\Http\Resources\SaleResource;
use Illuminate\Database\Eloquent\Builder;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::with('transactions')->paginate();

        return SaleResource::collection($sales);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SaleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleRequest $request)
    {
        $sale = Sale::create($request->validated());

        $items = collect($request->items)
            ->map(function ($item) {
                $product = Transaction::whereHasMorph(
                    'transactable', 
                    Delivery::class, 
                    function (Builder $query) use ($item) {
                        $query->where('product_id', $item['product_id']);
                    }
                )
                ->orderBy('created_at', 'desc')
                ->first();

                return new Transaction([
                    'product_id' => $item['product_id'],
                    'quantity' => -abs($item['quantity']),
                    'price' => $item['price'],
                    'unit_cost' => $product->price
                ]);
            })
            ->all();

        $sale->transactions()->saveMany($items);

        return new SaleResource($sale->load('transactions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        return new SaleResource($sale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SaleRequest  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function update(SaleRequest $request, Sale $sale)
    {
        $sale->update($request->validated());

        $sale->transactions()->delete();

        $items = collect($request->items)
            ->map(fn ($item) =>
                new Transaction([
                    'product_id' => $item['product_id'],
                    'quantity' => -abs($item['quantity']),
                    'price' => $item['price'],
                ])
            )
            ->all();

        $sale->transactions()->saveMany($items);

        return new SaleResource($sale->load('transactions'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return response()->noContent();
    }
}
