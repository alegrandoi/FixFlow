<?php

namespace App\Enums;

enum AssetStatus: string
{
    case ACTIVE = 'active';
    case BROKEN = 'broken';
    case UNDER_MAINTENANCE = 'under_maintenance';
    case RETIRED = 'retired';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Activo',
            self::BROKEN => 'Averiado',
            self::UNDER_MAINTENANCE => 'En Mantenimiento',
            self::RETIRED => 'Retirado',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'green',
            self::BROKEN => 'red',
            self::UNDER_MAINTENANCE => 'yellow',
            self::RETIRED => 'gray',
        };
    }
}
