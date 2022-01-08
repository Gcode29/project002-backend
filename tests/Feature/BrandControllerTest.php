<?php

use App\Models\Brand;
use App\Models\User;

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    Brand::factory()->count(10)->create();

    $this->user = User::factory()->create();
});

it('shows all the brands', function () {
    actingAs($this->user)->getJson(route('brands.index'))
        ->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('shows a specific brand', function () {
    actingAs($this->user)->getJson(route('brands.show', [
        'brand' => Brand::first()->id,
    ]))
    ->assertOk()
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can create a brand', function () {
    actingAs($this->user)->postJson(route('brands.store'), [
        'name' => 'New Brand',
    ])
    ->assertCreated()
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can update a brand', function () {
    actingAs($this->user)->putJson(route('brands.update', [
        'brand' => Brand::first()->id,
    ]), [
        'name' => 'New Brand',
    ])
    ->assertOk()
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can delete a brand', function () {
    actingAs($this->user)->deleteJson(route('brands.destroy', [
        'brand' => Brand::first()->id,
    ]))
    ->assertNoContent();
});

it('throws an error when creating a brand with an invalid name', function () {
    actingAs($this->user)->postJson(route('brands.store'), [
        'name' => '',
    ])
    ->assertJsonValidationErrors('name');
});

it('throws an error when updating a brand with an invalid name', function () {
    actingAs($this->user)->putJson(route('brands.update', [
        'brand' => Brand::first()->id,
    ]), [
        'name' => '',
    ])
    ->assertJsonValidationErrors('name');
});

it('throws an error when creating a brand with an existing name', function () {
    actingAs($this->user)->postJson(route('brands.store'), [
        'name' => Brand::first()->name,
    ])
    ->assertJsonValidationErrors('name');
});

it('throws an error when updating a brand with an existing name', function () {
    $brand = Brand::factory()->create();

    actingAs($this->user)->putJson(route('brands.update', $brand->id), [
        'name' => Brand::first()->name,
    ])
    ->assertJsonValidationErrors('name');
});
