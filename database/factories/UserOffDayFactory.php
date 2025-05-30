<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserOffDay>
 */
class UserOffDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::where('email', 'test@example.com')->first();
        return [
            'user_id' => $user->id,
            'date' => fake()->dateTimeBetween(startDate: '-1 week', endDate: '+1 month'),
            'reason' => fake()->sentence(),
        ];
    }
}
