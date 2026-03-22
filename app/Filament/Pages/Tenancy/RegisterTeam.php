<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Crear Entidad';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre de la Entidad (Ej: Ayuntamiento de Córdoba, Autoridad Portuaria...)')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        // 1. Crea la nueva entidad
        $team = Team::create($data);
        
        $user = filament()->auth()->user();
        
        // 2. Vincula al usuario a la entidad
        $user->teams()->attach($team);
        
        // 3. Le otorga la llave maestra dentro de esta nueva entidad
        setPermissionsTeamId($team->id);
        $user->assignRole('super_admin');
        
        return $team;
    }
}
