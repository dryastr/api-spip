<?php

use App\Models\User\User;
use App\Helpers\AuthHelper;

beforeEach(function () {
    setRedisDriverToArray();

    $this->endpoint = '/api/v1/user';
});

it('can user get my profile', function () {
    $user = User::factory()->create();

    config()->set('auth.acting-as.user_id', $user->id);

    $authHelper = app(AuthHelper::class);
    $authHelper->storeAuthToken([
        'fullname' => $user->fullname,
        'email' => $user->email,
        'user_id' => $user->id,
    ]);

    $response = $this->getJson($this->endpoint);

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'data' => [
            'id',
            'fullname',
            'email',
        ],
    ]);
});
