<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Agente / Usuario';
    protected static ?string $pluralModelLabel = 'Plantilla de Agentes';
    protected static ?string $navigationLabel = 'Plantilla';
    protected static ?int $navigationSort = 1; // Para que salga arriba en el menú
    protected static bool $isScopedToTenant = true;
    protected static ?string $tenantOwnershipRelationshipName = 'teams'; // Ruta: De Usuario a Entidades
    protected static ?string $tenantRelationshipName = 'users';          // Ruta: De Entidad a Usuarios

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos del Usuario')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre Completo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->pivotData(fn (): array => ['team_id' => filament()->getTenant()->getKey()])
                            ->label('Roles en esta Entidad')
                            ->required(),
                        //         $user = auth()->user();
                        //         return $user->organization_id;
                        //     }),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre Completo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Rango')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Alta')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('unlock')
                    ->label('Desbloquear Usuario')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->hidden(fn (User $record): bool => !$record->is_locked || !auth()->user()?->hasRole('super_admin'))
                    ->action(function (User $record) {
                        $record->update([
                            'is_locked' => false,
                            'two_factor_attempts' => 0,
                        ]);

                        Notification::make()
                            ->title('Usuario desbloqueado con éxito')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('reset2fa')
                    ->label('Resetear 2FA')
                    ->icon('heroicon-m-shield-exclamation')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Resetear Autenticación 2FA')
                    ->modalDescription('¿Estás seguro de que deseas resetear el 2FA de este usuario? En su próximo inicio de sesión, el sistema le obligará a configurar un nuevo dispositivo.')
                    ->visible(fn () => auth()->check() && auth()->user()->hasRole('super_admin'))
                    ->action(function (User $record) {
                        // Bypass total: Forzamos la base de datos en crudo
                        \Illuminate\Support\Facades\DB::table('users')->where('id', $record->id)->update([
                            'two_factor_secret' => null,
                            'two_factor_confirmed_at' => null,
                            'two_factor_attempts' => 0,
                        ]);

                        // Si el usuario se resetea a sí mismo, le borramos la marca de la sesión actual
                        if (auth()->id() === $record->id) {
                            session()->forget('two_factor_authenticated');
                        }

                        Notification::make()
                            ->title('2FA Reseteado con éxito')
                            ->body('La base de datos se ha limpiado. Se requerirá un nuevo dispositivo.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make()
                    ->hidden(fn (\App\Models\User $record): bool => $record->email === 'jefe@cordoba.es' && auth()->user()?->getKey() !== 'jefe@cordoba.es'),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (\App\Models\User $record): bool => $record->email === 'jefe@cordoba.es'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Si el usuario que está mirando la pantalla NO es el Jefe, ocultamos al Jefe de la lista
        if (auth()->user()?->email !== 'jefe@cordoba.es') {
            $query->where('email', '!=', 'jefe@cordoba.es');
        }
        
        return $query;
    }
}
