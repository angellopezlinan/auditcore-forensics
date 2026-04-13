<?php

namespace App\Filament\Imports;

use App\Models\Invoice;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class InvoiceImporter extends Importer
{
    protected static ?string $model = Invoice::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('vendor')
                ->relationship()
                ->label('Proveedor')
                ->requiredMapping(),
            ImportColumn::make('invoice_number')
                ->label('Número de Factura')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('issue_date')
                ->label('Fecha de Emisión')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('total_amount')
                ->label('Importe Total')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric', 'min:0']),
            ImportColumn::make('tax_amount')
                ->label('Impuestos')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('description')
                ->label('Descripción')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?Invoice
    {
        $invoice = new Invoice();
        $invoice->team_id = filament()->getTenant()->getKey();

        return $invoice;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your invoice import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
