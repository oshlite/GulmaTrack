<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drone>
 */
class DroneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => $this->faker->sentence(4),
            'lokasi' => $this->faker->city(),
            'tanggal_perencanaan' => $this->faker->date(),
            'pdf_path' => 'drones/' . $this->faker->slug() . '.pdf',
            'pdf_filename' => $this->faker->slug() . '.pdf',
            'user_id' => null,
        ];
    }
}
