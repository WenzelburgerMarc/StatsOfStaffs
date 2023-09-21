<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => fake()->sentence(),
            'sender_id' => fake()->numberBetween(1, 10),
            'receiver_id' => fake()->numberBetween(1, 10),
            'read_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
