<?php

namespace App\Enums;

enum DisciplinaryType: string
{
    case TYPE1 = 'type1';
    case TYPE2 = 'type2';
    case TYPE3 = 'type3';

    public function label(): string
    {
        return match ($this) {
            self::TYPE1 => 'Tipo 1 - Conflicto o desacuerdo',
            self::TYPE2 => 'Tipo 2 - Agresión o acoso escolar',
            self::TYPE3 => 'Tipo 3 - Vulneración de derechos',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::TYPE1 => 'Situaciones esporádicas que no generan daños al cuerpo o a la salud física y mental de quienes intervienen.',
            self::TYPE2 => 'Situaciones de agresión escolar, acoso escolar (bullying) y ciberacoso (ciberbullying).',
            self::TYPE3 => 'Situaciones que vulneran los derechos humanos, el DIH o delitos en el ámbito escolar.',
        };
    }
}
