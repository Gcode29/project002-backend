<?php

use App\Models\UOM;
use App\Models\User;

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    UOM::factory()->count(10)->create();

    $this->user = User::factory()->create();
});

it('shows all the uoms', function () {
    actingAs($this->user)->getJson(route('uoms.index'))
        ->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'short_name',
                    'long_name',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
});

it('shows a specific uom', function () {
    actingAs($this->user)->getJson(route('uoms.show', [
        'uom' => UOM::first()->id,
    ]))
    ->assertOk()
    ->assertJsonStructure([
        'data' => [
            'id',
            'short_name',
            'long_name',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can create a uom', function () {
    actingAs($this->user)->postJson(route('uoms.store'), [
        'short_name' => 'bg',
        'long_name' => 'Bag',
    ])
    ->assertCreated()
    ->assertJsonStructure([
        'data' => [
            'id',
            'short_name',
            'long_name',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can update a uom', function () {
    actingAs($this->user)->putJson(route('uoms.update', [
        'uom' => UOM::first()->id,
    ]), [
        'short_name' => 'bg',
        'long_name' => 'Bag',
    ])
    ->assertOk()
    ->assertJsonStructure([
        'data' => [
            'id',
            'short_name',
            'long_name',
            'created_at',
            'updated_at',
        ],
    ]);
});

it('can delete a uom', function () {
    actingAs($this->user)->deleteJson(route('uoms.destroy', [
        'uom' => UOM::first()->id,
    ]))
    ->assertNoContent();
});

it('throws an error when creating a uom with invalid payload', function () {
    actingAs($this->user)
        ->postJson(route('uoms.store'), [])
        ->assertJsonValidationErrors(['short_name', 'long_name']);
});

it('throws an error when updating a uom with invalid payload', function () {
    actingAs($this->user)
        ->putJson(route('uoms.update', UOM::first()->id), [])
        ->assertJsonValidationErrors(['short_name', 'long_name']);
});

it('throws an error when creating a uom with an existing short_name', function () {
    actingAs($this->user)
        ->postJson(route('uoms.store'), [
            'short_name' => UOM::first()->short_name,
            'long_name' => 'Bag',
        ])
        ->assertJsonValidationErrors(['short_name']);
});

it('throws an error when updating a uom with an existing short_name', function () {
    $uom = UOM::factory()->create();

    actingAs($this->user)
        ->putJson(route('uoms.update', $uom->id), [
            'short_name' => UOM::first()->short_name,
            'long_name' => 'Bag',
        ])
        ->assertJsonValidationErrors(['short_name']);
});
