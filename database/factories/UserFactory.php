<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hrm_id' => fake()->unique()->randomNumber('5'),
            'user_no' => fake()->randomNumber('5'),
            'chinese_name' => fake()->name(),
            'english_name' => fake()->userName(),
            'phone_number' => fake()->phoneNumber(),
            'mobile_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'created_at' => date('Y-m-d H:i:s', time()),
            'created_by' => 'Laravel seeder',
            'is_valid' => fake()->numberBetween(0, 1),
        ];
    }
}
