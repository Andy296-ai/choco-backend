<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SalonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'description' => $this->faker->sentence(),
            'latitude' => clone $this->faker->latitude(),
            'longitude' => clone $this->faker->longitude(),
        ];
    }
}
