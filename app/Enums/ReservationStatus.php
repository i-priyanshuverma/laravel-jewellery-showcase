<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case Active = 'active';
    case Expired = 'expired';
    case Released = 'released';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active Hold',
            self::Expired => 'Expired',
            self::Released => 'Released',
        };
    }
}
