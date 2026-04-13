<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $modelLabel = 'Proveedor';
    protected static ?string $pluralModelLabel = 'Proveedores';
    protected static bool $isScopedToTenant = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('legal_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('vat_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('risk_score')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('legal_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vat_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('risk_score')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 70 => 'danger',
                        $state >= 40 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_blocked')
                    ->label('Bloqueado')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_blocked')
                    ->label('Estado de Bloqueo'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('block')
                        ->label('Bloquear Proveedor')
                        ->icon('heroicon-o-lock-closed')
                        ->color('danger')
                        ->hidden(fn (Vendor $record): bool => $record->is_blocked)
                        ->form([
                            Forms\Components\Textarea::make('blocking_reason')
                                ->label('Motivo del Bloqueo')
                                ->required(),
                        ])
                        ->action(fn (Vendor $record, array $data) => $record->update([
                            'is_blocked' => true,
                            'blocking_reason' => $data['blocking_reason'],
                        ]))
                        ->successNotificationTitle('Proveedor bloqueado correctamente.'),
                    Tables\Actions\Action::make('unblock')
                        ->label('Desbloquear')
                        ->icon('heroicon-o-lock-open')
                        ->color('success')
                        ->visible(fn (Vendor $record): bool => $record->is_blocked)
                        ->requiresConfirmation()
                        ->action(fn (Vendor $record) => $record->update([
                            'is_blocked' => false,
                            'blocking_reason' => null,
                        ]))
                        ->successNotificationTitle('Proveedor desbloqueado.'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
