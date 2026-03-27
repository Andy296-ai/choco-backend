<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'telegram_id' => (string) $this->faker->unique()->randomNumber(8),
            'telegram_username' => $this->faker->userName(),
        ];
    }
}
