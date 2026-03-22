<?php

declare(strict_types=1);

namespace App\Enums;

enum InvoiceStatus: string
{
    case PENDING = 'pending';
    case OCR_PROCESSING = 'ocr_processing';
    case PROCESSED = 'processed';
    case FLAGGED = 'flagged';
    case CLEAN = 'clean';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::OCR_PROCESSING => 'Procesando (OCR)',
            self::PROCESSED => 'Procesada',
            self::FLAGGED => 'Marcada (Sospechosa)',
            self::CLEAN => 'Limpia / Verificada',
        };
    }
}
