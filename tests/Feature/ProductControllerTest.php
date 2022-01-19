<?php

use App\Models\Category;
use App\Models\Product;

use function Pest\Laravel\{assertDatabaseMissing, getJson};
use App\Models\Brand;
use App\Models\UOM;

beforeEach(function () {
    Product::factory()->count(10)->create();
});

it('throws an error when the user is not authenticated', function () {
    getJson(route('locations.index'))
        ->assertUnauthorized();
});

it('shows the products', function () {
    authenticated()
        ->getJson(route('products.index'))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'code',
                    'name',
                    'description',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('shows a specific product', function () {
    authenticated()
        ->getJson(route('products.show', [
            'product' => Product::first()->id,
        ]))
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
                'description',
                'created_at',
                'updated_at',
            ],
        ]);
});

it('can create a product', function () {
    $payload = Product::factory()
        ->make([
            'category_id' => Category::factory()->create()->id,
            'brand_id' => Brand::factory()->create()->id,
            'u_o_m_id' => UOM::factory()->create()->id,
        ])
        ->toArray();

    authenticated()
        ->postJson(route('products.store'), $payload)
        ->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
                'description',
                'created_at',
                'updated_at',
            ],
        ]);
});

it('can update a product', function () {
    $payload = Product::factory()
        ->make([
            'category_id' => Category::factory()->create()->id,
            'brand_id' => Brand::factory()->create()->id,
            'u_o_m_id' => UOM::factory()->create()->id,
        ])
        ->toArray();

    authenticated()
        ->putJson(route('products.update', Product::first()), $payload)
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'code',
                'name',
                'description',
                'created_at',
                'updated_at',
            ],
        ]);
});

it('can delete a product', function () {
    $product = Product::first();

    authenticated()
        ->deleteJson(route('products.destroy', $product->id))
        ->assertNoContent();

    assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

it('throws an error when creating a product with invalid data', function () {
    authenticated()
        ->postJson(route('products.store'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'code',
            'name',
            'category_id',
            'brand_id',
            'u_o_m_id',
        ]);
});

it('throws an error when creating a product with invalid relationship', function () {
    $payload = Product::factory()
        ->make([
            'category_id' => 999,
            'brand_id' => 999,
            'u_o_m_id' => 999,
        ])
        ->toArray();

    authenticated()
        ->postJson(route('products.store'), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'category_id',
            'brand_id',
            'u_o_m_id',
        ]);
});

it('throws an error when updating a product with invalid data', function () {
    authenticated()
        ->putJson(route('products.update', Product::first()), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'code',
            'name',
            'category_id',
            'brand_id',
            'u_o_m_id',
        ]);
});

it('throws an error when updating a product with invalid relationship', function () {
    $payload = Product::factory()
        ->make([
            'category_id' => 999,
            'brand_id' => 999,
            'u_o_m_id' => 999,
        ])
        ->toArray();

    authenticated()
        ->putJson(route('products.update', Product::first()), $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'category_id',
            'brand_id',
            'u_o_m_id',
        ]);
});
