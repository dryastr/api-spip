<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (in_array(config('app.env'), ['testing'])) {
            $this->call([
                MenuSeeder::class,
                RoleSeeder::class,
                ReferensiLokasiSeeder::class,
                ReferensiKlpSeeder::class,
                UserSeeder::class,
            ]);
        } else {
            $this->call([
                MenuSeeder::class,
                RoleSeeder::class,
                ReferensiKlpSeeder::class,
                UserSeeder::class,
            ]);
        }
    }
}
