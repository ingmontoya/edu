<?php

namespace App\Enums;

enum DisciplinaryStatus: string
{
    case OPEN = 'open';
    case IN_PROCESS = 'in_process';
    case RESOLVED = 'resolved';
    case ESCALATED = 'escalated';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Abierto',
            self::IN_PROCESS => 'En Proceso',
            self::RESOLVED => 'Resuelto',
            self::ESCALATED => 'Escalado',
        };
    }
}
