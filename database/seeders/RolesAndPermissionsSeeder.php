<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos la cache de Spatie por seguridad
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear Permisos (Operativa UAS)
        $guard = 'web'; // Define the guard explicitly for APIs
        
        Permission::create(['name' => 'create-flights', 'guard_name' => $guard]);
        Permission::create(['name' => 'view-own-flights', 'guard_name' => $guard]);
        Permission::create(['name' => 'view-all-flights', 'guard_name' => $guard]);
        Permission::create(['name' => 'manage-drones', 'guard_name' => $guard]);
        Permission::create(['name' => 'manage-pilots', 'guard_name' => $guard]);
        Permission::create(['name' => 'manage-operator-data', 'guard_name' => $guard]);
        Permission::create(['name' => 'view-operator-reports', 'guard_name' => $guard]);

        // 2. Crear Roles y asignar permisos

        // Rol: Piloto (Autónomo o miembro de Ayto que solo vuela)
        $rolePiloto = Role::create(['name' => 'piloto', 'guard_name' => $guard]);
        $rolePiloto->syncPermissions(Permission::whereIn('name', ['create-flights', 'view-own-flights'])->where('guard_name', $guard)->get());

        // Rol: Jefe de Operaciones (Responsable de Vuelos de Policía/Ayto)
        $roleJefeOp = Role::create(['name' => 'jefe-operaciones', 'guard_name' => $guard]);
        $roleJefeOp->syncPermissions(Permission::whereIn('name', [
            'create-flights', 
            'view-own-flights', 
            'view-all-flights', 
            'manage-drones', 
            'manage-pilots'
        ])->where('guard_name', $guard)->get());

        // Rol: Representante del Operador (Alcalde, Concejal, CEO...)
        $roleRepresentante = Role::create(['name' => 'representante-operador', 'guard_name' => $guard]);
        $roleRepresentante->syncPermissions(Permission::where('guard_name', $guard)->get());

        // Rol: Super Admin Global (Aicor)
        $roleSuperAdmin = Role::create(['name' => 'super-admin', 'guard_name' => $guard]);
        // El Super Admin tendrá un Gate en el AuthServiceProvider que le dará todos los permisos por defecto
    }
}
