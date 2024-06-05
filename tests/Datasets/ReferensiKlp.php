<?php

dataset('referensi-klp:payload', [
    [
        [
            'ref_lokasi_id' => 1,
            'kode' => 'KLP0192',
            'nama' => 'Testing Klp',
            'nama_pendek' => 'TestKlp',
            'pimpinan' => 'Amir',
            'jabatan_pimpinan' => 'IT',
            'jenis' => 'PS',
            'level' => 'PUSAT',
            'lokasi' => 'Jakarta',
            'no_telp' => '01817112',
            'website' => 'google.com',
            'fax' => '10192811',
            'alamat' => 'Jakarta',
        ], 200
    ],[
        [
            'ref_lokasi_id' => 1,
            'kode' => 'KLP0192',
            'nama' => 'Testing Klp a',
            'nama_pendek' => 'TestKlp t',
            'pimpinan' => 'Amir',
            'jabatan_pimpinan' => 'IT',
            'jenis' => 'PS',
            'level' => 'PUSAT',
            'lokasi' => 'Jakarta',
            'no_telp' => '01817112',
            'website' => 'google.com',
            'fax' => '10192811',
            'alamat' => 'Jakarta',
        ], 200
    ],[
        [
            'ref_lokasi_id' => 1,
            'kode' => null,
            'nama' => 'Testing Klp',
            'nama_pendek' => 'TestKlp',
            'pimpinan' => 'Amir',
            'jabatan_pimpinan' => 'IT',
            'jenis' => 'PS',
            'level' => 'PUSAT',
            'lokasi' => 'Jakarta',
            'no_telp' => '01817112',
            'website' => 'google.com',
            'fax' => '10192811',
            'alamat' => 'Jakarta',
        ], 422
    ],[
        [
            'ref_lokasi_id' => 1,
            'kode' => 'K1',
            'nama' => null,
            'nama_pendek' => 'TestKlp',
            'pimpinan' => 'Amir',
            'jabatan_pimpinan' => 'IT',
            'jenis' => 'PS',
            'level' => 'PUSAT',
            'lokasi' => 'Jakarta',
            'no_telp' => '01817112',
            'website' => 'google.com',
            'fax' => '10192811',
            'alamat' => 'Jakarta',
        ], 422
    ],[
        [
            'kode' => 'KLP0192',
            'nama' => 'Testing Klp',
            'nama_pendek' => 'TestKlp',
            'pimpinan' => null,
            'jabatan_pimpinan' => 'IT',
            'jenis' => 'PS',
            'level' => 'PUSAT',
            'lokasi' => 'Jakarta',
            'no_telp' => '01817112',
            'website' => 'google.com',
            'fax' => '10192811',
            'alamat' => 'Jakarta',
        ], 422
    ],[
        [
            'kode' => 'KLP0192',
            'nama' => 'Testing Klp',
            'nama_pendek' => 'TestKlp',
            'pimpinan' => 'Amir',
            'jabatan_pimpinan' => 'IT',
            'jenis' => null,
            'level' => 'PUSAT',
            'lokasi' => 'Jakarta',
            'no_telp' => '01817112',
            'website' => 'google.com',
            'fax' => '10192811',
            'alamat' => 'Jakarta',
        ], 422
    ],[
        [
            'kode' => 'KLP0192',
            'nama' => 'Testing Klp',
            'nama_pendek' => 'TestKlp',
            'pimpinan' => 'Amir',
            'jabatan_pimpinan' => 'IT',
            'jenis' => 'PS',
            'level' => 'PUSAT',
            'lokasi' => 'Jakarta',
            'no_telp' => '01817112',
            'website' => 'google.com',
            'fax' => '10192811',
            'alamat' => null,
        ], 422
    ],
]);
