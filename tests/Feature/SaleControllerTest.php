<?php

use App\Models\Delivery;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;

use function Pest\Faker\faker;
use function Pest\Laravel\assertSoftDeleted;
use function PHPUnit\Framework\assertEquals;

beforeEach(function () {
    $product = Product::factory()->create();

    Delivery::factory()
        ->has(Transaction::factory()->state([
            'product_id' => $product->id,
            'quantity' => 100,
            'price' => faker()->randomNumber(2),
        ]))
        ->create();
});

it('shows the sales', function () {
    authenticated()
        ->getJson(route('sales.index'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'invoice',
                    'or_number',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('shows a specific sale', function () {
    $sale = Sale::factory()
        ->has(Transaction::factory()->state([
            'product_id' => Product::first()->id,
            'quantity' => -10,
            'price' => faker()->randomNumber(2),
        ]))
        ->create();

    authenticated()
        ->getJson(route('sales.show', $sale))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'invoice',
                'or_number',
                'created_at',
                'updated_at',
            ],
        ]);

    assertEquals(
        Product::withSum('transactions as stocks', 'quantity')->first()->stocks,
        90
    );
});

it('can create a sale', function () {
    $payload = Sale::factory()->make();

    $payload = array_merge($payload->toArray(), [
        'items' => [
            [
                'product_id' => Product::first()->id,
                'quantity' => 10,
                'price' => faker()->randomNumber(2),
            ]
        ],
    ]);

    authenticated()
        ->postJson(route('sales.store'), $payload)
        ->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'invoice',
                'or_number',
                'created_at',
                'updated_at',
            ],
        ]);

    assertEquals(
        Product::withSum('transactions as stocks', 'quantity')->first()->stocks,
        90
    );
});

it('can update a sale', function () {
    $sale = Sale::factory()
        ->has(Transaction::factory()->state([
            'product_id' => Product::first()->id,
            'quantity' => -10,
            'price' => faker()->randomNumber(2),
        ]))
        ->create();

    assertEquals(
        Product::withSum('transactions as stocks', 'quantity')->first()->stocks,
        90
    );

    $payload = $sale->toArray();

    $payload = array_merge($payload, [
        'items' => [
            [
                'product_id' => Product::first()->id,
                'quantity' => 20,
                'price' => faker()->randomNumber(2),
            ]
        ],
    ]);

    authenticated()
        ->putJson(route('sales.update', $sale), $payload)
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'invoice',
                'or_number',
                'created_at',
                'updated_at',
            ],
        ]);

    assertEquals(
        Product::withSum('transactions as stocks', 'quantity')->first()->stocks,
        80
    );
});

it('can delete a sale', function () {
    $sale = Sale::factory()
        ->has(Transaction::factory()->state([
            'product_id' => Product::first()->id,
            'quantity' => -10,
            'price' => faker()->randomNumber(2),
        ]))
        ->create();

    assertEquals(
        Product::withSum('transactions as stocks', 'quantity')->first()->stocks,
        90
    );

    authenticated()
        ->deleteJson(route('sales.destroy', $sale))
        ->assertNoContent();

    assertSoftDeleted('sales', ['id' => $sale->id]);

    assertEquals(
        Product::withSum('transactions as stocks', 'quantity')->first()->stocks,
        100
    );
});
