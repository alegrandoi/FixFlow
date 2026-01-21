<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_view_maintenances_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('maintenances.index'));

        $response->assertOk();
    }

    public function test_authenticated_users_can_view_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('maintenances.create'));

        $response->assertOk();
    }

    public function test_authenticated_users_can_create_maintenance(): void
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();

        $maintenanceData = [
            'asset_id' => $asset->id,
            'description' => 'Routine oil change',
            'cost' => 50.00,
            'performed_at' => now()->format('Y-m-d'),
            'type' => 'preventive',
            'notes' => 'Used synthetic oil',
        ];

        $response = $this->actingAs($user)->post(route('maintenances.store'), $maintenanceData);

        $response->assertRedirect(route('maintenances.index'));
        $this->assertDatabaseHas('maintenances', [
            'asset_id' => $asset->id,
            'description' => 'Routine oil change',
            'user_id' => $user->id,
        ]);
    }

    public function test_maintenance_requires_valid_asset(): void
    {
        $user = User::factory()->create();

        $maintenanceData = [
            'asset_id' => 99999,
            'description' => 'Test',
            'cost' => 50.00,
            'performed_at' => now()->format('Y-m-d'),
            'type' => 'preventive',
        ];

        $response = $this->actingAs($user)->post(route('maintenances.store'), $maintenanceData);

        $response->assertSessionHasErrors('asset_id');
    }

    public function test_authenticated_users_can_view_maintenance_details(): void
    {
        $user = User::factory()->create();
        $asset = Asset::factory()->create();
        $maintenance = Maintenance::factory()->create([
            'asset_id' => $asset->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('maintenances.show', $maintenance));

        $response->assertOk();
    }

    public function test_creator_can_delete_maintenance(): void
    {
        $user = User::factory()->create(['role' => 'technician']);
        $asset = Asset::factory()->create();
        $maintenance = Maintenance::factory()->create([
            'asset_id' => $asset->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('maintenances.destroy', $maintenance));

        $response->assertRedirect(route('maintenances.index'));
        $this->assertDatabaseMissing('maintenances', ['id' => $maintenance->id]);
    }

    public function test_admin_can_delete_any_maintenance(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherUser = User::factory()->create(['role' => 'technician']);
        $asset = Asset::factory()->create();
        $maintenance = Maintenance::factory()->create([
            'asset_id' => $asset->id,
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($admin)->delete(route('maintenances.destroy', $maintenance));

        $response->assertRedirect(route('maintenances.index'));
        $this->assertDatabaseMissing('maintenances', ['id' => $maintenance->id]);
    }
}
