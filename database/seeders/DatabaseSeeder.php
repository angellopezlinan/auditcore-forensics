<?php

namespace Database\Seeders;

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
        // 1. Crear o recuperar el Team principal: HQ
        $team = \App\Models\Team::firstOrCreate([
            'name' => 'AuditCore HQ',
        ]);

        $this->command->info('¡Entidad AuditCore HQ lista!');

        // 2. Crear o actualizar el usuario maestro (DIOS)
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'admin@auditcore.com'],
            [
                'name' => 'Angel Lopez',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // a) Vincular al usuario a la oficina
        if (!$user->teams()->where('team_id', $team->id)->exists()) {
            $user->teams()->attach($team->id);
        }

        // b) Establecer equipo actual
        $user->current_team_id = $team->id;
        $user->save();
        
        // c) Asignar poder absoluto (Spatie Role)
        // Nota: Aseguramos que el ID del equipo esté en el contexto si se usa Spatie con Tenancy
        if (function_exists('setPermissionsTeamId')) {
            setPermissionsTeamId($team->id);
        }
        
        $user->assignRole('super_admin');
        
        $this->command->info('¡SuperAdmin [Angel Lopez] configurado y vinculado a HQ!');

        // 3. Inyección de Datos Final (Proveedores y Facturas)
        $this->call([
            VendorSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}