<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case EXCUSED = 'excused';

    public function label(): string
    {
        return match($this) {
            self::PRESENT => 'Presente',
            self::ABSENT => 'Ausente',
            self::LATE => 'Tardanza',
            self::EXCUSED => 'Excusa',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PRESENT => '✓',
            self::ABSENT => '✗',
            self::LATE => 'T',
            self::EXCUSED => 'E',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PRESENT => 'green',
            self::ABSENT => 'red',
            self::LATE => 'yellow',
            self::EXCUSED => 'blue',
        };
    }
}
