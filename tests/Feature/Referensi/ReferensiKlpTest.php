<?php

use App\Models\Referensi\Klp;
use Illuminate\Http\UploadedFile;

beforeEach(function () {
    setRedisDriverToArray();

    $this->endpoint = '/api/v1/ref/klp';
});

it('can get list', function () {
    $response = $this->getJson($this->endpoint . '/list')->assertStatus(200);

    $response->assertJsonStructure([
        'status',
        'message',
        'data'
    ]);
});

it('can show detail', function () {
    $data = Klp::factory()->count(1)->create()->first();

    $response = $this->get($this->endpoint . '/' . $data->id);
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'status',
        'message',
        'data' => [
            'id',
            'parent_id',
            'parent_root_id',
            'kode',
            'nama',
            'nama_pendek',
            'jenis',
            'level',
            'attrs',
            'is_active',
            'lokasi',
            'pimpinan',
            'jabatan_pimpinan',
            'logo',
            'no_telp',
            'website',
            'fax',
            'alamat',
            'created_by',
            'created_by_name',
            'updated_by',
            'updated_by_name',
            'created_at',
            'updated_at',
            'logo_url',
        ],
    ]);
});

it('can store data', function ($payload, $status = 200, $errors = null) {
    $payload['logo'] = UploadedFile::fake()->image('gambar.jpg');
    $response = $this->postJson($this->endpoint, $payload);

    $response->assertStatus($status);

    if ($errors) {
        $response->assertJsonValidationErrors($errors);
    }
})->with('referensi-klp:payload');

it('can update data', function ($payload, $status = 200, $errors = null) {
    $data = Klp::factory()->count(1)->create()->first();
    $id = $data->id;

    $payload['logo'] = UploadedFile::fake()->image('gambar.jpg');
    $response = $this->putJson($this->endpoint . '/' . $id, $payload);
    $response->assertStatus($status);

    if ($errors) {
        $response->assertJsonValidationErrors($errors);
    }
})->with('referensi-klp:payload');

it('can delete data', function () {
    $data = Klp::factory()->count(1)->create()->first();
    $response = $this->deleteJson($this->endpoint . '/' . $data->id);

    $response->assertStatus(200);
});
