<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Referensi\Klp;

class ReferensiKlpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Klp::updateOrCreate([
            'kode' => '089',
            'nama' => 'Badan Pengawasan Keuangan dan Pembangunan',
        ], [
            'parent_id' => null,
            'parent_root_id' => null,
            'kode' => '089',
            'nama' => 'Badan Pengawasan Keuangan dan Pembangunan',
            'nama_pendek' => 'BPKP',
            'jenis' => 'KL',
            'level' => 'PUSAT',
            'attrs' => null,
            'is_active' => 1,
            'lokasi' => null,
            'pimpinan' => null,
            'jabatan_pimpinan' => null,
            'logo' => null,
            'no_telp' => null,
            'website' => null,
            'fax' => null,
            'alamat' => null,
            'created_by' => null,
            'created_by_name' => 'SYSTEM',
            'updated_by' => null,
            'updated_by_name' => 'SYSTEM',
        ])->toSql();

        Klp::updateOrCreate([
            'kode' => '028',
            'nama' => 'BPKP Provinsi Jawa Barat',
        ], [
            'parent_id' => null,
            'parent_root_id' => null,
            'kode' => '028',
            'nama' => 'BPKP Provinsi Jawa Barat',
            'nama_pendek' => 'Jabar',
            'jenis' => 'KL',
            'level' => 'NON-PUSAT',
            'attrs' => null,
            'is_active' => 1,
            'lokasi' => null,
            'pimpinan' => null,
            'jabatan_pimpinan' => null,
            'logo' => null,
            'no_telp' => null,
            'website' => null,
            'fax' => null,
            'alamat' => null,
            'created_by' => null,
            'created_by_name' => 'SYSTEM',
            'updated_by' => null,
            'updated_by_name' => 'SYSTEM',
        ])->toSql();

        Klp::updateOrCreate([
            'kode' => '0281',
            'nama' => 'Pemda Provinsi Jawa Barat',
        ], [
            'parent_id' => null,
            'parent_root_id' => null,
            'kode' => '0281',
            'nama' => 'Pemda Provinsi Jawa Barat',
            'nama_pendek' => 'Jabar',
            'jenis' => 'PEMDA',
            'level' => 'NON-PUSAT',
            'attrs' => null,
            'is_active' => 1,
            'lokasi' => null,
            'pimpinan' => null,
            'jabatan_pimpinan' => null,
            'logo' => null,
            'no_telp' => null,
            'website' => null,
            'fax' => null,
            'alamat' => null,
            'created_by' => null,
            'created_by_name' => 'SYSTEM',
            'updated_by' => null,
            'updated_by_name' => 'SYSTEM',
        ])->toSql();
    }
}
