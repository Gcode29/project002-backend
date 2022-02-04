<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\{
    assertAuthenticatedAs,
    getJson,
    postJson
};

it('can authenticate a user', function () {
    /** @var \App\Models\User */
    $user = User::factory()->create();

    postJson(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertStatus(201);
});

it('returns the user information when authenticated', function () {
    $user = Sanctum::actingAs(User::factory()->create());

    assertAuthenticatedAs($user, 'sanctum');

    $response = getJson(route('me'))->json();

    expect($user)
        ->email->toEqual($response['email']);
});

it('can logout a user', function () {
    Sanctum::actingAs(User::factory()->create());

    postJson(route('logout'))
        ->assertStatus(201);
});

it('throws an error when logging out without being authenticated', function () {
    postJson(route('logout'))
        ->assertStatus(401);
});