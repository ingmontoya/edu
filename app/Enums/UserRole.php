<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case COORDINATOR = 'coordinator';
    case TEACHER = 'teacher';
    case GUARDIAN = 'guardian';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::COORDINATOR => 'Coordinador',
            self::TEACHER => 'Docente',
            self::GUARDIAN => 'Acudiente',
        };
    }
}
