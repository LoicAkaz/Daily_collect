<?php

namespace Database\Factories;

use App\Models\User;
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
            'id_user' => $this->generateID(),
            'nom' => fake()->name(),
            'prenom' => fake()->firstName(),
            'age' => rand(10,100),
            'sexe' => "M",
            'username' => fake()->name(),
            'telephone' => Str::substr(fake()->unique()->phoneNumber(), 0, 10),
            'password' => static::$password ??= Hash::make('password'),
            // 'remember_token' => Str::random(10),
        ];
    }

    function generateID()
    {
        $id = rand(100,999999);
        $user= User::where('id_user', $id)->first();
        if($user){
            return $this->generateID();
        }
        return $id;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
