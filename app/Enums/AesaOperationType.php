<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AesaOperationType: string implements HasLabel, HasColor
{
    case OPEN_CATEGORY = 'OPEN-CATEGORY';
    case STS_01 = 'STS-01';
    case STS_02 = 'STS-02';
    case SORA = 'SORA';
    case NO_EASA = 'NO-EASA';
    case TRAINING = 'TRAINING';

    public static function fromValue(?string $value): ?self
    {
        if (!$value) {
            return null;
        }

        return self::tryFrom(strtolower($value)) ?? self::tryFrom(strtoupper($value)) ?? null;
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::OPEN_CATEGORY => 'Categoría Abierta (A1, A2, A3)',
            self::STS_01 => 'STS-01 (Escenario Estándar VLOS)',
            self::STS_02 => 'STS-02 (Escenario Estándar BVLOS)',
            self::SORA => 'Categoría Específica (SORA)',
            self::NO_EASA => 'Operación de Estado (No EASA - RD 1036/2017)',
            self::TRAINING => 'Vuelo de Entrenamiento / Mantenimiento',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::OPEN_CATEGORY => 'success',
            self::STS_01, self::STS_02 => 'warning',
            self::SORA => 'danger',
            self::NO_EASA => 'info',
            self::TRAINING => 'gray',
        };
    }
}
