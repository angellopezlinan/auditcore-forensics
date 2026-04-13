<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';
    protected static ?string $navigationLabel = 'Facturas';
    protected static bool $isScopedToTenant = true;
    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function canViewAny(): bool
    {
        return true; // Acceso total garantizado
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Factura')
                    ->schema([
                        Forms\Components\Select::make('vendor_id')
                            ->relationship('vendor', 'legal_name')
                            ->label('Proveedor')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('invoice_number')
                            ->label('Número de Factura')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('issue_date')
                            ->label('Fecha de Emisión')
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('total_amount')
                            ->label('Importe Total')
                            ->required()
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Impuestos')
                            ->numeric()
                            ->prefix('€'),
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options(\App\Enums\InvoiceStatus::class)
                            ->required()
                            ->default(\App\Enums\InvoiceStatus::PENDING->value),
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Archivo de Factura (Bóveda Privada)')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Documento PDF')
                            ->disk('private')
                            ->directory('invoices')
                            ->visibility('private')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->downloadable()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vendor.legal_name')
                    ->label('Proveedor')
                    ->placeholder('Sin Proveedor')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Nº Factura')
                    ->searchable(),
                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax_amount')
                    ->label('Impuestos')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof \App\Enums\InvoiceStatus ? $state : \App\Enums\InvoiceStatus::from($state)) {
                        \App\Enums\InvoiceStatus::PENDING => 'gray',
                        \App\Enums\InvoiceStatus::OCR_PROCESSING => 'info',
                        \App\Enums\InvoiceStatus::PROCESSED => 'success',
                        \App\Enums\InvoiceStatus::FLAGGED => 'danger',
                        \App\Enums\InvoiceStatus::CLEAN => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('audit_status')
                    ->label('Estado Auditoría')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'gray',
                        'cleared' => 'success',
                        'fraud_confirmed' => 'danger',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'cleared' => 'Legítima',
                        'fraud_confirmed' => 'Fraude',
                        default => $state ?? 'Pendiente',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vendor.is_blocked')
                    ->label('Proveedor Bloqueado')
                    ->badge()
                    ->state(fn (?Invoice $record) => $record?->vendor?->is_blocked ? 'BLOQUEADO' : null)
                    ->color('danger')
                    ->icon('heroicon-m-no-symbol')
                    ->visible(fn (?Invoice $record) => $record?->vendor?->is_blocked ?? false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagada',
                        'cancelled' => 'Cancelada',
                    ]),
                Tables\Filters\SelectFilter::make('audit_status')
                    ->label('Filtrar por Auditoría')
                    ->options([
                        'pending' => 'Pendientes',
                        'cleared' => 'Legítimas',
                        'fraud_confirmed' => 'Fraudes Confirmados',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->url(fn (Invoice $record) => route('vault.invoices.download', $record))
                    ->openUrlInNewTab()
                    ->authorize(true),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'duplicate-audit' => Pages\DuplicateAudit::route('/duplicate-audit'),
            'weekend-audit' => Pages\WeekendAudit::route('/weekend-audit'),
        ];
    }
}
