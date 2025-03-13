<?php

namespace Database\Factories;

use App\Models\TransactionalEmail;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionalEmailFactory extends Factory
{
    protected $model = TransactionalEmail::class;

    public function definition()
    {
        return [
            'recipient_type' => $this->faker->randomElement(['partner', 'user']),
            'title' => $this->faker->sentence(),
            'subject' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['estado', 'notificacion', 'recordatorio']),
            'variant' => $this->faker->randomElement(['nunca_respondio', 'respondio_antes', null]),
            'body' => $this->faker->paragraph(3),
        ];
    }
}
