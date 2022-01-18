<?php

use function Pest\Laravel\getJson;
use App\Models\Location;

beforeEach(function () {
    Location::factory()->count(10)->create();
});

it('throws an error when the user is not authenticated', function () {
    getJson(route('locations.index'))
        ->assertUnauthorized();
});

it('shows the locations', function () {
    authenticated()
        ->getJson(route('locations.index'))
        ->assertOk()
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

it('shows a specific location', function () {
    $location = Location::first();

    authenticated()
        ->getJson(route('locations.show', $location))
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

it('can create a location', function () {
    $location = Location::factory()->make();

    authenticated()
        ->postJson(route('locations.store'), $location->toArray())
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

it('can update a location', function () {
    $location = Location::first();

    $location->name = 'New name';

    authenticated()
        ->putJson(route('locations.update', $location), $location->toArray())
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'created_at',
                'updated_at',
            ],
        ]);

    expect($location->fresh()->name)->toBe('New name');
});

it('can delete a location', function () {
    $location = Location::first();

    authenticated()
        ->deleteJson(route('locations.destroy', $location))
        ->assertNoContent();
});

it('throws an error when creating a location with invalid data', function () {
    authenticated()
        ->postJson(route('locations.store'), [])
        ->assertJsonValidationErrors(['name']);
});

it('throws an error when updating a location with invalid data', function () {
    $location = Location::first();

    authenticated()
        ->putJson(route('locations.update', $location), [])
        ->assertJsonValidationErrors(['name']);
});

it('throws an error when creating an existing location', function () {
    $location = Location::first();

    authenticated()
        ->postJson(route('locations.store'), $location->toArray())
        ->assertJsonValidationErrors(['name']);
});

it('throws an error when updating an existing location', function () {
    $location = Location::factory()->create();

    authenticated()
        ->putJson(route('locations.update', $location), [
            'name' => Location::first()->name,
        ])
        ->assertJsonValidationErrors(['name']);
});
