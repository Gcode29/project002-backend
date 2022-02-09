<?php

use App\Models\Client;

it('can create a client', function () {
    $client = Client::factory()->make();

    authenticated()
        ->postJson(route('clients.store'), $client->toArray())
        ->assertCreated();
});
