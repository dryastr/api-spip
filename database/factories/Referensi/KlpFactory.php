<?php

namespace Database\Factories\Referensi;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class KlpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => fake()->word(),
            'nama' => fake()->word(),
            'nama_pendek' => fake()->word(),
            'jenis' => fake()->randomElement(['PS', 'KL', 'PEMDA']),
            'level' => fake()->randomElement(['PUSAT', 'NON-PUSAT']),
            'is_active' => 1,
            'lokasi' => fake()->address(),
            'pimpinan' => fake()->word(),
            'jabatan_pimpinan' => fake()->word(),
            'logo' => 'logo.jpg',
            'no_telp' => fake()->phoneNumber(),
            'website' => 'google.com',
            'fax' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'created_by' => 1,
            'created_by_name' => fake()->firstName(),
            'updated_by' => 1,
            'updated_by_name' => fake()->firstName(),
        ];
    }
}
