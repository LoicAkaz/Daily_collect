<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_client' => $this->generateID(),
            'nom_client' => fake()->name(),
            'prenom_client' => fake()->firstName(),
            'age' => rand(10,100),
            'sexe_client' => "M",
            'cni_client' => fake()->uuid(),
            'telephone_client' => Str::substr(fake()->unique()->phoneNumber(), 0, 10),
            'addresse_client' => static::$password ??= Hash::make('password'),
            // 'remember_token' => Str::random(10),
        ];
    }
}
