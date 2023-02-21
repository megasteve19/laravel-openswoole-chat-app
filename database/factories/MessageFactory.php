<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
		$date = fake()->dateTimeBetween('-2 hours');

        return [
            'content' => fake()->text(255),
			'created_at' => $date,
			'updated_at' => $date,
        ];
    }
}
