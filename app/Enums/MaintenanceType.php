<?php

namespace App\Enums;

enum MaintenanceType: string
{
    case PREVENTIVE = 'preventive';
    case CORRECTIVE = 'corrective';

    public function label(): string
    {
        return match($this) {
            self::PREVENTIVE => 'Preventivo',
            self::CORRECTIVE => 'Correctivo',
        };
    }
}
