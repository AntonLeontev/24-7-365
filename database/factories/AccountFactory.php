<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //'user_id' => User::factory(),
            //'organization_id' => User::with('organization')->find($user_id)->organization->id,
            //'organization_id'=> Organization::factory(),
            'payment_account' => (string) $this->faker->unique()->numberBetween(111111111111111111, 999999999999999999) . '00',
            'correspondent_account' => (string) $this->faker->unique()->numberBetween(111111111111111111, 999999999999999999) . '00',
            'bik' => (string) $this->faker->unique()->numberBetween(111111111, 999999999),
            'bank' => $this->faker->unique()->company(),
            'status' => 1,
        ];
    }
}
