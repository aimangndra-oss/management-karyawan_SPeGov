<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TO_DO = 'to_do';
    case IN_PROGRESS = 'in_progress';
    case REVIEW = 'review';
    case DONE = 'done';

    public function label(): string
    {
        return match($this) {
            self::TO_DO => 'Belum Dimulai',
            self::IN_PROGRESS => 'Sedang Dikerjakan',
            self::REVIEW => 'Menunggu Review',
            self::DONE => 'Selesai',
        };
    }
}
