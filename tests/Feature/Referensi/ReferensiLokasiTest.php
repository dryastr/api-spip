<?php

beforeEach(function () {
    setRedisDriverToArray();

    $this->endpoint = '/api/v1/ref/lokasi';
});

it('can get list', function () {
    $response = $this->getJson($this->endpoint . '/list')->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'data'
    ]);
});
