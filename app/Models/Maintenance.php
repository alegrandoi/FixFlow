<?php

namespace App\Models;

use App\Enums\MaintenanceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'user_id',
        'description',
        'cost',
        'performed_at',
        'type',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => MaintenanceType::class,
            'performed_at' => 'datetime',
            'cost' => 'decimal:2',
        ];
    }

    /**
     * Get the asset that owns this maintenance.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the technician (user) that performed this maintenance.
     */
    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
