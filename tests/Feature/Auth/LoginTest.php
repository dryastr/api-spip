<?php

use App\Models\User\User;

beforeEach(function () {
    setRedisDriverToArray();

    $this->endpoint = '/api/v1/login';
});

it('can user login', function () {
    $user = User::factory()->create();

    $response = $this->postJson($this->endpoint, [
        'email' => $user->email,
        'password' => 'Password123',
    ]);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'data' => [
            'token_type',
            'expire_at',
            'access_token',
        ],
    ]);
});

it('cant user login without email', function () {
    $response = $this->postJson($this->endpoint, [
        'email' => '',
        'password' => 'Password123',
    ]);

    $response->assertStatus(422);

    $response->assertJsonStructure([
        'message',
        'errors' => [
            'email',
        ],
    ]);
});

it('cant user login without password', function () {
    $response = $this->postJson($this->endpoint, [
        'email' => 'testing@mail.com',
        'password' => '',
    ]);

    $response->assertStatus(422);

    $response->assertJsonStructure([
        'message',
        'errors' => [
            'password',
        ],
    ]);
});
