<?php

use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use App\Models\User;

use function Pest\Laravel\{actingAs, getJson};

beforeEach(function () {
    Category::factory()->count(10)->create();

    $this->user = User::factory()->create();
});

it('shows all the categories', function () {
    actingAs($this->user)->getJson(route('categories.index'))
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

it('shows a specifc category', function () {
    actingAs($this->user)->getJson(route('categories.show', [
        'category' => Category::first()->id,
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

it('can create a category', function () {
    actingAs($this->user)->postJson(route('categories.store'), [
        'name' => 'New Category',
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

it('can update a category', function () {
    actingAs($this->user)->patchJson(route('categories.update', [
        'category' => Category::first()->id,
    ]), [
        'name' => 'New Category',
    ])
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'New Category',
        ]);
});

it('can delete a category', function () {
    actingAs($this->user)->deleteJson(route('categories.destroy', [
        'category' => Category::first()->id,
    ]))
        ->assertNoContent();
});

it('throws an error when creating a category with an invalid name', function () {
    actingAs($this->user)->postJson(route('categories.store'), [
        'name' => '',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('throws an error when creating an existing category', function () {
    actingAs($this->user)->postJson(route('categories.store'), [
        'name' => Category::first()->name,
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('throws an error when updating a category with an invalid name', function () {
    actingAs($this->user)->patchJson(route('categories.update', [
        'category' => Category::first()->id,
    ]), [
        'name' => '',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('throws an error when updating an existing category', function () {
    $category = Category::factory()->create();

    actingAs($this->user)->patchJson(route('categories.update', $category), [
        'name' => Category::first()->name,
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});
