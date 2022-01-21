<?php

use App\Models\Delivery;
use App\Models\Product;
use App\Models\Transaction;

use function Pest\Laravel\{getJson, assertSoftDeleted};
use function Pest\Faker\faker;


beforeEach(function () {
    Delivery::factory()
        ->has(Transaction::factory()->count(3))
        ->count(10)
        ->create();
});

it('throws an error when the user is not authenticated', function () {
    getJson(route('deliveries.index'))
        ->assertUnauthorized();
});

it('shows the deliveries', function () {
    authenticated()
        ->getJson(route('deliveries.index'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'supplier',
                    'dr_number',
                    'received_by',
                    'received_at',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('can show a specific delivery', function () {
    authenticated()
        ->getJson(route('deliveries.show', Delivery::first()))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'supplier',
                'dr_number',
                'received_by',
                'received_at',
                'created_at',
                'updated_at',
            ],
        ]);
});

it('can create a delivery', function () {
    $payload = Delivery::factory()->make();

    $items = Product::inRandomOrder()->take(3)->get();

    $items = $items->map(function ($item) {
        return [
            'product_id' => $item->id,
            'quantity' => faker()->numberBetween(1, 10),
            'price' => faker()->numberBetween(1, 10),
        ];
    });

    $payload = array_merge($payload->toArray(), [
        'items' => $items->toArray(),
    ]);

    authenticated()
        ->postJson(route('deliveries.store'), $payload)
        ->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'supplier',
                'items',
                'dr_number',
                'received_by',
                'received_at',
                'created_at',
                'updated_at',
            ],
        ]);
});

it('can update a delivery', function () {
    $delivery = Delivery::first();

    $delivery->load('supplier', 'transactions');

    $payload = $delivery->toArray();

    $items = $delivery->transactions->map(function ($item) {
        return [
            'product_id' => $item->product_id,
            'quantity' => faker()->numberBetween(1, 10),
            'price' => faker()->numberBetween(1, 10),
        ];
    });

    $payload = array_merge($payload, [
        'items' => $items->toArray()
    ]);

    authenticated()
        ->putJson(route('deliveries.update', $delivery), $payload)
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'supplier',
                'items',
                'dr_number',
                'received_by',
                'received_at',
                'created_at',
                'updated_at',
            ],
        ]);

    collect($delivery->transactions)->each(function (Transaction $transaction) {
        assertSoftDeleted('transactions', [
            'id' => $transaction->id,
        ]);
    });
});

it('can delete a delivery', function () {
    $delivery = Delivery::first();

    authenticated()
        ->deleteJson(route('deliveries.destroy', $delivery))
        ->assertNoContent();

    assertSoftDeleted('deliveries', [
        'id' => $delivery->id,
    ]);
});
