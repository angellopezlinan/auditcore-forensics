<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Model;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Seguridad';
    protected static ?string $modelLabel = 'Auditoría / Actividad';
    protected static ?string $pluralModelLabel = 'Logs de Actividad';
    protected static ?int $navigationSort = 10;

    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }

    public static function canViewAny(): bool
    {
        $superAdminRole = config('filament-shield.super_admin.name', 'super_admin');

        return auth()->user()?->hasRole($superAdminRole) ?? false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Usuario')
                    ->default(fn ($record) => $record->causer_id ? "ID: {$record->causer_id}" : 'Sistema')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Acción')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Creado',
                        'updated' => 'Actualizado',
                        'deleted' => 'Eliminado',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Modelo')
                    ->formatStateUsing(fn ($state) => str_replace('App\\Models\\', '', $state)),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID Ref'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Acción')
                    ->options([
                        'created' => 'Creado',
                        'updated' => 'Actualizado',
                        'deleted' => 'Eliminado',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detalle del Evento')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')->label('Acción'),
                        Infolists\Components\TextEntry::make('created_at')->label('Fecha y Hora')->dateTime(),
                        Infolists\Components\TextEntry::make('causer.name')->label('Responsable'),
                    ])->columns(3),
                Infolists\Components\Section::make('Cambios Realizados')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties.old')
                            ->label('Valores Anteriores')
                            ->keyLabel('Campo')
                            ->valueLabel('Valor')
                            ->columnSpan(1),
                        Infolists\Components\KeyValueEntry::make('properties.attributes')
                            ->label('Valores Nuevos')
                            ->keyLabel('Campo')
                            ->valueLabel('Valor')
                            ->columnSpan(1),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
