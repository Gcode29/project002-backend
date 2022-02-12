<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;
use App\Http\Resources\DeliveryResource;
use App\Models\Transaction;
use Spatie\QueryBuilder\QueryBuilder;
use App\Actions\CreateOrUpdateTransactionItems;
use Illuminate\Support\Facades\DB;

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
     * @param \App\Actions\CreateOrUpdateTransactionItems  $createOrUpdateTransactionItems
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryRequest $request, CreateOrUpdateTransactionItems $createOrUpdateTransactionItems)
    {
        $delivery = DB::transaction(function () use ($request, $createOrUpdateTransactionItems) {
            $delivery = Delivery::create($request->validated());

            $createOrUpdateTransactionItems->execute($delivery, $request->collect('items'));

            return $delivery;
        });

        return new DeliveryResource($delivery->load('supplier', 'transactions', 'receiver'));
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
     * @param \App\Actions\CreateOrUpdateTransactionItems  $createOrUpdateTransactionItems
     * @return \Illuminate\Http\Response
     */
    public function update(DeliveryRequest $request, Delivery $delivery, CreateOrUpdateTransactionItems $createOrUpdateTransactionItems)
    {
        $delivery = DB::transaction(function () use ($request, $delivery, $createOrUpdateTransactionItems) {
            $delivery->update($request->validated());

            $createOrUpdateTransactionItems->execute($delivery, $request->collect('items'));

            return $delivery;
        });

        return new DeliveryResource($delivery->load('supplier', 'transactions', 'receiver'));
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
