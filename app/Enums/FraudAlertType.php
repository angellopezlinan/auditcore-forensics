<?php

declare(strict_types=1);

namespace App\Enums;

enum FraudAlertType: string
{
    case IBAN_CROSSING = 'iban_crossing';
    case DUPLICATE_INVOICE = 'duplicate_invoice';
    case PRICE_CREEPING = 'price_creeping';
    case PHANTOM_VENDOR = 'phantom_vendor';

    public function label(): string
    {
        return match ($this) {
            self::IBAN_CROSSING => 'Cruce de IBAN sospechoso',
            self::DUPLICATE_INVOICE => 'Factura duplicada',
            self::PRICE_CREEPING => 'Incremento de precio progresivo (Creeping)',
            self::PHANTOM_VENDOR => 'Proveedor fantasma o inexistente',
        };
    }
}
