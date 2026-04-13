<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\InvoiceResource;

class DuplicateInvoicesWidget extends BaseWidget
{
    protected static ?string $heading = '🚨 Alertas de Posibles Duplicados (Mismo Proveedor e Importe)';
    
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '500px';

    protected static bool $isLazy = false;

    public function isTablePaginationSimple(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Invoice::query()
                    ->where('team_id', Filament::getTenant()->id)
                    ->where(function ($query) {
                        $query->whereNull('audit_status')
                            ->orWhere('audit_status', 'pending');
                    })
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('invoices as i2')
                            ->whereColumn('i2.vendor_id', 'invoices.vendor_id')
                            ->whereColumn('i2.total_amount', 'invoices.total_amount')
                            ->whereColumn('i2.id', '!=', 'invoices.id')
                            ->where('i2.team_id', Filament::getTenant()->id);
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Nº Factura')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('danger')
                    ->icon('heroicon-m-exclamation-triangle'),
                Tables\Columns\TextColumn::make('vendor.legal_name')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Importe')
                    ->money('EUR')
                    ->sortable()
                    ->alignment('end')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('audit_status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'cleared' => 'success',
                        'fraud_confirmed' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('clear')
                    ->label('Marcar como Legítima')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->action(function (Invoice $record) {
                        Invoice::query()
                            ->where('vendor_id', $record->vendor_id)
                            ->where('total_amount', $record->total_amount)
                            ->where('team_id', $record->team_id)
                            ->update(['audit_status' => 'cleared']);
                    })
                    ->requiresConfirmation()
                    ->successNotificationTitle('Grupo de facturas marcado como legítimo.'),
                
                Tables\Actions\Action::make('fraud')
                    ->label('Confirmar Fraude')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->action(function (Invoice $record) {
                        // 1. La seleccionada es el fraude
                        $record->update(['audit_status' => 'fraud_confirmed']);

                        // 2. Las gemelas son legitimadas automáticamente
                        Invoice::query()
                            ->where('vendor_id', $record->vendor_id)
                            ->where('total_amount', $record->total_amount)
                            ->where('id', '!=', $record->id)
                            ->where('team_id', $record->team_id)
                            ->update(['audit_status' => 'cleared']);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('¿Confirmar Fraude?')
                    ->modalDescription('Esta factura se marcará como fraude y sus duplicados se marcarán como legítimos.')
                    ->modalSubmitActionLabel('Sí, confirmar fraude')
                    ->successNotificationTitle('Fraude confirmado y duplicados despejados.'),

                Tables\Actions\Action::make('Ver Detalle')
                    ->label('Ver Detalle')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->url(fn (Invoice $record): string => InvoiceResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(5)
            ->extremePaginationLinks();
    }
}
