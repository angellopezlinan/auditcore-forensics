<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class LicensesRelationManager extends RelationManager
{
    protected static string $relationship = 'licenses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Tipo de Titulación/Licencia')
                    ->options([
                        'STS-01' => 'Escenarios Estándar STS-01',
                        'STS-02' => 'Escenarios Estándar STS-02',
                        'Radiofonista' => 'Certificado de Radiofonista',
                        'Certificado Médico' => 'Certificado Médico Aeronáutico',
                        'Operador' => 'Registro de Operador',
                        'Otro' => 'Otra Titulación',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('identifier')
                    ->label('Identificador / N.º Certificado')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('expiry_date')
                    ->label('Fecha de Caducidad')
                    ->required(),
                Forms\Components\FileUpload::make('certificate_path')
                    ->label('Documento Adjunto (PDF)')
                    ->disk('local')
                    ->visibility('private')
                    ->downloadable()
                    ->directory('licenses')
                    ->acceptedFileTypes(['application/pdf']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('identifier')
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Tipo'),
                Tables\Columns\TextColumn::make('identifier')->label('Identificador'),
                Tables\Columns\TextColumn::make('expiry_date')->label('Caducidad')->date(),
                Tables\Columns\TextColumn::make('certificate_path')
                    ->label('Expediente')
                    ->formatStateUsing(fn ($state) => $state ? 'Ver Documento' : 'No Adjunto')
                    ->action(fn ($record) => Storage::disk('local')->download($record->certificate_path))
                    ->color(fn ($state) => $state ? 'primary' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
