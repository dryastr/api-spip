<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Referensi\Lokasi;

class ReferensiLokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lokasi::factory(1)->create();
    }
}
