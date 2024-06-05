<?php

namespace Database\Factories\Referensi;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class LokasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'kode' => fake()->word(),
            'nama' => fake()->word(),
            'level' => fake()->word(),
            'is_active' => 1,
            'created_by' => 1,
            'created_by_name' => fake()->firstName(),
            'updated_by' => 1,
            'updated_by_name' => fake()->firstName(),
        ];
    }
}
