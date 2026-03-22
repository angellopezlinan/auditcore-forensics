<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // 0. Set up roles and permissions
    $this->call(RolesAndPermissionsSeeder::class);

    // 1. Creamos un Equipo (Entidad/Jefatura)
    $teamCordoba = \App\Models\Team::create([
        'name' => 'Policía Local de Córdoba (UAS Unit)',
    ]);

    // 2. Le asignamos un par de drones a este equipo específico
    $teamCordoba->drones()->create([
        'model' => 'Matrice 30T',
        'brand' => 'DJI',
        'serial_number' => '158CF4567890',
        'weight_grams' => 3770, // Peso en gramos
        'class_mark' => 'C2'
    ]);

    $teamCordoba->drones()->create([
        'model' => 'Mavic 3 Enterprise',
        'brand' => 'DJI',
        'serial_number' => '158DG1234567',
        'weight_grams' => 915,
        'class_mark' => 'C1'
    ]);

    $this->command->info('¡Hangar completado! Equipo y Drones creados con éxito.');

    // 3. Crear usuario Jefe de Policía y asignarle el Rol de representante o jefe
    $user = \App\Models\User::create([
        'name' => 'Jefe Policia',
        'email' => 'jefe@cordoba.es',
        'password' => bcrypt('123456'),
    ]);

    $user->teams()->attach($teamCordoba);
    
    // Set Spatie Team ID so the role pivot captures it
    setPermissionsTeamId($teamCordoba->id);
    $user->assignRole('representante-operador');
    
    // 4. Crear un Piloto básico para pruebas
    $pilotUser = \App\Models\User::create([
        'name' => 'Agente Piloto 01',
        'email' => 'piloto1@cordoba.es',
        'password' => bcrypt('123456'),
    ]);
    $pilotUser->teams()->attach($teamCordoba);
    $pilotUser->assignRole('piloto');
}
}