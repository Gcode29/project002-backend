<?php

use App\Models\Supplier;
use App\Models\User;

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    Supplier::factory()->count(10)->create();

    $this->user = User::factory()->create();
});

it('shows all the suppliers', function () {
    actingAs($this->user)->getJson(route('suppliers.index'))
        ->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'address',
                    'contact_person',
                    'contact_number',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('shows a specific supplier', function () {
    actingAs($this->user)
    ->getJson(route('suppliers.show', Supplier::first()->id))
    ->assertOk()
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'address',
            'contact_person',
            'contact_number',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can create a supplier', function () {
    $payload = Supplier::factory()->make()->toArray();

    actingAs($this->user)->postJson(route('suppliers.store'), $payload)
    ->assertCreated()
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'address',
            'contact_person',
            'contact_number',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can update a supplier', function () {
    $supplier = Supplier::first();

    actingAs($this->user)
    ->putJson(route('suppliers.update', $supplier->id), [
        'name' => 'New Supplier',
        'address' => $supplier->address,
        'contact_person' => $supplier->contact_person,
        'contact_number' => $supplier->contact_number,
    ])
    ->assertOk()
    ->assertJsonStructure([
        'data' => [
            'id',
            'name',
            'address',
            'contact_person',
            'contact_number',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can delete a supplier', function () {
    actingAs($this->user)
    ->deleteJson(route('suppliers.destroy', Supplier::first()->id))
    ->assertNoContent();
});

it('throws an error when creating a supplier with an invalid name', function () {
    actingAs($this->user)->postJson(route('suppliers.store'), [
        'name' => '',
    ])
    ->assertJsonValidationErrors('name');
});

it('throws an error when updating a supplier with an invalid name', function () {
    actingAs($this->user)->putJson(route('suppliers.update', Supplier::first()->id), [
        'name' => '',
    ])
    ->assertJsonValidationErrors('name');
});

it('throws an error when creating a supplier with an existing name', function () {
    actingAs($this->user)->postJson(route('suppliers.store'), [
        'name' => Supplier::first()->name,
    ])
    ->assertJsonValidationErrors('name');
});

it('throws an error when updating a supplier with an existing name', function () {
    $supplier = Supplier::factory()->create();

    actingAs($this->user)->putJson(route('suppliers.update', $supplier->id), [
        'name' => Supplier::first()->name,
    ])
    ->assertJsonValidationErrors('name');
});
