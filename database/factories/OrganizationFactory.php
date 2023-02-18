<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //'user_id' => User::inRandomOrder()->first('id'),
            'title' => $this->faker->unique()->company(),
            'type' => '1',
            'inn' => (string) $this->faker->unique()->numberBetween(111111111111, 999999999999),
            'kpp' => (string) $this->faker->numberBetween(111111111111, 999999999999),
            'ogrn' => (string) $this->faker->numberBetween(1111111111111, 9999999999999),
            'director' => $this->faker->firstName('male'),
            'director_post' => 'Генеральный директор',
            'accountant' => $this->faker->firstName('female'),
            'legal_address' => $this->faker->address(),
            'actual_address' => $this->faker->address(),
        ];
    }
}
