<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Laptop Dell',
                'Impresora HP',
                'Monitor Samsung',
                'Router Cisco',
                'Servidor IBM',
                'Proyector Epson',
                'Escáner Canon',
                'Teléfono IP',
            ]) . ' ' . fake()->numerify('###'),
            'serial_number' => strtoupper(fake()->bothify('??-####-????')),
            'purchase_date' => fake()->dateTimeBetween('-5 years', '-1 month'),
            'status' => fake()->randomElement(['active', 'broken', 'under_maintenance', 'retired']),
            'location' => fake()->randomElement([
                'Oficina Principal',
                'Bodega',
                'Sala de Juntas',
                'Departamento IT',
                'Recepción',
            ]),
            'description' => fake()->sentence(),
            'purchase_cost' => fake()->randomFloat(2, 100, 5000),
            'maintenance_interval_days' => fake()->randomElement([30, 60, 90, 180, 365]),
            'next_maintenance_date' => fake()->dateTimeBetween('now', '+6 months'),
        ];
    }
}
