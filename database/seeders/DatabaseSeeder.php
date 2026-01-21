<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@fixflow.com',
            'role' => 'admin',
        ]);

        // Create technician users
        $technicians = User::factory(5)->create([
            'role' => 'technician',
        ]);

        // Create assets
        $assets = \App\Models\Asset::factory(20)->create();

        // Create maintenances for assets
        foreach ($assets as $asset) {
            $maintenanceCount = rand(2, 8);
            for ($i = 0; $i < $maintenanceCount; $i++) {
                \App\Models\Maintenance::factory()->create([
                    'asset_id' => $asset->id,
                    'user_id' => $technicians->random()->id,
                ]);
            }
        }
    }
}
