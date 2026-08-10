<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => null,
            'role' => 'partner', // Define que el usuario es un socio
        ];
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

    /**
     * Administrador del sistema.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::ADMIN_USER,
        ]);
    }

    /**
     * Partner (rol por defecto, explícito para que el test se lea solo).
     */
    public function partner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => User::PARTNER_USER,
        ]);
    }

    /**
     * Partner que ya activó su cuenta desde el mail de bienvenida.
     */
    public function activated(): static
    {
        return $this->state(fn (array $attributes) => [
            'activated_at' => now(),
        ]);
    }

    /**
     * Partner recién creado por el admin, todavía sin activar.
     */
    public function pendingActivation(): static
    {
        return $this->state(fn (array $attributes) => [
            'activated_at' => null,
            'email_verified_at' => null,
        ]);
    }
}
