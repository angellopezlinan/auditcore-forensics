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
                
                Forms\Components\Section::make('Declaración Operacional AESA (Apéndice 1)')
                    ->description('Registro de lectura y comprensión de la documentación vigente.')
                    ->schema([
                        Forms\Components\Placeholder::make('enlaces_oficiales')
                            ->label('Portal Oficial de AESA')
                            ->content(new \Illuminate\Support\HtmlString('
                                <div style="margin-bottom: 10px;">
                                    <a href="https://www.seguridadaerea.gob.es/es/ambitos/drones/operaciones-uas-drones/operaciones-con-uas-drones---categoria-especifica" target="_blank" style="display: inline-flex; align-items: center; color: #ffffff; background-color: #2563eb; text-decoration: none; font-weight: bold; padding: 8px 16px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                        🌍 Acceder al Portal de Documentos AESA
                                    </a>
                                </div>
                                <p style="font-size: 0.85rem; color: #6b7280; margin-top: 5px;">* Enlace a la web oficial de AESA para descargar siempre la última edición del <b>Manual de Operaciones</b> y los <b>ConOps</b> de su Entidad.</p>
                            ')),
                        Forms\Components\Toggle::make('mo_read_at')
                            ->label('El usuario confirma haber accedido, leído y comprendido la documentación vigente')
                            ->formatStateUsing(fn ($state) => $state !== null)
                            ->dehydrateStateUsing(fn ($state) => $state ? now() : null),
                    ]),

                Forms\Components\Section::make('Registro de Formación y Certificados')
                    ->schema([
                        Forms\Components\Repeater::make('licenses')
                            ->label('Certificados Registrados')
                            ->relationship('licenses')
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $data['team_id'] = filament()->getTenant()->getKey();
                                return $data;
                            })
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Tipo de Licencia')
                                    ->options([
                                        'A1/A3' => 'A1/A3 (Abierta)', 
                                        'STS-01' => 'STS-01 (Específica)', 
                                        'STS-02' => 'STS-02 (Específica)', 
                                        'Radiofonista' => 'Radiofonista', 
                                        'Certificado Médico' => 'Certificado Médico LAPL/Clase 3'
                                    ])->required(),
                                Forms\Components\TextInput::make('identifier')
                                    ->label('Número de Certificado')
                                    ->required(),
                                Forms\Components\DatePicker::make('issue_date')
                                    ->label('Fecha de Emisión')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\DatePicker::make('expiry_date')
                                    ->label('Fecha de Caducidad')
                                    ->native(false)
                                    ->required(),
                                \Filament\Forms\Components\FileUpload::make('certificate_path')
                                    ->label('Documento PDF (Súbelo para auto-completar todos los datos)')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->maxSize(5120)
                                    ->disk('local')
                                    ->visibility('private')
                                    ->downloadable()
                                    ->directory('licenses')
                                    ->columnSpanFull()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        if (!$state) return;

                                        try {
                                            $file = is_array($state) ? reset($state) : $state;
                                            if (!$file) return;

                                            $parser = new \Smalot\PdfParser\Parser();
                                            $pdf = $parser->parseFile($file->getRealPath());
                                            $text = strtoupper($pdf->getText()); // Pasamos a mayúsculas para buscar mejor

                                            // 1. EXTRAER TIPO DE LICENCIA
                                            if (str_contains($text, 'STS-01') || str_contains($text, 'STS 01')) {
                                                $set('type', 'STS-01');
                                            } elseif (str_contains($text, 'STS-02') || str_contains($text, 'STS 02')) {
                                                $set('type', 'STS-02');
                                            } elseif (str_contains($text, 'A1/A3') || (str_contains($text, 'A1') && str_contains($text, 'A3'))) {
                                                $set('type', 'A1/A3');
                                            } elseif (str_contains($text, 'RADIOFONISTA')) {
                                                $set('type', 'Radiofonista');
                                            } elseif (str_contains($text, 'MEDICO') || str_contains($text, 'MÉDICO') || str_contains($text, 'LAPL')) {
                                                $set('type', 'Certificado Médico');
                                            }

                                            // 2. EXTRAER NÚMERO DE CERTIFICADO (Caza patrones AESA ej: ESP-RP-XXXXX o ESPXXXXX)
                                            if (preg_match('/(ESP-RP-[A-Z0-9]+|ESP[A-Z0-9]{10,})/', $text, $matchesNum)) {
                                                $set('identifier', $matchesNum[0]);
                                            }

                                            // 3. EXTRAER FECHAS INTELIGENTES (Ignora fechas de nacimiento)
                                            if (preg_match_all('/\b(\d{2})[\/-](\d{2})[\/-](\d{4})\b/', $text, $matchesDates)) {
                                                $dates = [];
                                                foreach ($matchesDates[0] as $match) {
                                                    $dateString = str_replace('-', '/', $match);
                                                    try {
                                                        $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
                                                        // Filtro anti-fecha de nacimiento: Solo fechas posteriores a 2015
                                                        if ($parsedDate > '2015-01-01') {
                                                            $dates[] = $parsedDate;
                                                        }
                                                    } catch (\Exception $e) {}
                                                }
                                                
                                                if (count($dates) > 0) {
                                                    sort($dates); // Ordenar de más antigua a más nueva
                                                    $set('issue_date', $dates[0]); // La más antigua es la emisión
                                                    if (count($dates) > 1) {
                                                        $set('expiry_date', end($dates)); // La más nueva es la caducidad
                                                    }
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            // Si el PDF no tiene texto (es una imagen escaneada), falla silenciosamente
                                        }
                                    }),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): string => 
                                ($state['type'] ?? 'Nuevo Certificado') . 
                                (!empty($state['identifier']) ? " - " . $state['identifier'] : "")
                            )
                            ->addActionLabel('Añadir Nuevo Certificado')
                            ->collapsible()
                            ->collapsed()
                            ->defaultItems(0),
                        Forms\Components\Placeholder::make('ayuda_guardado')
                            ->content('Nota: Cualquier modificación o nuevo certificado añadido requiere pulsar "Guardar cambios" para hacerse efectiva.')
                    ]),
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
