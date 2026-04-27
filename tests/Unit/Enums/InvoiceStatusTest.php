<?php

namespace Tests\Unit\Enums;

use App\Enums\InvoiceStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InvoiceStatusTest extends TestCase
{
    #[DataProvider('statusLabelProvider')]
    public function test_it_returns_the_expected_label(InvoiceStatus $status, string $label): void
    {
        $this->assertSame($label, $status->label());
    }

    /**
     * @return array<string, array{0: InvoiceStatus, 1: string}>
     */
    public static function statusLabelProvider(): array
    {
        return [
            'pending' => [InvoiceStatus::PENDING, 'Pendiente'],
            'ocr processing' => [InvoiceStatus::OCR_PROCESSING, 'Procesando (OCR)'],
            'processed' => [InvoiceStatus::PROCESSED, 'Procesada'],
            'flagged' => [InvoiceStatus::FLAGGED, 'Marcada (Sospechosa)'],
            'clean' => [InvoiceStatus::CLEAN, 'Limpia / Verificada'],
        ];
    }
}
