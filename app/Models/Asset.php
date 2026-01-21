<?php

namespace App\Models;

use App\Enums\AssetStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_number',
        'purchase_date',
        'status',
        'location',
        'description',
        'purchase_cost',
        'maintenance_interval_days',
        'next_maintenance_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => AssetStatus::class,
            'purchase_date' => 'date',
            'next_maintenance_date' => 'date',
            'purchase_cost' => 'decimal:2',
        ];
    }

    /**
     * Get maintenances for this asset.
     */
    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Scope a query to only include active assets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', AssetStatus::ACTIVE);
    }

    /**
     * Scope a query to only include broken assets.
     */
    public function scopeBroken($query)
    {
        return $query->where('status', AssetStatus::BROKEN);
    }

    /**
     * Calculate total maintenance cost for this asset.
     */
    public function totalMaintenanceCost(): float
    {
        return $this->maintenances()->sum('cost');
    }

    /**
     * Get the latest maintenance record.
     */
    public function latestMaintenance()
    {
        return $this->maintenances()->latest('performed_at')->first();
    }
}
