<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncidentResource\Pages;
use App\Filament\Resources\IncidentResource\RelationManagers;
use App\Models\Incident;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IncidentResource extends Resource
{
    protected static ?string $model = Incident::class;
    protected static bool $isScopedToTenant = true;
    protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Seguridad';
    protected static ?string $modelLabel = 'Incidente';
    protected static ?string $pluralModelLabel = 'Gestión de Incidentes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Apertura del Expediente')
                    ->description('Detalles principales del evento ocurrido.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título Descriptivo')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                            
                        Forms\Components\DatePicker::make('date')
                            ->label('Fecha del Suceso')
                            ->required()
                            ->default(now()),
                            
                        Forms\Components\Select::make('category')
                            ->label('Categoría SGS / SMS')
                            ->options([
                                'accident' => 'Accidente (Daños mayores)',
                                'incident' => 'Incidente de Seguridad',
                                'near_miss' => 'Near-Miss / Susto Operativo',
                                'hazard' => 'Peligro / Riesgo Detectado',
                            ])
                            ->required()
                            ->native(false),
                            
                        Forms\Components\Select::make('severity')
                            ->label('Nivel de Gravedad')
                            ->options([
                                'low' => 'Baja',
                                'medium' => 'Media',
                                'high' => 'Alta',
                                'critical' => 'Crítica',
                            ])
                            ->required()
                            ->native(false),
                    ])->columns(3),
                Forms\Components\Section::make('Responsable')
                    ->description('Vincular evento al usuario responsable.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Usuario Responsable')
                            ->relationship(
                                name: 'user', 
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->whereHas('teams', fn($q) => $q->where('teams.id', filament()->getTenant()->getKey()))
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->user()?->getKey()),
                    ])->columns(1),

                Forms\Components\Section::make('Análisis Causal y Acciones')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción Completa de los Hechos')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('root_cause')
                            ->label('Análisis de Causa Raíz')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('mitigation_actions')
                            ->label('Acciones Mitigadoras y/o Correctoras')
                            ->rows(3)
                            ->columnSpanFull(),
                            
                        Forms\Components\Select::make('status')
                            ->label('Estado de la Investigación')
                            ->options([
                                'open' => 'Abierto (Pendiente de acción)',
                                'investigating' => 'En Investigación',
                                'closed' => 'Cerrado / Mitigado',
                            ])
                            ->default('open')
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Reporte')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Responsable')
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accident' => 'danger',
                        'incident' => 'warning',
                        'near_miss' => 'info',
                        'hazard' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accident' => 'Accidente',
                        'incident' => 'Incidente',
                        'near_miss' => 'Near-Miss',
                        'hazard' => 'Peligro',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('severity')
                    ->label('Gravedad')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'critical' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'Baja',
                        'medium' => 'Media',
                        'high' => 'Alta',
                        'critical' => 'Crítica',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'danger',
                        'investigating' => 'warning',
                        'closed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Abierto',
                        'investigating' => 'En curso',
                        'closed' => 'Cerrado',
                        default => $state,
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->action(function (Incident $record) {
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.incident-report', ['incident' => $record]);
                        return response()->streamDownload(fn () => print($pdf->output()), 'SMS_Report_' . $record->id . '.pdf');
                    }),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListIncidents::route('/'),
            'create' => Pages\CreateIncident::route('/create'),
            'edit' => Pages\EditIncident::route('/{record}/edit'),
        ];
    }
}
