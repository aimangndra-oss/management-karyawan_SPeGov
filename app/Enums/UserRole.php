<?php

namespace App\Enums;

enum UserRole: string
{
    case KABID = 'kabid';
    case STAFF = 'staff';

    public function label(): string
    {
        return match($this) {
            self::KABID => 'Kepala Bidang',
            self::STAFF => 'Staff',
        };
    }
}
