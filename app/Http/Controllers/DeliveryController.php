<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Transaction;
use Spatie\QueryBuilder\QueryBuilder;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = QueryBuilder::for(Delivery::class)
            ->allowedIncludes(['transactions', 'transactions.product.uom', 'supplier', 'receiver'])
            ->paginate(request()->per_page);

        return DeliveryResource::collection($deliveries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\DeliveryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryRequest $request)
    {
        $delivery = Delivery::create($request->validated());

        $items = collect($request->items)
            ->map(fn ($item) =>
                new Transaction([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ])
            )
            ->all();

        $delivery->transactions()->saveMany($items);

        return new DeliveryResource($delivery->load('supplier', 'transactions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        return new DeliveryResource($delivery->load('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DeliveryRequest  $request
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        $delivery->update($request->validated());

        $delivery->transactions()->delete();

        $items = collect($request->items)
            ->map(fn ($item) =>
                new Transaction([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ])
            )
            ->all();

        $delivery->transactions()->saveMany($items);

        return new DeliveryResource($delivery->load('supplier', 'transactions'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        return response()->noContent();
    }
}
