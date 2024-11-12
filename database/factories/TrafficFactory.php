<?php

namespace Database\Factories;

use App\Models\Jalan;
use App\Models\Kendaraan;
use App\Models\Traffic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Traffic>
 */
class TrafficFactory extends Factory
{
    protected $model = Traffic::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jalan = Jalan::inrandomOrder()->first();
        $kendaraan = Kendaraan::inrandomOrder()->first();
        return [
            'id_jenis' => $kendaraan->id,
            'id_ruas' => $jalan->id,
            'kecepatan' => fake()->randomNumber(2, true),
            'tanggal' => fake()->dateTimeBetween('-30 days')->format('Y-m-d H:i:s'),
        ];
    }

    public function between($start, $end = 'now'): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal' => fake()->dateTimeBetween($start, $end)->format('Y-m-d H:i:s'),
        ]);
    }
}
