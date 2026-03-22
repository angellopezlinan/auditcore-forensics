<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OperatorDocumentType: string implements HasLabel, HasColor
{
    case EARO = 'EARO';
    case ERP = 'ERP';
    case MIR_COMMS = 'MIR_COMMS';
    case INSURANCE = 'SEGURO-RC';
    case OPERATOR_REGISTRATION = 'REGISTRO-OPERADOR';
    case OPERATIONS_MANUAL = 'MANUAL-OPERACIONES';
    case STS_01 = 'STS-01';
    case STS_02 = 'STS-02';
    case OTHERS = 'OTROS';

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
            self::EARO => 'Estudio Aeronáutico de Seguridad (EARO)',
            self::ERP => 'Plan de Respuesta a Emergencias (ERP)',
            self::MIR_COMMS => 'Justificante de Comunicación al MIR',
            self::INSURANCE => 'Póliza de Seguro de Responsabilidad Civil',
            self::OPERATOR_REGISTRATION => 'Certificado de Registro de Operador UAS',
            self::OPERATIONS_MANUAL => 'Manual de Operaciones (MO)',
            self::STS_01 => 'Declaración Operacional (STS-01)',
            self::STS_02 => 'Declaración Operacional (STS-02)',
            self::OTHERS => 'Otros Documentos',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::EARO, self::ERP => 'danger',
            self::INSURANCE, self::OPERATOR_REGISTRATION, self::OPERATIONS_MANUAL => 'success',
            self::STS_01, self::STS_02, self::MIR_COMMS => 'warning',
            default => 'gray',
        };
    }
    
    public function getShortLabel(): string
    {
        return match ($this) {
            self::EARO => 'Estudio Seguridad (EARO)',
            self::ERP => 'Emergencias (ERP)',
            self::MIR_COMMS => 'Comunicación MIR',
            self::INSURANCE => 'Seguro Responsabilidad Civil',
            self::OPERATOR_REGISTRATION => 'Certificado de Operador UAS',
            self::OPERATIONS_MANUAL => 'Manual de Operaciones (MO)',
            self::STS_01 => 'Declaración STS-01',
            self::STS_02 => 'Declaración STS-02',
            self::OTHERS => 'Otros',
        };
    }
}
