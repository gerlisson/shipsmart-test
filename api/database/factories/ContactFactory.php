<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '11999999999',
            'zip_code' => '01001-000',
            'state' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Centro',
            'address' => 'Rua Teste',
            'number' => '123',
        ];
    }
}
