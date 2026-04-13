<?php

namespace App\Filament\Widgets;

use App\Models\Vendor;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\VendorResource;
use Filament\Forms;
use Filament\Forms\Components\Textarea;

class VendorRiskWidget extends BaseWidget
{
    protected static ?string $heading = '🏢 Ranking de Riesgo de Proveedores (Muro de la Vergüenza)';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '500px';

    protected function isTablePaginationSimple(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Vendor::query()
                    ->withCount([
                        'invoices',
                        'invoices as fraud_count' => fn ($query) => $query->where('audit_status', 'fraud_confirmed')
                    ])
                    ->having('fraud_count', '>', 0)
                    ->orderByDesc('fraud_count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('legal_name')
                    ->label('Proveedor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoices_count')
                    ->label('Total Facturas')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->alignment('center'),
                Tables\Columns\TextColumn::make('fraud_count')
                    ->label('Fraudes Detectados')
                    ->badge()
                    ->color('danger')
                    ->sortable()
                    ->alignment('center')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('ratio')
                    ->label('Riesgo')
                    ->state(function (Vendor $record): string {
                        $ratio = ($record->fraud_count / max($record->invoices_count, 1)) * 100;
                        return round($ratio, 1) . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => (float)$state >= 20 ? 'danger' : 'warning'),
            ])
            ->actions([
                Tables\Actions\Action::make('Ver Proveedor')
                    ->label('Ver Proveedor')
                    ->icon('heroicon-m-building-office')
                    ->color('info')
                    ->url(fn (Vendor $record): string => VendorResource::getUrl('edit', ['record' => $record])),
                Tables\Actions\Action::make('block')
                    ->label('Bloquear')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->hidden(fn (Vendor $record): bool => (bool)$record->is_blocked)
                    ->form([
                        Textarea::make('blocking_reason')
                            ->label('Motivo del Bloqueo')
                            ->required(),
                    ])
                    ->action(fn (Vendor $record, array $data) => $record->update([
                        'is_blocked' => true,
                        'blocking_reason' => $data['blocking_reason'],
                    ]))
                    ->successNotificationTitle('Proveedor bloqueado.'),
            ])
;
    }
}
