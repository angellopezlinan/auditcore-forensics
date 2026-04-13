<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\InvoiceResource;
use Filament\Facades\Filament;

class WeekendInvoicesWidget extends BaseWidget
{
    protected static ?string $heading = '📅 Facturas en Fin de Semana (Pendientes)';
    
    protected static ?int $sort = 4;

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
                \App\Models\Invoice::query()
                    ->where('team_id', Filament::getTenant()->id)
                    ->whereRaw('WEEKDAY(issue_date) >= 5')
                    ->where('audit_status', 'pending')
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Nº Factura')
                    ->searchable()
                    ->sortable(),
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
                    ->alignment('end'),
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

                Tables\Actions\Action::make('view_detail')
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
