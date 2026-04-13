<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Facades\Filament;

class WeekendAudit extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = InvoiceResource::class;

    protected static ?string $title = 'Auditoría de Fin de Semana';
    protected static ?string $navigationLabel = 'Auditoría Fin de Semana';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = '🕵️‍♂️ Inteligencia Forense';

    protected static string $view = 'filament.resources.invoice-resource.pages.weekend-audit';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Invoice::query()
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
                Tables\Columns\TextColumn::make('audit_status')
                    ->label('Estado')
                    ->badge()
                    ->color('warning'),
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
                        $record->update(['audit_status' => 'fraud_confirmed']);

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
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
