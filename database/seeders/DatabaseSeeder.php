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

    // 1. Creamos un Equipo (Entidad Central)
    $team = \App\Models\Team::create([
        'name' => 'AuditCore Forensics - División Central',
    ]);

    $this->command->info('¡Entidad inicial creada con éxito!');

    // 2. Crear usuario SuperAdmin
    $user = \App\Models\User::create([
        'name' => 'Admin AuditCore',
        'email' => 'admin@auditcore.app',
        'password' => bcrypt('password123'),
    ]);

    $user->teams()->attach($team);
    
    // Set Spatie Team ID so the role pivot captures it
    setPermissionsTeamId($team->id);
    $user->assignRole('super-admin');
    
    $this->command->info('¡Admin configurado en la División Central!');
}
}