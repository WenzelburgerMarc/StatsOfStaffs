<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absence>
 */
class AbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 10),
            'start_date' => fake()->dateTimeBetween('-1 years', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+1 years'),
            'absence_reason_id' => fake()->numberBetween(1, 5),
            'comment' => fake()->sentence(),
            'document' => null,
            'original_document_name' => null,
            'status_id' => 1, // pending, approved, rejected
            'approved_by' => null,
            'approved_at' => null,

        ];
    }
}
