<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Service ' . $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 500, 5000),
            'duration_minutes' => $this->faker->randomElement([30, 60, 90, 120]),
        ];
    }
}
