<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maintenance>
 */
class MaintenanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_id' => \App\Models\Asset::factory(),
            'user_id' => \App\Models\User::factory(),
            'description' => fake()->randomElement([
                'Cambio de aceite',
                'Limpieza general',
                'Reparación de pantalla',
                'Actualización de software',
                'Reemplazo de batería',
                'Mantenimiento preventivo',
                'Revisión técnica',
            ]),
            'cost' => fake()->randomFloat(2, 50, 1000),
            'performed_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'type' => fake()->randomElement(['preventive', 'corrective']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
