<?php

namespace App\Enums;

enum PerformanceLevel: string
{
    case SUPERIOR = 'superior';
    case HIGH = 'high';
    case BASIC = 'basic';
    case LOW = 'low';

    public function label(): string
    {
        return match($this) {
            self::SUPERIOR => 'Desempeño Superior',
            self::HIGH => 'Desempeño Alto',
            self::BASIC => 'Desempeño Básico',
            self::LOW => 'Desempeño Bajo',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::SUPERIOR => 'S',
            self::HIGH => 'A',
            self::BASIC => 'Bs',
            self::LOW => 'Bj',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::SUPERIOR => 'Supera los logros propuestos. Demuestra dominio excepcional de los conocimientos.',
            self::HIGH => 'Alcanza satisfactoriamente los logros propuestos con buen nivel de desempeño.',
            self::BASIC => 'Alcanza los logros mínimos con dificultades. Requiere actividades de refuerzo.',
            self::LOW => 'No alcanza los logros mínimos. Requiere actividades de nivelación obligatorias.',
        };
    }

    /**
     * Convert numeric grade to performance level using default scale
     */
    public static function fromGrade(float $grade, ?array $scale = null): self
    {
        $scale = $scale ?? self::defaultScale();

        return match(true) {
            $grade >= $scale['superior'] => self::SUPERIOR,
            $grade >= $scale['high'] => self::HIGH,
            $grade >= $scale['basic'] => self::BASIC,
            default => self::LOW,
        };
    }

    /**
     * Default grading scale (Colombian standard 1.0-5.0)
     */
    public static function defaultScale(): array
    {
        return [
            'min' => 1.0,
            'max' => 5.0,
            'passing' => 3.0,
            'basic' => 3.0,
            'high' => 4.0,
            'superior' => 4.6,
        ];
    }

    /**
     * Get minimum grade for this level
     */
    public function minGrade(?array $scale = null): float
    {
        $scale = $scale ?? self::defaultScale();

        return match($this) {
            self::SUPERIOR => $scale['superior'],
            self::HIGH => $scale['high'],
            self::BASIC => $scale['basic'],
            self::LOW => $scale['min'],
        };
    }

    /**
     * Check if this level is passing
     */
    public function isPassing(): bool
    {
        return $this !== self::LOW;
    }

    /**
     * Check if this level requires remedial activities
     */
    public function requiresRemedial(): bool
    {
        return $this === self::LOW;
    }

    public function color(): string
    {
        return match($this) {
            self::SUPERIOR => 'green',
            self::HIGH => 'blue',
            self::BASIC => 'yellow',
            self::LOW => 'red',
        };
    }

    public function bgColor(): string
    {
        return match($this) {
            self::SUPERIOR => 'bg-green-100',
            self::HIGH => 'bg-blue-100',
            self::BASIC => 'bg-yellow-100',
            self::LOW => 'bg-red-100',
        };
    }
}
